<x-app-layout>
    <x-slot name="header">
        Kurikulum & Mata Kuliah
    </x-slot>

    <div class="mb-6">
        <h2 class="text-xl font-bold text-siakad-dark dark:text-white">Daftar Mata Kuliah - Prodi {{ $dosen->prodi->nama ?? '-' }}</h2>
        <p class="text-siakad-secondary dark:text-gray-400">Kelola mata kuliah dan kurikulum program studi.</p>
    </div>

    <div class="card-saas overflow-hidden dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full table-saas">
                <thead>
                    <tr class="bg-siakad-light/30 dark:bg-gray-900 text-siakad-secondary dark:text-gray-400 text-xs uppercase tracking-wider font-semibold">
                        <th class="px-6 py-3 text-left">Kode</th>
                        <th class="px-6 py-3 text-left">Nama Mata Kuliah</th>
                        <th class="px-6 py-3 text-center">SKS</th>
                        <th class="px-6 py-3 text-center">Semester</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-siakad-light dark:divide-gray-700">
                    @forelse($mataKuliah as $mk)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-siakad-secondary dark:text-gray-400">{{ $mk->kode_mk }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-siakad-dark dark:text-white">{{ $mk->nama_mk }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">{{ $mk->sks }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">{{ $mk->semester }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="#" class="text-siakad-primary hover:text-siakad-dark">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-siakad-secondary dark:text-gray-400">
                            Belum ada data mata kuliah.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
