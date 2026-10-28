<?php

namespace App\Services;

class EssayScoringService
{
    /**
     * Indonesian Stopwords list (kata sambung dan kata umum yang tidak memuat bobot semantik esensial)
     */
    protected static array $stopwords = [
        'yang', 'di', 'ke', 'dari', 'pada', 'dalam', 'untuk', 'dengan', 'dan', 'atau',
        'ini', 'itu', 'adalah', 'yaitu', 'yakni', 'sebagai', 'oleh', 'karena', 'maka',
        'sehingga', 'dapat', 'bisa', 'akan', 'telah', 'sudah', 'jika', 'bila', 'apabila',
        'bahwa', 'secara', 'lebih', 'sangat', 'tersebut', 'ada', 'hanya', 'antara',
        'seperti', 'terhadap', 'tentang', 'atas', 'suatu', 'para', 'ia', 'dia', 'mereka',
        'kita', 'kami', 'kamu', 'anda', 'kalian', 'saat', 'ketika', 'waktu', 'lalu',
        'kemudian', 'namun', 'tetapi', 'melainkan', 'selain', 'tidak', 'bukan', 'pun',
        'juga', 'agar', 'supaya', 'bagi', 'sampai', 'hingga', 'setelah', 'sebelum'
    ];

    /**
     * Indonesian Root Word Prefixes & Suffixes for rule-based stemming
     */
    protected static array $prefixes = [
        'meng', 'meny', 'men', 'mem', 'me',
        'peng', 'peny', 'pen', 'pem', 'pe',
        'di', 'ter', 'ke', 'ber', 'bel', 'be', 'per', 'pel', 'se'
    ];

    protected static array $suffixes = [
        'kan', 'an', 'i', 'lah', 'kah', 'pun', 'nya', 'ku', 'mu'
    ];

    /**
     * Hitung Skor Penilaian Esai Hibrida:
     * Kombinasi Bobot Kata Kunci (Keyword Rubric) + Cosine Similarity (TF-IDF)
     *
     * @param string|null $studentAnswer Jawaban yang diisi oleh siswa
     * @param string|null $referenceAnswer Kunci jawaban baku dari guru
     * @param string|null $customKeywords Kata kunci khusus yang ditentukan guru (opsional, dipisah koma)
     * @param float $alpha Bobot skor kata kunci (default: 0.5)
     * @param float $beta Bobot skor cosine similarity (default: 0.5)
     * @return array
     */
    public function evaluate(
        ?string $studentAnswer,
        ?string $referenceAnswer,
        ?string $customKeywords = null,
        float $alpha = 0.5,
        float $beta = 0.5
    ): array {
        $studentText = trim($studentAnswer ?? '');
        $refText = trim($referenceAnswer ?? '');

        if (empty($studentText)) {
            return [
                'keyword_score' => 0.0,
                'similarity_score' => 0.0,
                'final_suggested_score' => 0.0,
                'matched_keywords' => [],
                'missed_keywords' => $this->extractKeywords($customKeywords, $refText),
                'details' => 'Siswa tidak mengisi jawaban esai.'
            ];
        }

        if (empty($refText) && empty($customKeywords)) {
            return [
                'keyword_score' => 0.0,
                'similarity_score' => 0.0,
                'final_suggested_score' => 0.0,
                'matched_keywords' => [],
                'missed_keywords' => [],
                'details' => 'Kunci jawaban dan kata kunci rujukan belum disediakan oleh guru.'
            ];
        }

        // 1. Text Preprocessing
        $studentTokens = $this->preprocess($studentText);
        $refTokens = $this->preprocess($refText);

        // 2. Evaluasi Kata Kunci (Keyword Rubric Matching)
        $keywords = $this->extractKeywords($customKeywords, $refText);
        $keywordResult = $this->calculateKeywordScore($studentTokens, $keywords);

        // 3. Evaluasi TF-IDF & Cosine Similarity
        $similarityScore = $this->calculateCosineSimilarity($studentTokens, $refTokens);

        // 4. Agregasi Skor Hibrida: S_saran = (alpha * S_kw) + (beta * S_cos)
        // Jika tidak ada kata kunci khusus, gunakan pembobotan dinamis
        if (empty($keywords)) {
            $finalScore = $similarityScore;
        } else {
            $finalScore = ($alpha * $keywordResult['score']) + ($beta * $similarityScore);
        }

        $finalScore = round(min(1.0, max(0.0, $finalScore)), 2);

        return [
            'keyword_score' => round($keywordResult['score'], 2),
            'similarity_score' => round($similarityScore, 2),
            'final_suggested_score' => $finalScore,
            'matched_keywords' => $keywordResult['matched'],
            'missed_keywords' => $keywordResult['missed'],
            'student_tokens_count' => count($studentTokens),
            'ref_tokens_count' => count($refTokens),
        ];
    }

