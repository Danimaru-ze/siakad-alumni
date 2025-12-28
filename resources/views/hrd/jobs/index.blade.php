<x-app-layout>
    <x-slot name="header">
        Lowongan Saya
    </x-slot>

    <div x-data="{ deleteModal: false, submitModal: false, selectedJob: null }">
        <!-- Header & Actions -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Kelola Lowongan Kerja Anda
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Buat, edit, dan ajukan lowongan kerja dari perusahaan Anda.
                </p>
            </div>
            <a href="{{ route('hrd.jobs.create') }}" class="btn-primary-saas px-4 py-2 rounded-lg">
                Buat Lowongan Baru
            </a>
        </div>

        <!-- Table Card -->
        <div class="card-saas overflow-hidden dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full table-saas">
                    <thead class="bg-siakad-light/30 dark:bg-gray-900 border-b border-siakad-light dark:border-gray-700">
                        <tr>
                            <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Judul</th>
                            <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Dibuat</th>
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
                                    <x-job-status-badge :status="$job->status" />
                                </td>
                                <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">
                                    {{ $job->created_at->format('d M Y') }}
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        @if(in_array($job->status, ['DRAFT', 'REJECTED']))
                                            <button @click="selectedJob = {{ $job }}; submitModal = true" class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-900">Ajukan</button>
                                            <a href="{{ route('hrd.jobs.edit', $job) }}" class="font-medium text-amber-600 dark:text-amber-400 hover:text-amber-900">Edit</a>
                                        @endif
                                        <button @click="selectedJob = {{ $job }}; deleteModal = true" class="font-medium text-red-600 dark:text-red-400 hover:text-red-900">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-siakad-light/50 dark:bg-gray-700/50 rounded-xl flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-siakad-secondary dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="text-siakad-secondary dark:text-gray-400 text-sm">Anda belum membuat lowongan kerja.</p>
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

        <!-- Delete Modal -->
        <div x-cloak x-show="deleteModal" @keydown.escape.window="deleteModal = false" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div @click="deleteModal = false" class="fixed inset-0 bg-gray-500/75"></div>
                <div @click.away="deleteModal = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 z-10">
                    <form :action="'/hrd/jobs/' + selectedJob.id" method="POST">
                        @csrf
                        @method('DELETE')
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Hapus Lowongan</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Anda yakin ingin menghapus lowongan <strong x-text="selectedJob.judul"></strong>? Tindakan ini permanen.</p>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="deleteModal = false" class="btn-ghost-saas px-4 py-2 rounded-lg">Batal</button>
                            <button type="submit" class="btn-primary-saas bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Submit Modal -->
        <div x-cloak x-show="submitModal" @keydown.escape.window="submitModal = false" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div @click="submitModal = false" class="fixed inset-0 bg-gray-500/75"></div>
                <div @click.away="submitModal = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 z-10">
                    <form :action="'/hrd/jobs/' + selectedJob.id + '/submit'" method="POST">
                        @csrf
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ajukan Lowongan</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Anda yakin ingin mengajukan lowongan <strong x-text="selectedJob.judul"></strong> untuk direview oleh admin?</p>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="submitModal = false" class="btn-ghost-saas px-4 py-2 rounded-lg">Batal</button>
                            <button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg">Ya, Ajukan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
