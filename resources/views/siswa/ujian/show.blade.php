<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Ujian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ujian->bankSoal->nama_bank }}</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Mata Pelajaran: {{ $ujian->bankSoal->mataPelajaran->nama }}</p>
                            <p class="text-gray-600 dark:text-gray-400">Guru: {{ $ujian->bankSoal->guru->name }}</p>
                            <p class="text-gray-600 dark:text-gray-400">Kode Ujian: {{ $ujian->kode_ujian }}</p>
                            <p class="text-gray-600 dark:text-gray-400">Status: {{ ucfirst($ujian->status) }}</p>
                            <p class="text-gray-600 dark:text-gray-400">Durasi: {{ $ujian->bankSoal->durasi }} menit</p>
                        </div>
                        <a href="{{ route('siswa.ujian.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                            ← Kembali
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded text-green-700 dark:text-green-200">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded text-red-700 dark:text-red-200">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($ujian->status === 'ongoing')
                        @php
                            $remaining = $expireAt->diffInSeconds(now());
                        @endphp
                        <div class="flex justify-between mb-6 p-6 rounded-lg bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700">
                            <div class="text-gray-700 dark:text-yellow-100">
                                <p class="font-semibold">Ujian sedang berlangsung.</p>
                                <p>Waktu mulai: {{ $ujian->waktu_mulai->format('d M Y H:i') }}</p>
                                <p>Batas waktu: {{ $expireAt->format('d M Y H:i') }}</p>
                            </div>
                            <div class="text-gray-700 dark:text-yellow-100">
                                <p>Sisa waktu:<br><span style="font-size: 30px;" id="remaining-time">{{ floor($remaining / 60) }} menit {{ $remaining % 60 }} detik</span></p>
                            </div>
                        </div>
                    @elseif ($ujian->status === 'completed' && $ujian->hasilUjian)
                        <div class="mb-6 p-6 rounded-lg bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700">
                            <div class="text-center">
                                <p class="text-xl font-bold text-blue-800 dark:text-blue-200">Ujian Telah Selesai</p>
                                <p class="text-gray-600 dark:text-gray-300 mt-2">Terima kasih telah mengerjakan ujian ini.</p>
                                <p class="text-gray-600 dark:text-gray-300">Selesai pada: {{ $ujian->waktu_selesai?->format('d M Y H:i') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 italic">Nilai dan detail jawaban akan diinformasikan oleh guru mata pelajaran.</p>
                            </div>
                        </div>
                    @endif

                    @if ($ujian->bankSoal->soal->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Belum ada soal di bank soal ini. Silakan hubungi guru Anda.</p>
                    @else
                        @if ($ujian->status === 'ongoing')
                            <form id="exam-form" action="{{ route('siswa.ujian.submit', $ujian) }}" method="POST" onsubmit="return false;">
                                @csrf
                                <div id="questions-container" class="space-y-6">
                                    @foreach ($ujian->bankSoal->soal as $index => $soal)
                                        <div class="question bg-white dark:bg-gray-900 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6" data-index="{{ $index }}" style="display: none;">
                                            <div class="flex justify-between items-start mb-4">
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Soal {{ $index + 1 }} dari {{ $ujian->bankSoal->soal->count() }}</h3>
                                                </div>
                                            </div>

                                            <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $soal->pertanyaan }}</p>

                                            @php
                                                $options = [
                                                    'a' => $soal->pilihan_a,
                                                    'b' => $soal->pilihan_b,
                                                    'c' => $soal->pilihan_c,
                                                    'd' => $soal->pilihan_d,
                                                ];
                                            @endphp

                                            <div class="grid gap-3">
                                                @foreach ($options as $key => $option)
                                                    <label class="cursor-pointer rounded-lg border border-gray-200 dark:border-gray-700 p-3 hover:border-blue-500 dark:hover:border-blue-400">
                                                        <input type="radio" name="answers[{{ $soal->id }}]" value="{{ $key }}" class="mr-2 option-input" data-soal-id="{{ $soal->id }}" {{ isset($answerMap[$soal->id]) && $answerMap[$soal->id] === $key ? 'checked' : '' }}>
                                                        <span class="text-gray-700 dark:text-gray-200"><strong>{{ strtoupper($key) }}.</strong> {{ $option }}</span>
                                                    </label>
                                                @endforeach
                                                @error('answers.' . $soal->id)
                                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="mt-4 flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <label class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                        <input type="checkbox" class="mark-review" data-soal-id="{{ $soal->id }}" {{ isset($markedMap[$soal->id]) && $markedMap[$soal->id] ? 'checked' : '' }}> <span class="ml-2">Tandai untuk review</span>
                                                    </label>
                                                    <input type="hidden" name="marked[{{ $soal->id }}]" value="{{ isset($markedMap[$soal->id]) && $markedMap[$soal->id] ? '1' : '0' }}" class="marked-input" data-soal-id="{{ $soal->id }}">
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">ID Soal: {{ $soal->id }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-6 flex items-center justify-between">
                                    <div>
                                        <button type="button" id="prev-btn" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400" disabled>Prev</button>
                                        <button type="button" id="next-btn" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Next</button>
                                    </div>

                                    <div>
                                        <button type="button" id="finish-btn" class="bg-green-600 text-white px-5 py-3 rounded hover:bg-green-700">Selesaikan Ujian</button>
                                    </div>
                                </div>

                                <!-- Summary modal -->
                                <div id="summary-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
                                    <div class="absolute inset-0 bg-black opacity-50"></div>
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 z-10 max-w-lg w-full">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Ringkasan Sebelum Mengirim</h3>
                                        <div class="mb-4 text-sm text-gray-700 dark:text-gray-300">
                                            <div class="grid grid-cols-[56px_minmax(0,1fr)_84px] gap-4 font-semibold border-b border-gray-200 dark:border-gray-700 px-3 pb-2 mb-2 text-gray-900 dark:text-gray-100 items-center">
                                                <div class="truncate">Nomor</div>
                                                <div class="truncate">Status</div>
                                                <div class="text-right truncate">Aksi</div>
                                            </div>
                                            <div id="summary-list" class="space-y-2 max-h-72 overflow-y-auto"></div>
                                        </div>
                                        <p id="summary-warning" class="text-sm text-red-600 dark:text-red-400 mb-4 hidden">Masih ada soal yang belum dijawab. Lengkapi jawaban terlebih dahulu untuk mengaktifkan tombol kirim.</p>
                                        <div class="flex justify-end gap-3">
                                            <button type="button" id="cancel-submit" class="px-4 py-2 rounded border">Batal</button>
                                            <button type="button" id="confirm-submit" class="px-4 py-2 bg-green-600 text-white rounded disabled:opacity-50 disabled:cursor-not-allowed" disabled>Kirim Jawaban</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Block Modal for Tab Switching -->
                            <div id="block-modal" class="fixed inset-0 z-[100] hidden items-center justify-center">
                                <div class="absolute inset-0 bg-red-900 opacity-95"></div>
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-8 z-10 max-w-lg w-full text-center shadow-2xl">
                                    <div class="mb-4 text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 15c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">AKSES DIBLOKIR!</h3>
                                    <p class="text-gray-700 dark:text-gray-300 mb-6">Anda terdeteksi meninggalkan halaman ujian. Pelanggaran telah dicatat. Silakan masukkan Kode Ujian untuk melanjutkan.</p>
                                    
                                    <div class="mb-4">
                                        <input type="text" id="unlock-code" placeholder="Masukkan Kode Ujian (XXX-XXX)" class="w-full p-3 border rounded text-center font-bold text-xl uppercase dark:bg-gray-700 dark:text-white">
                                        <p id="unlock-error" class="text-red-600 text-sm mt-2 hidden">Kode Ujian salah atau sudah kedaluwarsa!</p>
                                    </div>
                                    
                                    <button type="button" id="unlock-btn" class="w-full bg-red-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-red-700 transition">
                                        BUKA BLOKIR & LANJUTKAN
                                    </button>
                                    
                                    <p class="mt-4 text-xs text-gray-500">Catatan: Setiap pelanggaran terekam di sistem dan dapat mempengaruhi penilaian atau status ujian Anda.</p>
                                </div>
                            </div>
                        @else
                            {{-- When completed, do not reveal answers or scores to students --}}
                            <div class="p-6 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                                <p class="text-gray-700 dark:text-gray-300">Ujian telah selesai pada {{ $ujian->waktu_selesai?->format('d M Y H:i') }}. Nilai dan kunci jawaban tidak ditampilkan untuk peserta.</p>
                                <p class="text-gray-600 dark:text-gray-400 mt-2">Jika Anda membutuhkan klarifikasi mengenai hasil, silakan hubungi guru Anda.</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const remainingTimeElement = document.getElementById('remaining-time');
            if (remainingTimeElement && typeof {{ $expireAt->timestamp ?? 'null' }} === 'number') {
                const expiryMs = {{ ($expireAt->timestamp ?? 0) * 1000 }};
                const updateRemaining = () => {
                    const diffSeconds = Math.max(0, Math.floor((expiryMs - Date.now()) / 1000));
                    const minutes = Math.floor(diffSeconds / 60);
                    const seconds = diffSeconds % 60;
                    remainingTimeElement.textContent = `${minutes} menit ${seconds.toString().padStart(2, '0')} detik`;
                };
                updateRemaining();
                setInterval(updateRemaining, 1000);
            }

            // One-question pagination
            const questions = Array.from(document.querySelectorAll('.question'));
            if (questions.length === 0) return;
            let current = 0;
            const show = (i) => {
                questions.forEach(q=>q.style.display='none');
                questions[i].style.display='block';
                document.getElementById('prev-btn').disabled = i===0;
                document.getElementById('next-btn').disabled = i===questions.length-1;
            };
            show(0);

            document.getElementById('next-btn').addEventListener('click', ()=>{ if(current<questions.length-1){ current++; show(current); } });
            document.getElementById('prev-btn').addEventListener('click', ()=>{ if(current>0){ current--; show(current); } });

            // Mark for review handling
            document.querySelectorAll('.mark-review').forEach(cb=>{
                cb.addEventListener('change', function(){
                    const id = this.getAttribute('data-soal-id');
                    const hidden = document.querySelector('.marked-input[data-soal-id="'+id+'"]');
                    if (hidden) hidden.value = this.checked ? '1' : '0';
                    saveAnswer(id);
                });
            });

            // AJAX Answer Saving
            const saveAnswer = (soalId) => {
                const selectedOption = document.querySelector(`input[name="answers[${soalId}]"]:checked`);
                const markedInput = document.querySelector(`.mark-review[data-soal-id="${soalId}"]`);
                
                const data = {
                    soal_id: soalId,
                    jawaban: selectedOption ? selectedOption.value : null,
                    marked: markedInput ? markedInput.checked : false,
                    _token: '{{ csrf_token() }}'
                };
                
                fetch('{{ route("siswa.ujian.answer", $ujian) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .catch(error => console.error('Error saving answer:', error));
            };

            document.querySelectorAll('.option-input').forEach(input => {
                input.addEventListener('change', function() {
                    saveAnswer(this.getAttribute('data-soal-id'));
                });
            });

            // Session Security: Tab Switch Detection & Re-authentication
            const blockModal = document.getElementById('block-modal');
            const unlockCodeInput = document.getElementById('unlock-code');
            const unlockBtn = document.getElementById('unlock-btn');
            const unlockError = document.getElementById('unlock-error');
            
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    // Record violation to server
                    fetch('{{ route("siswa.ujian.violation", $ujian) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    // Show block modal
                    blockModal.classList.remove('hidden');
                    blockModal.style.display = 'flex';
                }
            });

            unlockBtn.addEventListener('click', () => {
                const code = unlockCodeInput.value.toUpperCase();
                
                unlockBtn.disabled = true;
                unlockBtn.textContent = 'MEMVERIFIKASI...';
                
                fetch('{{ route("siswa.ujian.verify-code", $ujian) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ kode_ujian: code })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        blockModal.classList.add('hidden');
                        blockModal.style.display = 'none';
                        unlockError.classList.add('hidden');
                        unlockCodeInput.value = '';
                    } else {
                        unlockError.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error verifying code:', error);
                    unlockError.textContent = 'Terjadi kesalahan sistem. Silakan coba lagi.';
                    unlockError.classList.remove('hidden');
                })
                .finally(() => {
                    unlockBtn.disabled = false;
                    unlockBtn.textContent = 'BUKA BLOKIR & LANJUTKAN';
                });
            });

            // Prevent Back Button
            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function() {
                window.history.go(1);
            };

            // Finish button -> show summary modal
            const finishBtn = document.getElementById('finish-btn');
            const summaryModal = document.getElementById('summary-modal');
            const summaryList = document.getElementById('summary-list');
            const summaryWarning = document.getElementById('summary-warning');
            const confirmSubmit = document.getElementById('confirm-submit');

            const buildSummary = () => {
                summaryList.innerHTML = '';
                let hasUnanswered = false;

                questions.forEach((q, index) => {
                    const soalId = q.querySelector('.option-input')?.getAttribute('data-soal-id') || index;
                    const selectedOption = q.querySelector(`input[name="answers[${soalId}]"]:checked`);
                    const markedInput = q.querySelector(`.marked-input[data-soal-id="${soalId}"]`);
                    const marked = markedInput && markedInput.value === '1';
                    let status = 'Not Answered';

                    if (selectedOption) {
                        status = marked ? 'Answered & Marked for Review' : 'Answered';
                    } else if (marked) {
                        status = 'Review Later';
                    } else {
                        hasUnanswered = true;
                    }

                    const row = document.createElement('button');
                    row.type = 'button';
                    row.className = 'w-full grid grid-cols-[56px_minmax(0,1fr)_84px] gap-4 items-center rounded-lg px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-800';
                    row.style.gridAutoColumns = '56px minmax(0,1fr) 84px';
                    row.dataset.index = index;
                    row.innerHTML = `
                        <div class="font-semibold text-gray-900 dark:text-gray-100">${index + 1}.</div>
                        <div class="text-sm text-gray-700 dark:text-gray-300 truncate">${status}</div>
                        <div class="text-sm text-indigo-600 dark:text-indigo-400 text-right">Lihat</div>
                    `;
                    row.addEventListener('click', () => {
                        current = index;
                        show(current);
                        summaryModal.classList.add('hidden');
                        summaryModal.style.display = 'none';
                    });

                    summaryList.appendChild(row);
                });

                summaryWarning.classList.toggle('hidden', !hasUnanswered);
                confirmSubmit.disabled = hasUnanswered;
            };

            finishBtn && finishBtn.addEventListener('click', ()=>{
                buildSummary();
                summaryModal.classList.remove('hidden');
                summaryModal.style.display = 'flex';
            });

            document.getElementById('cancel-submit').addEventListener('click', ()=>{
                summaryModal.classList.add('hidden');
                summaryModal.style.display = 'none';
            });

            document.getElementById('confirm-submit').addEventListener('click', ()=>{
                if (!confirmSubmit.disabled) {
                    document.getElementById('exam-form').submit();
                }
            });
        })();
    </script>
</x-app-layout>
