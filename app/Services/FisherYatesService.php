<?php

namespace App\Services;

class FisherYatesService
{
    /**
     * Mengacak array menggunakan Algoritma Fisher-Yates Shuffle murni dengan kompleksitas O(n).
     *
     * @param array $items Array data yang akan diacak
     * @param int|null $seed Seed untuk menjamin konsistensi keacakan per sesi siswa
     * @return array
     */
    public static function shuffle(array $items, ?int $seed = null): array
    {
        $count = count($items);
        if ($count <= 1) {
            return $items;
        }

        // Jika seed disediakan, gunakan mt_srand agar urutan stabil selama sesi ujian siswa
        if ($seed !== null) {
            mt_srand($seed);
        }

        // Salin nilai agar array berindeks numerik 0 .. n-1
        $list = array_values($items);

        // Algoritma Fisher-Yates (Knuth) mundur dari indeks terakhir
        for ($i = $count - 1; $i > 0; $i--) {
            // Pilih indeks acak j di mana 0 <= j <= i
            $j = mt_rand(0, $i);

            // Tukar elemen pada indeks i dengan elemen pada indeks j
            $temp = $list[$i];
            $list[$i] = $list[$j];
            $list[$j] = $temp;
        }

        // Kembalikan seed ke random global
        if ($seed !== null) {
            mt_srand();
        }

        return $list;
    }

    /**
     * Mengacak pilihan jawaban ganda (pilihan_a s.d. pilihan_e) untuk butir soal PG
     * sambil tetap mempertahankan pemetaan jawaban benar siswa secara transparan.
     *
     * @param object $soal Model Soal
     * @param int $seed Seed unik kombinasi id ujian dan id soal
     * @return array ['options' => array, 'key_map' => array]
     */
    public static function shuffleOptions($soal, int $seed): array
    {
        $rawOptions = [];
        foreach (['a', 'b', 'c', 'd', 'e'] as $opt) {
            $field = 'pilihan_' . $opt;
            if (!empty($soal->$field)) {
                $rawOptions[$opt] = $soal->$field;
            }
        }

        if (count($rawOptions) <= 1) {
            return [
                'options' => $rawOptions,
                'reverse_map' => array_combine(array_keys($rawOptions), array_keys($rawOptions))
            ];
        }

        $keys = array_keys($rawOptions);
        $shuffledKeys = self::shuffle($keys, $seed);

        $shuffledOptions = [];
        $reverseMap = []; // Memetakan label baru (A, B, C...) kembali ke opsi asal
        $labels = ['a', 'b', 'c', 'd', 'e'];

        foreach ($shuffledKeys as $newIdx => $origKey) {
            $newLabel = $labels[$newIdx];
            $shuffledOptions[$newLabel] = $rawOptions[$origKey];
            $reverseMap[$newLabel] = $origKey;
        }

        return [
            'options' => $shuffledOptions,
            'reverse_map' => $reverseMap
        ];
    }
}