    /**
     * Pipeline Preprocessing: Case Folding, Cleaning, Tokenizing, Stopwords Removal, Stemming
     */
    public function preprocess(string $text): array
    {
        // 1. Case Folding
        $clean = mb_strtolower($text, 'UTF-8');

        // 2. Cleaning: Hapus karakter selain huruf dan angka
        $clean = preg_replace('/[^a-z0-9\s]/', ' ', $clean);
        $clean = preg_replace('/\s+/', ' ', $clean);

        // 3. Tokenizing
        $words = explode(' ', trim($clean));

        // 4. Filtering Stopwords & Length < 2
        $filtered = array_filter($words, function ($word) {
            $len = strlen($word);
            return $len >= 2 && !in_array($word, self::$stopwords, true);
        });

        // 5. Stemming Bahasa Indonesia Sederhana
        $stemmed = array_map([$this, 'stemWord'], array_values($filtered));

        return array_values(array_filter($stemmed));
    }

    /**
     * Ekstraksi Kata Kunci: Menggunakan input khusus guru atau auto-extract dari kunci jawaban
     */
    public function extractKeywords(?string $customKeywords, string $refText): array
    {
        if (!empty($customKeywords)) {
            $parts = explode(',', $customKeywords);
            $cleaned = [];
            foreach ($parts as $p) {
                $item = trim(mb_strtolower($p, 'UTF-8'));
                if (!empty($item)) {
                    $cleaned[] = $item;
                }
            }
            if (!empty($cleaned)) {
                return array_unique($cleaned);
            }
        }

        // Auto-extract dari kunci jawaban guru jika kata kunci manual belum diisi
        $tokens = $this->preprocess($refText);
        $freq = array_count_values($tokens);
        arsort($freq);

        // Ambil maksimal 7 kata paling berbobot sebagai kata kunci rujukan
        return array_slice(array_keys($freq), 0, 7);
    }

    /**
     * Hitung persentase ketercakupan kata kunci (Coverage)
     */
    protected function calculateKeywordScore(array $studentTokens, array $keywords): array
    {
        if (empty($keywords)) {
            return ['score' => 1.0, 'matched' => [], 'missed' => []];
        }

        $matched = [];
        $missed = [];

        foreach ($keywords as $kw) {
            $kwTokens = $this->preprocess($kw);
            $found = false;

            if (empty($kwTokens)) {
                continue;
            }

            // Jika kata kunci terdiri dari 1 token
            if (count($kwTokens) === 1) {
                $target = $kwTokens[0];
                if (in_array($target, $studentTokens, true)) {
                    $found = true;
                }
            } else {
                // Frasa multi-kata: cek apakah semua sub-token ada dalam jawaban siswa
                $allSubFound = true;
                foreach ($kwTokens as $st) {
                    if (!in_array($st, $studentTokens, true)) {
                        $allSubFound = false;
                        break;
                    }
                }
                $found = $allSubFound;
            }

            if ($found) {
                $matched[] = $kw;
            } else {
                $missed[] = $kw;
            }
        }

        $total = count($matched) + count($missed);
        $score = $total > 0 ? (count($matched) / $total) : 0.0;

        return [
            'score' => $score,
            'matched' => $matched,
            'missed' => $missed
        ];
    }

