<x-app-layout>
    <x-slot name="header">
        Manajemen Lowongan Kerja
    </x-slot>

    <!-- Header & Actions -->
    <div class="mb-6 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Daftar Semua Lowongan Kerja
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Moderasi lowongan kerja yang diajukan oleh perusahaan.
            </p>
        </div>
        {{-- Filter atau tombol tambah bisa ditambahkan di sini jika perlu --}}
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
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Tanggal Diajukan</th>
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
                            <td class="py-4 px-5">
                                <div class="font-medium text-siakad-dark dark:text-white">{{ $job->company->nama_perusahaan }}</div>
                                <div class="text-xs text-siakad-secondary dark:text-gray-400">{{ $job->hrd->name }}</div>
                            </td>
                            <td class="py-4 px-5">
                                <x-job-status-badge :status="$job->status" />
                            </td>
                            <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">
                                {{ $job->created_at->format('d M Y') }}
                            </td>
                            <td class="py-4 px-5 text-right">
                                <div x-data="{ rejectModal: false, deleteModal: false }" class="flex items-center justify-end gap-2">
                                    <a href="{{ route('superadmin.jobs.show', $job) }}" class="p-2 text-gray-500 hover:text-indigo-600 rounded-lg transition" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    @if ($job->status === 'PENDING')
                                        <form action="{{ route('superadmin.jobs.approve', $job) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-indigo-600 hover:bg-indigo-100 rounded-lg transition" title="Approve">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </form>
                                        <button @click="rejectModal = true" class="p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition" title="Reject">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    @endif
                                    <button @click="deleteModal = true" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>

                                    <!-- Reject Modal -->
                                    <div x-cloak x-show="rejectModal" @keydown.escape.window="rejectModal = false" class="fixed inset-0 z-50 overflow-y-auto">
                                        <div class="flex items-center justify-center min-h-screen px-4">
                                            <div class="fixed inset-0 bg-black/50" @click="rejectModal = false"></div>
                                            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
                                                <form action="{{ route('superadmin.jobs.reject', $job) }}" method="POST">
                                                    @csrf
                                                    <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-4 text-left">Tolak Lowongan Pekerjaan</h3>
                                                    <div class="text-left">
                                                        <label for="reason" class="block text-sm font-medium text-siakad-secondary mb-1">Alasan Penolakan (Opsional)</label>
                                                        <textarea name="reason" id="reason" rows="3" class="input-saas w-full dark:bg-gray-700 dark:border-gray-600"></textarea>
                                                    </div>
                                                    <div class="flex justify-end gap-3 pt-4">
                                                        <button type="button" @click="rejectModal = false" class="px-4 py-2 text-sm text-siakad-secondary hover:bg-gray-100 rounded-lg">Batal</button>
                                                        <button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg text-sm bg-red-600 hover:bg-red-700">Tolak</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div x-cloak x-show="deleteModal" @keydown.escape.window="deleteModal = false" class="fixed inset-0 z-50 overflow-y-auto">
                                        <div class="flex items-center justify-center min-h-screen px-4">
                                            <div class="fixed inset-0 bg-black/50" @click="deleteModal = false"></div>
                                            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
                                                <form action="{{ route('superadmin.jobs.destroy', $job) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-4 text-left">Hapus Lowongan Pekerjaan</h3>
                                                    <p class="text-sm text-siakad-secondary dark:text-gray-400 mb-4 text-left">Apakah Anda yakin ingin menghapus lowongan ini? Tindakan ini tidak dapat dibatalkan.</p>
                                                    <div class="flex justify-end gap-3">
                                                        <button type="button" @click="deleteModal = false" class="px-4 py-2 text-sm text-siakad-secondary hover:bg-gray-100 rounded-lg">Batal</button>
                                                        <button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg text-sm bg-red-600 hover:bg-red-700">Hapus</button>
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
