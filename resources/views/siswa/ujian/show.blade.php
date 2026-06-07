@extends('layouts.exam')

@section('content')
    @if ($ujian->status === 'ongoing')
        <!-- Topbar Ujian -->
        <header class="h-14 bg-primary flex items-center justify-between px-6 shrink-0 z-30">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('assets/img/logoSIBUO.png') }}" alt="SIBUO" class="w-6 h-6 object-contain">
                </div>
                <div>
                    <h1 class="text-white text-sm font-medium leading-tight">{{ $ujian->bankSoal->nama_bank }}</h1>
                    <p class="text-white/40 text-[10px] uppercase tracking-wider">{{ Auth::user()->name }} • Kelas {{ Auth::user()->kelas->nama_kelas ?? '-' }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="flex flex-col items-end">
                    <p class="text-white/40 text-[10px] uppercase tracking-widest font-semibold">Sisa Waktu</p>
                    <p id="timer" class="text-xl font-medium text-[#34D399] font-tabular leading-none mt-1">00:00:00</p>
                </div>
            </div>
        </header>

        <!-- Security Bar -->
        <div class="bg-[#FEF3C7] border-b border-yellow-200 px-6 py-2 flex items-center gap-2 z-20 shrink-0">
            <svg class="w-4 h-4 text-[#92400E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v3m0-3h3m-3 0H9m12-3a9 9 0 11-18 0 9 9 0 0118 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <p class="text-[11px] text-[#92400E] font-medium">Sesi ujian aktif — jangan berpindah tab atau halaman. Pelanggaran dicatat otomatis.</p>
        </div>

        <div class="flex-1 flex overflow-hidden">
            <!-- Main Content: Soal -->
            <main class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-white">
                <form id="exam-form" action="{{ route('siswa.ujian.submit', $ujian) }}" method="POST">
                    @csrf
                    <div id="questions-container">
                        @foreach ($ujian->bankSoal->soal as $index => $soal)
                            <div class="question-item hidden" data-index="{{ $index }}" data-soal-id="{{ $soal->id }}">
                                <div class="flex items-start gap-5 mb-8">
                                    <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-medium shrink-0 shadow-lg shadow-emerald-900/10">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="prose prose-sm max-w-none text-gray-800 leading-relaxed text-sm">
                                            {!! nl2br(e($soal->pertanyaan)) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-3">
                                    @php
                                        $options = [
                                            'a' => $soal->pilihan_a,
                                            'b' => $soal->pilihan_b,
                                            'c' => $soal->pilihan_c,
                                            'd' => $soal->pilihan_d,
                                        ];
                                    @endphp

                                    @foreach ($options as $key => $option)
                                        <label class="group relative flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-emerald-400 hover:bg-emerald-50/30 transition-all active:scale-[0.99]">
                                            <input type="radio" name="answers[{{ $soal->id }}]" value="{{ $key }}" 
                                                class="hidden option-input" 
                                                data-soal-id="{{ $soal->id }}" 
                                                {{ (isset($answerMap[$soal->id]) && $answerMap[$soal->id] === $key) ? 'checked' : '' }}>
                                            
                                            <div class="w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center mr-4 group-hover:border-emerald-500 transition-colors radio-circle">
                                                <div class="w-3 h-3 bg-emerald-600 rounded-full opacity-0 radio-dot transition-opacity"></div>
                                            </div>
                                            
                                            <div class="flex-1">
                                                <span class="text-xs font-bold text-gray-400 mr-2">{{ strtoupper($key) }}.</span>
                                                <span class="text-sm text-gray-700">{{ $option }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </main>

            <!-- Sidebar: Navigasi Soal -->
            <aside class="w-72 bg-gray-50 border-l border-gray-200 flex flex-col shrink-0 overflow-hidden">
                <div class="p-5 border-b border-gray-200 bg-white">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Navigasi Soal</h3>
                    
                    <div class="grid grid-cols-5 gap-2" id="navigator-grid">
                        @foreach ($ujian->bankSoal->soal as $index => $soal)
                            <button type="button" 
                                class="nav-btn w-full aspect-square rounded-lg border flex items-center justify-center text-xs font-medium transition-all"
                                data-index="{{ $index }}"
                                data-soal-id="{{ $soal->id }}">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="p-5 flex-1 overflow-y-auto custom-scrollbar">
                    <div class="space-y-4 mb-8">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Keterangan</h4>
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-md border-2 border-gray-200"></div>
                                <span class="text-[10px] text-gray-500">Belum dijawab</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-md bg-[#ECFDF5] border-2 border-emerald-500"></div>
                                <span class="text-[10px] text-gray-500">Sudah dijawab</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-md bg-primary border-2 border-primary"></div>
                                <span class="text-[10px] text-gray-500">Sedang dikerjakan</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-md bg-[#FFFBEB] border-2 border-yellow-400"></div>
                                <span class="text-[10px] text-gray-500">Ditandai ragu</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-white border border-gray-200 rounded-xl">
                        <p class="text-[10px] text-gray-400 font-medium mb-2 uppercase tracking-wide">Progress Jawaban</p>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mb-2 overflow-hidden">
                            <div id="progress-bar" class="bg-emerald-500 h-1.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                        <p class="text-[10px] font-medium text-gray-600" id="progress-text">0/{{ $ujian->bankSoal->soal->count() }} soal dijawab</p>
                    </div>
                </div>

                <div class="p-5 border-t border-gray-200 bg-white space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <button type="button" id="prev-btn" class="flex-1 px-4 py-2 border border-gray-200 text-gray-600 text-[10px] font-bold rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-30">
                            PREV
                        </button>
                        <button type="button" id="flag-btn" class="px-4 py-2 bg-yellow-50 border border-yellow-200 text-yellow-700 text-[10px] font-bold rounded-lg hover:bg-yellow-100 transition-colors">
                            ⚑ RAGU
                        </button>
                        <button type="button" id="next-btn" class="flex-1 px-4 py-2 border border-gray-200 text-gray-600 text-[10px] font-bold rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-30">
                            NEXT
                        </button>
                    </div>
                    <button type="button" id="finish-btn" class="w-full py-3 bg-primary text-white text-[11px] font-bold rounded-xl hover:bg-primary-dark transition-colors shadow-lg shadow-emerald-900/10">
                        KUMPULKAN UJIAN
                    </button>
                </div>
            </aside>
        </div>

        <!-- Modals -->
        <x-confirm-modal id="submit-confirm" title="Kumpulkan Jawaban?" message="Pastikan semua soal telah dijawab dengan benar. Setelah dikumpulkan, Anda tidak dapat mengubah jawaban lagi." type="info">
            <x-slot name="footer">
                <button type="button" id="confirm-submit-btn" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary text-sm font-medium text-white hover:bg-primary-dark focus:outline-none sm:ml-3 sm:w-auto">
                    Ya, Kumpulkan
                </button>
                <button type="button" @click="$dispatch('close-submit-confirm')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto">
                    Batal
                </button>
            </x-slot>
        </x-confirm-modal>

        <!-- Block Modal for Violations -->
        <div id="block-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-red-900/90 backdrop-blur-md"></div>
            <div class="bg-white rounded-2xl p-8 z-10 max-w-md w-full text-center shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-red-600"></div>
                <div class="mb-6">
                    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Pelanggaran Terdeteksi</h3>
                    <p class="text-xs text-gray-500 mt-2">Anda terdeteksi meninggalkan halaman ujian atau berpindah aplikasi. Masukkan kode ujian untuk melanjutkan.</p>
                </div>
                
                <div class="space-y-4">
                    <input type="text" id="unlock-code" placeholder="KODE-XXX" 
                        class="w-full h-12 text-center text-xl font-mono border-2 border-gray-100 rounded-xl focus:border-red-500 focus:outline-none uppercase tracking-widest">
                    <p id="unlock-error" class="text-[10px] text-red-600 font-medium hidden">Kode tidak valid atau sudah kedaluwarsa</p>
                    <button type="button" id="unlock-btn" class="w-full h-12 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-900/10">
                        VERIFIKASI & LANJUTKAN
                    </button>
                </div>
            </div>
        </div>
    @else
        <!-- Kondisi Selesai Ujian -->
        <div class="flex-1 flex items-center justify-center p-6 bg-gray-50">
            <div class="w-full max-w-md bg-white border border-gray-200 rounded-3xl p-10 text-center shadow-sm">
                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-2xl font-medium text-gray-900">Ujian Selesai!</h2>
                <p class="text-sm text-gray-500 mt-2">Terima kasih telah mengerjakan ujian dengan jujur.</p>
                
                <div class="mt-8 p-6 bg-gray-50 rounded-2xl text-left space-y-4 border border-gray-100">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Nama Ujian</p>
                        <p class="text-sm font-medium text-gray-900">{{ $ujian->bankSoal->nama_bank }}</p>
                    </div>
                    <div class="flex justify-between gap-4">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Waktu Selesai</p>
                            <p class="text-xs font-medium text-gray-700">{{ $ujian->waktu_selesai?->format('H:i') }} WIB</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Total Pelanggaran</p>
                            <p class="text-xs font-medium text-gray-700">{{ $ujian->pelanggaran }} Kali</p>
                        </div>
                    </div>
                </div>

                <p class="mt-8 text-xs text-gray-400 leading-relaxed italic">
                    Nilai dan detail jawaban akan tersedia setelah guru mengumumkan hasil pengerjaan.
                </p>

                <a href="{{ route('siswa.ujian.index') }}" class="mt-10 w-full h-12 bg-primary text-white text-sm font-medium rounded-xl hover:bg-primary-dark transition-colors flex items-center justify-center shadow-lg shadow-emerald-900/10">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
@if($ujian->status === 'ongoing')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // State management
    const totalSoal = {{ $ujian->bankSoal->soal->count() }};
    let currentIndex = 0;
    const answerMap = @json($answerMap);
    const markedMap = @json($markedMap);
    
    // Elements
    const questionItems = document.querySelectorAll('.question-item');
    const navButtons = document.querySelectorAll('.nav-btn');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const flagBtn = document.getElementById('flag-btn');
    const timerEl = document.getElementById('timer');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    
    // 1. Navigation Logic
    function showQuestion(index) {
        questionItems.forEach(item => item.classList.add('hidden'));
        questionItems[index].classList.remove('hidden');
        
        currentIndex = index;
        updateNavButtons();
        
        prevBtn.disabled = index === 0;
        nextBtn.disabled = index === totalSoal - 1;
    }

    function updateNavButtons() {
        navButtons.forEach((btn, i) => {
            const soalId = btn.dataset.soalId;
            const isAnswered = answerMap[soalId] != null;
            const isFlagged = markedMap[soalId] == true;
            const isCurrent = i === currentIndex;
            
            // Default classes
            btn.className = 'nav-btn w-full aspect-square rounded-lg border flex items-center justify-center text-xs font-medium transition-all ';
            
            if (isCurrent) {
                btn.classList.add('bg-primary', 'text-white', 'border-primary', 'ring-2', 'ring-offset-1', 'ring-primary');
            } else if (isFlagged) {
                btn.classList.add('bg-[#FFFBEB]', 'text-yellow-800', 'border-yellow-400');
            } else if (isAnswered) {
                btn.classList.add('bg-[#ECFDF5]', 'text-emerald-700', 'border-emerald-500');
            } else {
                btn.classList.add('bg-white', 'text-gray-500', 'border-gray-200', 'hover:border-gray-300');
            }
        });
        
        // Update current question radio UI
        const currentSoalId = questionItems[currentIndex].dataset.soalId;
        const radios = questionItems[currentIndex].querySelectorAll('.option-input');
        radios.forEach(radio => {
            const container = radio.closest('label');
            const circle = container.querySelector('.radio-circle');
            const dot = container.querySelector('.radio-dot');
            
            if (radio.checked) {
                container.classList.add('border-emerald-500', 'bg-[#ECFDF5]');
                circle.classList.add('border-emerald-500');
                dot.classList.remove('opacity-0');
            } else {
                container.classList.remove('border-emerald-500', 'bg-[#ECFDF5]');
                circle.classList.remove('border-emerald-500');
                dot.classList.add('opacity-0');
            }
        });

        // Update progress
        const answeredCount = Object.values(answerMap).filter(v => v != null).length;
        progressBar.style.width = `${(answeredCount / totalSoal) * 100}%`;
        progressText.innerText = `${answeredCount}/${totalSoal} soal dijawab`;
    }

    navButtons.forEach(btn => {
        btn.addEventListener('click', () => showQuestion(parseInt(btn.dataset.index)));
    });

    prevBtn.addEventListener('click', () => { if(currentIndex > 0) showQuestion(currentIndex - 1); });
    nextBtn.addEventListener('click', () => { if(currentIndex < totalSoal - 1) showQuestion(currentIndex + 1); });

    // 2. Timer Countdown
    const expiryMs = {{ $expireAt->timestamp }} * 1000;
    function updateTimer() {
        const now = Date.now();
        const diff = Math.max(0, expiryMs - now);
        
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        
        timerEl.innerText = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        
        if (diff < 600000 && diff > 0) { // < 10 mins
            timerEl.classList.remove('text-[#34D399]');
            timerEl.classList.add('text-red-500', 'animate-pulse');
        }
        
        if (diff === 0) {
            alert('Waktu habis! Jawaban Anda akan otomatis dikirim.');
            document.getElementById('exam-form').submit();
        }
    }
    setInterval(updateTimer, 1000);
    updateTimer();

    // 3. Auto-Save Answer
    document.querySelectorAll('.option-input').forEach(radio => {
        radio.addEventListener('change', function() {
            const soalId = this.dataset.soalId;
            const val = this.value;
            answerMap[soalId] = val;
            saveToServer(soalId, val, markedMap[soalId] || false);
            updateNavButtons();
        });
    });

    flagBtn.addEventListener('click', () => {
        const soalId = questionItems[currentIndex].dataset.soalId;
        markedMap[soalId] = !markedMap[soalId];
        saveToServer(soalId, answerMap[soalId], markedMap[soalId]);
        updateNavButtons();
    });

    function saveToServer(soalId, jawaban, marked) {
        fetch('{{ route("siswa.ujian.answer", $ujian) }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ soal_id: soalId, jawaban: jawaban, marked: marked })
        });
    }

    // 4. Tab Switch Detection
    const blockModal = document.getElementById('block-modal');
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            fetch('{{ route("siswa.ujian.violation", $ujian) }}', { 
                method: 'POST', 
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
            });
            blockModal.classList.remove('hidden');
            blockModal.classList.add('flex');
        }
    });

    document.getElementById('unlock-btn').addEventListener('click', () => {
        const code = document.getElementById('unlock-code').value.toUpperCase();
        fetch('{{ route("siswa.ujian.verify-code", $ujian) }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ kode_ujian: code })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                blockModal.classList.add('hidden');
                blockModal.classList.remove('flex');
                document.getElementById('unlock-code').value = '';
                document.getElementById('unlock-error').classList.add('hidden');
            } else {
                document.getElementById('unlock-error').classList.remove('hidden');
            }
        });
    });

    // 5. Prevent Back & Submit Confirmation
    history.pushState(null, null, location.href);
    window.addEventListener('popstate', () => history.pushState(null, null, location.href));

    document.getElementById('finish-btn').addEventListener('click', () => {
        window.dispatchEvent(new CustomEvent('confirm-submit-confirm'));
    });

    document.getElementById('confirm-submit-btn').addEventListener('click', () => {
        document.getElementById('exam-form').submit();
    });

    // Initialize
    showQuestion(0);
});
</script>
@endif
@endpush
