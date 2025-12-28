<x-app-layout>
    <x-slot name="header">
        Detail Lowongan Kerja
    </x-slot>

    <div class="card-saas">
        <div class="p-6 md:p-8">
            <!-- Job Header -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ $job->judul }}</h1>
                <p class="mt-2 text-lg text-indigo-600 dark:text-indigo-400">{{ $job->company->nama_perusahaan }}</p>
                <div class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-gray-500 dark:text-gray-400">
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $job->lokasi }}
                    </span>
                    <span class="inline-flex items-center gap-2">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        {{ $job->tipe_pekerjaan }}
                    </span>
                     @if($job->kisaran_gaji_min && $job->kisaran_gaji_max)
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2m4 6h.01M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Rp {{ number_format($job->kisaran_gaji_min, 0, ',', '.') }} - Rp {{ number_format($job->kisaran_gaji_max, 0, ',', '.') }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Job Body -->
            <div class="prose prose-lg dark:prose-invert max-w-none">
                
                <h3 class="font-semibold text-gray-800 dark:text-white">Deskripsi Pekerjaan</h3>
                <p>{{ $job->deskripsi }}</p>

                <h3 class="font-semibold text-gray-800 dark:text-white mt-8">Persyaratan</h3>
                <p>{{ $job->persyaratan }}</p>
                
            </div>
        </div>

        <!-- Job Footer / Apply Button -->
        <div class="p-6 bg-gray-50 dark:bg-gray-800/50 rounded-b-lg flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-300">Diposting pada: <span class="font-medium">{{ $job->created_at->format('d F Y') }}</span></p>
                @if($job->deadline_lamaran)
                <p class="text-sm text-red-600 dark:text-red-400">Deadline: <span class="font-medium">{{ \Carbon\Carbon::parse($job->deadline_lamaran)->format('d F Y') }}</span></p>
                @endif
            </div>
            <a href="mailto:{{ $job->hrd->email }}?subject=Lamaran%20Pekerjaan:%20{{ urlencode($job->judul) }}" class="btn-primary-saas px-8 py-3 rounded-lg text-base font-semibold w-full md:w-auto text-center">
                Lamar Sekarang
            </a>
        </div>
    </div>

</x-app-layout>
