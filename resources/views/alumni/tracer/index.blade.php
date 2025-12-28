<x-app-layout>
    <x-slot name="header">
        Tracer Study
    </x-slot>

    <div>
        <div class="mb-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Daftar Kuesioner Tracer Study
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Partisipasi Anda sangat berharga untuk peningkatan kualitas almamater.
            </p>
        </div>
        
        <div class="space-y-4">
            @forelse ($studies as $study)
                @php
                    $response = $study->responses->first();
                    $isCompleted = $response && $response->status === 'SUDAH_MENGISI';
                @endphp
                <div class="card-saas p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <p class="font-semibold text-siakad-dark dark:text-white">{{ $study->nama }}</p>
                        <p class="text-sm text-siakad-secondary dark:text-gray-400">Periode: {{ $study->tanggal_mulai->format('d M') }} - {{ $study->tanggal_selesai->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-4 flex-shrink-0">
                        @if ($isCompleted)
                            <div class="flex items-center gap-2 text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-sm font-medium">Selesai</span>
                            </div>
                        @else
                             <a href="{{ route('alumni.tracer.show', $study) }}" class="btn-primary-saas px-4 py-2 rounded-lg text-sm">
                                Isi Kuesioner
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="card-saas p-12 text-center">
                    <p class="text-siakad-secondary">Saat ini tidak ada tracer study yang aktif untuk Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