    /**
     * Hitung Cosine Similarity menggunakan representasi TF-IDF
     */
    public function calculateCosineSimilarity(array $tokensA, array $tokensB): float
    {
        if (empty($tokensA) || empty($tokensB)) {
            return 0.0;
        }

        // Vocabulary gabungan
        $vocab = array_unique(array_merge($tokensA, $tokensB));
        if (empty($vocab)) {
            return 0.0;
        }

        // Hitung TF (Term Frequency)
        $tfA = array_count_values($tokensA);
        $tfB = array_count_values($tokensB);

        // Korpus mini 2 dokumen untuk menghitung IDF
        $numDocs = 2;
        $vectorA = [];
        $vectorB = [];

        foreach ($vocab as $term) {
            $docFreq = 0;
            if (isset($tfA[$term])) $docFreq++;
            if (isset($tfB[$term])) $docFreq++;

            // Rumus IDF dengan smoothing: log(1 + (N / df))
            $idf = log(1 + ($numDocs / max(1, $docFreq))) + 1.0;

            $valA = (isset($tfA[$term]) ? $tfA[$term] : 0) * $idf;
            $valB = (isset($tfB[$term]) ? $tfB[$term] : 0) * $idf;

            $vectorA[$term] = $valA;
            $vectorB[$term] = $valB;
        }

        // Hitung Dot Product: A · B
        $dotProduct = 0.0;
        $magnitudeA = 0.0;
        $magnitudeB = 0.0;

        foreach ($vocab as $term) {
            $a = $vectorA[$term];
            $b = $vectorB[$term];

            $dotProduct += ($a * $b);
            $magnitudeA += ($a * $a);
            $magnitudeB += ($b * $b);
        }

        $magnitudeA = sqrt($magnitudeA);
        $magnitudeB = sqrt($magnitudeB);

        if ($magnitudeA == 0.0 || $magnitudeB == 0.0) {
            return 0.0;
        }

        $similarity = $dotProduct / ($magnitudeA * $magnitudeB);

        return min(1.0, max(0.0, $similarity));
    }

    /**
     * Algoritma Stemming Dasar Bahasa Indonesia (Penghapusan Infleksi, Partikel, Awalan & Akhiran)
     */
    public function stemWord(string $word): string
    {
        $len = strlen($word);
        if ($len <= 3) {
            return $word;
        }

        // 1. Hapus Partikel: -lah, -kah, -pun
        $word = preg_replace('/(lah|kah|pun)$/', '', $word);

        // 2. Hapus Kata Ganti Kepemilikan: -ku, -mu, -nya
        $word = preg_replace('/(ku|mu|nya)$/', '', $word);

        // 3. Hapus Akhiran: -kan, -an, -i
        if (preg_replace('/(kan|an|i)$/', '', $word) !== null) {
            $temp = preg_replace('/(kan|an|i)$/', '', $word);
            if (strlen($temp) >= 3) {
                $word = $temp;
            }
        }

        // 4. Hapus Awalan (Prefix)
        if (str_starts_with($word, 'meng')) {
            $stem = substr($word, 4);
            return (strlen($stem) >= 3) ? $stem : $word;
        }
        if (str_starts_with($word, 'meny')) {
            $stem = 's' . substr($word, 4);
            return (strlen($stem) >= 3) ? $stem : $word;
        }
        if (str_starts_with($word, 'mem')) {
            $sub = substr($word, 3);
            if (str_starts_with($sub, 'p') || str_starts_with($sub, 'b')) {
                return (strlen($sub) >= 3) ? $sub : $word;
            }
            $stem = 'p' . $sub;
            return (strlen($stem) >= 3) ? $stem : $word;
        }
        if (str_starts_with($word, 'men')) {
            $sub = substr($word, 3);
            if (str_starts_with($sub, 'd') || str_starts_with($sub, 't')) {
                return (strlen($sub) >= 3) ? $sub : $word;
            }
            $stem = 't' . $sub;
            return (strlen($stem) >= 3) ? $stem : $word;
        }
        if (str_starts_with($word, 'me')) {
            $stem = substr($word, 2);
            return (strlen($stem) >= 3) ? $stem : $word;
        }

        // Awalan di-, ke-, se-, ter-, ber-, per-
        foreach (['di', 'ke', 'se', 'ter', 'ber', 'per'] as $pre) {
            if (str_starts_with($word, $pre)) {
                $stem = substr($word, strlen($pre));
                if (strlen($stem) >= 3) {
                    return $stem;
                }
            }
        }

        return $word;
    }
}
