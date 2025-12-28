<x-app-layout>
    <x-slot name="header">
        Monitoring Lowongan Kerja
    </x-slot>

    <!-- Header & Actions -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Daftar Lowongan Kerja (Read-Only)
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Pantau dan tandai lowongan kerja yang tidak sesuai.
            </p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-saas overflow-hidden dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full table-saas">
                <thead class="bg-siakad-light/30 dark:bg-gray-900 border-b border-siakad-light dark:border-gray-700">
                    <tr>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Judul Lowongan</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Perusahaan</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="text-right py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-siakad-light dark:divide-gray-700">
                    @forelse ($jobs as $job)
                        <tr class="border-b border-siakad-light/50 dark:border-gray-700/50">
                            <td class="py-4 px-5">
                                <div class="font-medium text-siakad-dark dark:text-white">{{ $job->judul }}</div>
                                <div class="text-xs text-siakad-secondary dark:text-gray-400">{{ $job->lokasi }}</div>
                            </td>
                            <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">
                                {{ $job->company->nama_perusahaan }}
                            </td>
                            <td class="py-4 px-5">
                                <x-job-status-badge :status="$job->status" />
                            </td>
                            <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">
                                {{ $job->created_at->format('d M Y') }}
                            </td>
                            <td class="py-4 px-5 text-right">
                                <div x-data="{ flagModal: false }" class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin_fakultas.jobs.show', $job) }}" class="p-2 text-gray-500 hover:text-indigo-600 rounded-lg transition" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <button @click="flagModal = true" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition" title="Flag">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0l-1-3.5L6 9l6 6-4 6.5-5-1.5z"></path></svg>
                                    </button>
                                    
                                    <div x-cloak x-show="flagModal" @keydown.escape.window="flagModal = false" class="fixed inset-0 z-50 overflow-y-auto">
                                        <div class="flex items-center justify-center min-h-screen px-4">
                                            <div @click="flagModal = false" class="fixed inset-0 bg-black/50"></div>
                                            <div @click.away="flagModal = false" class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
                                                <form action="{{ route('admin_fakultas.jobs.flag', $job) }}" method="POST">
                                                    @csrf
                                                    <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-4 text-left">Tandai Lowongan (Flag)</h3>
                                                    <div class="text-left">
                                                        <label for="reason" class="block text-sm font-medium text-siakad-secondary mb-1">Alasan</label>
                                                        <textarea name="reason" id="reason" rows="3" class="input-saas w-full dark:bg-gray-700 dark:border-gray-600" required></textarea>
                                                    </div>
                                                    <div class="flex justify-end gap-3 pt-4">
                                                        <button @click="flagModal = false" type="button" class="px-4 py-2 text-sm text-siakad-secondary hover:bg-gray-100 rounded-lg">Batal</button>
                                                        <button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg text-sm bg-red-600 hover:bg-red-700">Flag</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 bg-siakad-light/50 dark:bg-gray-700/50 rounded-xl flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-siakad-secondary dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <p class="text-siakad-secondary dark:text-gray-400 text-sm">Tidak ada data lowongan kerja.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($jobs->hasPages())
    <div class="card-saas px-5 py-4 mt-4">
        {{ $jobs->links() }}
    </div>
    @endif

</x-app-layout>
