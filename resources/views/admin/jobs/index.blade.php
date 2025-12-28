<x-app-layout>
    <x-slot name="header">
        Manajemen Lowongan Kerja
    </x-slot>

    <div class="card-saas p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Daftar Semua Lowongan Kerja
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Moderasi lowongan kerja yang diajukan oleh perusahaan.
        </p>
    </div>

    <div class="card-saas p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul Lowongan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Perusahaan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Diajukan</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
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
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $job->hrd->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-job-status-badge :status="$job->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $job->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div x-data="{ rejectModal: false, deleteModal: false }" class="flex items-center justify-end gap-2">
                                    @if ($job->status === 'PENDING')
                                        <form action="{{ route('superadmin.jobs.approve', $job) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Approve</button>
                                        </form>
                                        <button @click="rejectModal = true" class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300">Reject</button>
                                    @endif
                                    <button @click="deleteModal = true" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>

                                    <!-- Reject Modal -->
                                    <div x-cloak x-show="rejectModal" @keydown.escape.window="rejectModal = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                                            <div @click="rejectModal = false" class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                            <div @click.away="rejectModal = false" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                <form action="{{ route('superadmin.jobs.reject', $job) }}" method="POST">
                                                    @csrf
                                                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">Tolak Lowongan Pekerjaan</h3>
                                                        <div class="mt-4">
                                                            <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alasan Penolakan (Opsional)</label>
                                                            <textarea name="reason" id="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Tolak</button>
                                                        <button @click="rejectModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div x-cloak x-show="deleteModal" @keydown.escape.window="deleteModal = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                                            <div @click="deleteModal = false" class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                            <div @click.away="deleteModal = false" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                <form action="{{ route('superadmin.jobs.destroy', $job) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Hapus Lowongan Pekerjaan</h3>
                                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus lowongan ini? Tindakan ini tidak dapat dibatalkan.</p>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Hapus</button>
                                                        <button @click="deleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
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
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                Tidak ada data lowongan kerja.
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
</x-app-layout>
