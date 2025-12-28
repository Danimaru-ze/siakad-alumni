<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('alumni.tracer.index') }}" class="text-siakad-secondary hover:text-siakad-primary">Tracer Study</a>
            <svg class="h-4 w-4 text-siakad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            {{ $tracer->nama }}
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('alumni.tracer.store.submit', $tracer) }}" method="POST">
            @csrf

            <div class="card-saas">
                {{-- Card Header --}}
                <div class="p-6 border-b border-siakad-light">
                    <h1 class="text-xl font-semibold text-siakad-dark">{{ $tracer->nama }}</h1>
                    <p class="mt-1 text-sm text-siakad-secondary">{{ $tracer->deskripsi }}</p>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <p class="font-bold">Harap perbaiki beberapa kesalahan di bawah ini:</p>
                            <ul class="list-disc pl-5 mt-2 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-8">
                        @foreach ($tracer->questions as $index => $question)
                            <div class="border-t border-siakad-light pt-6 first:border-t-0 first:pt-0">
                                <label class="block text-base font-semibold text-siakad-dark">
                                    {{ $loop->iteration }}. {{ $question->pertanyaan }}
                                    @if ($question->wajib_diisi) <span class="text-red-600 font-normal">*</span> @endif
                                </label>

                                <div class="mt-4 space-y-3">
                                    @if ($question->tipe == 'short_text')
                                        <input type="text" name="answers[{{ $question->id }}]" class="input-saas w-full max-w-lg" value="{{ old('answers.' . $question->id) }}">
                                    
                                    @elseif ($question->tipe == 'long_text')
                                        <textarea name="answers[{{ $question->id }}]" rows="4" class="input-saas w-full">{{ old('answers.' . $question->id) }}</textarea>
                                    
                                    @elseif ($question->tipe == 'radio')
                                        @foreach ($question->pilihan as $option)
                                            <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-siakad-primary/5 cursor-pointer transition-colors duration-200">
                                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" class="text-siakad-primary focus:ring-siakad-primary focus:ring-offset-0" {{ old('answers.' . $question->id) == $option ? 'checked' : '' }}>
                                                <span class="text-sm">{{ $option }}</span>
                                            </label>
                                        @endforeach

                                    @elseif ($question->tipe == 'checkbox')
                                        @foreach ($question->pilihan as $option)
                                            <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-siakad-primary/5 cursor-pointer transition-colors duration-200">
                                                <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option }}" class="rounded text-siakad-primary focus:ring-siakad-primary focus:ring-offset-0" {{ in_array($option, old('answers.' . $question->id, [])) ? 'checked' : '' }}>
                                                <span class="text-sm">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    
                                    @elseif ($question->tipe == 'dropdown')
                                        <select name="answers[{{ $question->id }}]" class="input-saas w-full max-w-lg">
                                            <option value="">Pilih salah satu...</option>
                                            @foreach($question->pilihan as $option)
                                                <option value="{{ $option }}" {{ old('answers.' . $question->id) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                    @error('answers.' . $question->id)
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Card Footer --}}
                <div class="p-6 bg-siakad-light/20 border-t border-siakad-light rounded-b-lg">
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-lg text-sm font-semibold">
                            Kirim Jawaban
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
