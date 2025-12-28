<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tracer.index') }}" class="text-siakad-secondary hover:text-siakad-primary">Tracer Study</a>
            <svg class="h-4 w-4 text-siakad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            Edit Studi
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.tracer.update', $tracer) }}" method="POST" x-data="tracerEditor({ questions: {{ $tracer->questions->toJson() }}, tracer: {{ $tracer->toJson() }} })">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <p class="font-bold">Harap perbaiki kesalahan berikut:</p>
                    <ul class="list-disc pl-5 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Study Details Card --}}
            <div class="card-saas mb-6">
                 <div class="px-6 py-4 border-b border-siakad-light">
                    <h3 class="font-semibold text-siakad-dark">Detail Studi</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="nama" class="block text-sm font-medium text-siakad-dark mb-2">Nama Studi</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $tracer->nama) }}" class="input-saas w-full @error('nama') border-red-500 @enderror" required>
                    </div>
                     <div class="md:col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-siakad-dark mb-2">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="input-saas w-full @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $tracer->deskripsi) }}</textarea>
                    </div>
                    <div>
                        <label for="target_angkatan" class="block text-sm font-medium text-siakad-dark mb-2">Target Angkatan Lulus</label>
                        <div class="flex items-center gap-4">
                            <input type="number" name="target_angkatan" id="target_angkatan" x-bind:disabled="targetSemuaAngkatan" value="{{ old('target_angkatan', $tracer->target_angkatan) }}" class="input-saas w-full @error('target_angkatan') border-red-500 @enderror" required>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="target_semua_angkatan" id="target_semua_angkatan" x-model="targetSemuaAngkatan" class="rounded border-siakad-light text-siakad-primary focus:ring-siakad-primary">
                                <label for="target_semua_angkatan" class="text-sm text-siakad-dark">Semua</label>
                            </div>
                        </div>
                        @error('target_angkatan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div></div>
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-siakad-dark mb-2">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $tracer->tanggal_mulai->format('Y-m-d')) }}" class="input-saas w-full @error('tanggal_mulai') border-red-500 @enderror" required>
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-siakad-dark mb-2">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $tracer->tanggal_selesai->format('Y-m-d')) }}" class="input-saas w-full @error('tanggal_selesai') border-red-500 @enderror" required>
                    </div>
                </div>
            </div>
            
            {{-- Question Editor --}}
            <div class="card-saas mb-6">
                <div class="px-6 py-4 border-b border-siakad-light">
                    <h3 class="font-semibold text-siakad-dark">Pertanyaan Kuesioner</h3>
                </div>
                 <div class="p-6">
                    <div class="space-y-4 mb-6">
                        <template x-for="(question, index) in questions" :key="question.id || question.tempId">
                            <div class="card-saas p-4 relative border-l-4 border-siakad-primary">
                                <input type="hidden" :name="`questions[${index}][id]`" x-model="question.id">
                                
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-sm font-medium text-siakad-secondary">Pertanyaan #<span x-text="index + 1"></span></span>
                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="duplicateQuestion(index)" class="p-1 text-siakad-secondary hover:text-siakad-primary transition" title="Duplikasi">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h6a2 2 0 012 2v10a2 2 0 01-2 2h-6a2 2 0 01-2-2v-2m0-4.578h3.375c.39 0 .75-.144 1.05-.405L18 9m-8 7.5h.01"></path></svg>
                                        </button>
                                        <button type="button" @click="removeQuestion(index)" class="p-1 text-siakad-secondary hover:text-red-600 transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label :for="`pertanyaan_${question.tempId}`" class="sr-only">Teks Pertanyaan</label>
                                        <textarea :name="`questions[${index}][pertanyaan]`" :id="`pertanyaan_${question.tempId}`" x-model="question.pertanyaan" rows="2" class="input-saas w-full" required placeholder="Tulis pertanyaan Anda di sini..."></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label :for="`tipe_${question.tempId}`" class="block text-sm font-medium text-siakad-dark mb-2">Tipe Jawaban</label>
                                            <select :name="`questions[${index}][tipe]`" :id="`tipe_${question.tempId}`" x-model="question.tipe" class="input-saas w-full">
                                                <option value="short_text">Jawaban Singkat</option>
                                                <option value="long_text">Paragraf</option>
                                                <option value="radio">Pilihan Ganda</option>
                                                <option value="checkbox">Kotak Centang</option>
                                                <option value="dropdown">Dropdown</option>
                                            </select>
                                        </div>
                                        <div x-show="['radio', 'checkbox', 'dropdown'].includes(question.tipe)" x-transition>
                                            <label :for="`pilihan_${question.tempId}`" class="block text-sm font-medium text-siakad-dark mb-2">Pilihan Jawaban</label>
                                            <textarea :name="`questions[${index}][pilihan]`" :id="`pilihan_${question.tempId}`" x-model="question.pilihan" rows="3" class="input-saas w-full" placeholder="Satu pilihan per baris..."></textarea>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 mt-4">
                                        <input type="hidden" :name="`questions[${index}][wajib_diisi]`" value="0">
                                        <input type="checkbox" :name="`questions[${index}][wajib_diisi]`" :id="`wajib_diisi_${question.tempId}`" value="1" x-model="question.wajib_diisi" class="rounded border-siakad-light text-siakad-primary focus:ring-siakad-primary">
                                        <label :for="`wajib_diisi_${question.tempId}`" class="text-sm text-siakad-dark">Wajib diisi</label>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="addQuestion" class="btn-ghost-saas px-5 py-2.5 rounded-lg text-sm font-medium w-full flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Pertanyaan
                    </button>
                 </div>
            </div>

            {{-- Targeting Card --}}
            <div class="card-saas">
                <div class="px-6 py-4 border-b border-siakad-light">
                    <h3 class="font-semibold text-siakad-dark">Target Responden</h3>
                    <p class="text-xs text-siakad-secondary mt-1">Pilih siapa yang akan menerima kuesioner ini.</p>
                </div>
                <div class="p-6 space-y-4">
                    @if (Auth::user()->isSuperAdmin())
                        <div class="grid grid-cols-3 gap-4 @error('target_type') border border-red-500 p-2 rounded-lg @enderror">
                             <label class="p-4 border rounded-lg flex items-center gap-3 cursor-pointer" :class="targetType === 'all' ? 'border-siakad-primary bg-siakad-primary/5' : 'border-siakad-light'">
                                <input type="radio" name="target_type" value="all" x-model="targetType">
                                <span>Semua Alumni</span>
                            </label>
                            <label class="p-4 border rounded-lg flex items-center gap-3 cursor-pointer" :class="targetType === 'fakultas' ? 'border-siakad-primary bg-siakad-primary/5' : 'border-siakad-light'">
                                <input type="radio" name="target_type" value="fakultas" x-model="targetType">
                                <span>Per Fakultas</span>
                            </label>
                            <label class="p-4 border rounded-lg flex items-center gap-3 cursor-pointer" :class="targetType === 'prodi' ? 'border-siakad-primary bg-siakad-primary/5' : 'border-siakad-light'">
                                <input type="radio" name="target_type" value="prodi" x-model="targetType">
                                <span>Per Program Studi</span>
                            </label>
                        </div>
                        @error('target_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        
                        <div x-show="targetType === 'fakultas'" x-transition>
                            <label for="fakultas_id" class="block text-sm font-medium text-siakad-dark mb-2">Pilih Fakultas</label>
                            <select name="fakultas_id" id="fakultas_id" class="input-saas w-full @error('fakultas_id') border-red-500 @enderror" x-model="selectedFakultas">
                                <option value="">Pilih Fakultas...</option>
                                @foreach ($fakultas as $f)
                                    <option value="{{ $f->id }}">{{ $f->nama }}</option>
                                @endforeach
                            </select>
                            @error('fakultas_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div x-show="targetType === 'prodi'" x-transition>
                            <label for="prodi_ids" class="block text-sm font-medium text-siakad-dark mb-2">Pilih Program Studi</label>
                            <select name="prodi_ids[]" id="prodi_ids" class="input-saas w-full @error('prodi_ids') border-red-500 @enderror" multiple size="8" x-model="selectedProdi">
                                <template x-for="f in {{ $fakultas->toJson() }}" :key="f.id">
                                    <optgroup :label="f.nama">
                                        <template x-for="p in allProdi.filter(ap => ap.fakultas_id == f.id)" :key="p.id">
                                            <option :value="p.id" x-text="p.nama"></option>
                                        </template>
                                    </optgroup>
                                </template>
                            </select>
                            <p class="text-xs text-siakad-secondary mt-1">Gunakan CTRL/CMD + Klik untuk memilih lebih dari satu.</p>
                            @error('prodi_ids')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    @else
                        {{-- Admin Fakultas --}}
                        <input type="hidden" name="fakultas_id" value="{{ Auth::user()->fakultas_id }}">
                        <div class="grid grid-cols-2 gap-4 @error('target_type') border border-red-500 p-2 rounded-lg @enderror">
                             <label class="p-4 border rounded-lg flex items-center gap-3 cursor-pointer" :class="targetType === 'faculty_all' ? 'border-siakad-primary bg-siakad-primary/5' : 'border-siakad-light'">
                                <input type="radio" name="target_type" value="faculty_all" x-model="targetType">
                                <span>Semua Prodi di Fakultas Anda</span>
                            </label>
                            <label class="p-4 border rounded-lg flex items-center gap-3 cursor-pointer" :class="targetType === 'prodi' ? 'border-siakad-primary bg-siakad-primary/5' : 'border-siakad-light'">
                                <input type="radio" name="target_type" value="prodi" x-model="targetType">
                                <span>Pilih Program Studi</span>
                            </label>
                        </div>
                        @error('target_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <div x-show="targetType === 'prodi'" x-transition>
                             <label for="prodi_ids_fakultas" class="block text-sm font-medium text-siakad-dark mb-2">Pilih Program Studi</label>
                            <select name="prodi_ids[]" id="prodi_ids_fakultas" class="input-saas w-full @error('prodi_ids') border-red-500 @enderror" multiple size="8" x-model="selectedProdi">
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-siakad-secondary mt-1">Gunakan CTRL/CMD + Klik untuk memilih lebih dari satu.</p>
                            @error('prodi_ids')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.tracer.index') }}" class="btn-ghost-saas px-5 py-2.5 rounded-lg text-sm font-medium">Batal</a>
                <button type="submit" class="btn-primary-saas px-5 py-2.5 rounded-lg text-sm font-medium">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tracerEditor', (initialData) => ({
                questions: [],
                tempIdCounter: 0,
                targetSemuaAngkatan: {{ old('target_semua_angkatan', $tracer->target_angkatan === null) ? 'true' : 'false' }},
                
                // Targeting state
                targetType: 'all',
                allProdi: {!! $allProdi->toJson() !!},
                selectedFakultas: '',
                selectedProdi: [],

                // Function to determine initial target type
                getInitialTargetType(tracer, isSuperAdmin) {
                    if (isSuperAdmin) {
                        if (tracer.fakultas_id && (!tracer.target_prodi_ids || tracer.target_prodi_ids.length === 0)) {
                             // This logic might need adjustment. Assuming if fakultas_id is set, it's a 'fakultas' target.
                             // A better way would be to store the target_type in the database.
                             const prodiInFakultas = {!! $fakultas->find($tracer->fakultas_id)?->prodi->pluck('id')->toJson() !!} || [];
                             const targetProdi = tracer.target_prodi_ids || [];
                             return JSON.stringify(prodiInFakultas.sort()) === JSON.stringify(targetProdi.sort()) ? 'fakultas' : 'prodi';
                        }
                        if (tracer.target_prodi_ids && tracer.target_prodi_ids.length > 0) return 'prodi';
                        return 'all';
                    } else { // Faculty Admin
                        if (!tracer.target_prodi_ids || tracer.target_prodi_ids.length === 0) return 'faculty_all';
                        const prodiInFakultas = {!! $prodi->pluck('id')->toJson() !!} || [];
                        return prodiInFakultas.length === tracer.target_prodi_ids.length ? 'faculty_all' : 'prodi';
                    }
                },

                init() {
                    const isSuperAdmin = {{ Auth::user()->isSuperAdmin() ? 'true' : 'false' }};
                    const tracer = initialData.tracer || {};
                    const oldTargetType = '{{ old('target_type') }}';

                    // Initialize targeting state
                    this.targetType = oldTargetType || this.getInitialTargetType(tracer, isSuperAdmin);
                    this.selectedFakultas = '{{ old('fakultas_id', $tracer->fakultas_id) }}';
                    this.selectedProdi = {!! json_encode(old('prodi_ids', $tracer->target_prodi_ids ?? [])) !!}.map(String);


                    // Init questions
                    let oldQuestions = {!! json_encode(old('questions')) !!};
                    let questionsToLoad = oldQuestions && oldQuestions.length > 0 ? oldQuestions : (initialData.questions || []);
                    
                    this.questions = questionsToLoad.map(q => ({
                        ...q, 
                        pilihan: Array.isArray(q.pilihan) ? q.pilihan.join('\n') : (q.pilihan || ''),
                        wajib_diisi: q.wajib_diisi == 1,
                        tempId: this.tempIdCounter++
                    }));
                    if (this.questions.length === 0) this.addQuestion();

                    // Watch for targetType changes to reset selections
                    this.$watch('targetType', (newValue) => {
                        if (newValue !== 'fakultas') this.selectedFakultas = '';
                        if (newValue !== 'prodi') this.selectedProdi = [];
                    });
                },

                addQuestion() {
                    this.tempIdCounter++;
                    this.questions.push({
                        id: null,
                        tempId: this.tempIdCounter,
                        pertanyaan: '',
                        tipe: 'short_text',
                        pilihan: '',
                        wajib_diisi: true,
                    });
                },

                removeQuestion(index) {
                    if (confirm('Anda yakin ingin menghapus pertanyaan ini?')) {
                        this.questions.splice(index, 1);
                    }
                },

                duplicateQuestion(index) {
                    this.tempIdCounter++;
                    const original = this.questions[index];
                    const duplicated = { ...original, id: null, tempId: this.tempIdCounter };
                    this.questions.splice(index + 1, 0, duplicated);
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>