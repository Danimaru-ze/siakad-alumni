<x-app-layout>
    <x-slot name="header">
        Referensi Lowongan Kerja
    </x-slot>

    <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="max-w-full">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Daftar Lowongan Kerja Terpublikasi
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Lowongan kerja yang dapat Anda rekomendasikan kepada mahasiswa.
            </p>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul Lowongan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Perusahaan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipe</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Publikasi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($jobs as $job)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $job->judul }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $job->lokasi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $job->company->nama_perusahaan }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $job->tipe_pekerjaan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $job->approved_at ? $job->approved_at->format('d M Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                    Saat ini tidak ada lowongan kerja yang dipublikasikan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="mt-4">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
