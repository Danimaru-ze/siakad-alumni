<x-app-layout>
    <x-slot name="header">
        Daftar Lowongan Kerja
    </x-slot>

    <!-- Header & Filter -->
    <div class="card-saas p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Temukan Peluang Karir Anda
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Jelajahi lowongan kerja yang tersedia dari mitra perusahaan kami.
                </p>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('alumni.jobs.index') }}" class="mt-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Posisi / Perusahaan</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="input-saas mt-1 block w-full" placeholder="Cari...">
                </div>
                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" value="{{ request('lokasi') }}" class="input-saas mt-1 block w-full" placeholder="cth: Jakarta">
                </div>
                <div>
                    <label for="gaji_min" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gaji Min.</label>
                    <input type="number" name="gaji_min" id="gaji_min" value="{{ request('gaji_min') }}" class="input-saas mt-1 block w-full" placeholder="cth: 5000000">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg w-full">Filter</button>
                    <a href="{{ route('alumni.jobs.index') }}" class="btn-ghost-saas px-4 py-2 rounded-lg">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Job Listings -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($jobs as $job)
            <div class="card-saas flex flex-col">
                <div class="p-6 flex-grow">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $job->judul }}</h3>
                    <p class="mt-1 text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ $job->company->nama_perusahaan }}</p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $job->lokasi }}
                        </span>
                    </p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                         <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ $job->tipe_pekerjaan }}
                        </span>
                    </p>
                     <p class="mt-4 text-xs text-gray-500 dark:text-gray-400 line-clamp-3">
                        {{ $job->deskripsi }}
                    </p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-b-lg">
                    <a href="{{ route('alumni.jobs.show', $job) }}" class="block w-full text-center btn-primary-saas px-4 py-2 rounded-lg text-sm">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="md:col-span-2 lg:col-span-3 text-center py-12">
                 <p class="text-gray-500 dark:text-gray-400">Tidak ada lowongan kerja yang cocok dengan filter Anda.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $jobs->links() }}
    </div>
</x-app-layout>
