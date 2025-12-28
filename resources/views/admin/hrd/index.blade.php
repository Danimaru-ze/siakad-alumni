<x-app-layout>
    <x-slot name="header">
        Data HRD
    </x-slot>

    @if(session('success'))<div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm text-siakad-secondary dark:text-gray-400">Kelola data HRD dalam sistem</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openModal('createModal')" class="btn-primary-saas px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Tambah
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="hidden md:block card-saas overflow-hidden dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full table-saas">
                <thead>
                    <tr class="bg-siakad-light/30 dark:bg-gray-900 border-b border-siakad-light dark:border-gray-700">
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Nama HRD</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Perusahaan</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Jabatan</th>
                        <th class="text-right py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-siakad-light dark:divide-gray-700">
                    @forelse($hrdProfiles as $hrd)
                    <tr class="border-b border-siakad-light/50 dark:border-gray-700/50">
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-siakad-secondary dark:bg-gray-700 flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr($hrd->nama_hrd, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-siakad-dark dark:text-white">{{ $hrd->nama_hrd }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">{{ $hrd->email_hrd }}</td>
                        <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">{{ $hrd->company->nama_perusahaan ?? '-' }}</td>
                        <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">{{ $hrd->jabatan_hrd ?? '-' }}</td>
                        <td class="py-4 px-5 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button onclick="openEditModal({{ json_encode($hrd) }})" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <form action="{{ route('admin.hrd.destroy', $hrd) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus HRD dan akun user terkait?')">@csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded-lg" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <p class="text-siakad-secondary dark:text-gray-400">Tidak ada data HRD</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($hrdProfiles->hasPages())
    <div class="card-saas px-5 py-4 mt-4">
        {{ $hrdProfiles->links() }}
    </div>
    @endif

    <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50" onclick="closeModal('createModal')"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-4">Tambah HRD</h3>
                <form action="{{ route('admin.hrd.store') }}" method="POST" class="space-y-4">@csrf
                    <div><label class="block text-sm font-medium text-siakad-secondary mb-1">Nama HRD</label><input type="text" name="nama_hrd" required class="input-saas w-full"></div>
                    <div><label class="block text-sm font-medium text-siakad-secondary mb-1">Email Login (Akan menjadi email user)</label><input type="email" name="email" required class="input-saas w-full"></div>
                    <div><label class="block text-sm font-medium text-siakad-secondary mb-1">Password</label><input type="password" name="password" required minlength="8" class="input-saas w-full"></div>
                    <div><label class="block text-sm font-medium text-siakad-secondary mb-1">Nama Perusahaan</label><input type="text" name="nama_perusahaan" required class="input-saas w-full" placeholder="Ketik untuk mencari atau membuat baru..."></div>
                    <div><label class="block text-sm font-medium text-siakad-secondary mb-1">Jabatan</label><input type="text" name="jabatan_hrd" class="input-saas w-full"></div>
                    <div class="flex justify-end gap-3"><button type="button" onclick="closeModal('createModal')" class="px-4 py-2 text-sm text-siakad-secondary hover:bg-gray-100 rounded-lg">Batal</button><button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg text-sm">Simpan</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50" onclick="closeModal('editModal')"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-4">Edit HRD</h3>
                <form id="editForm" method="POST" class="space-y-4">@csrf @method('PUT')
                    <div><label class="block text-sm font-medium text-siakad-secondary mb-1">Nama HRD</label><input type="text" name="nama_hrd" id="editNamaHrd" required class="input-saas w-full"></div>
                    <div><label class="block text-sm font-medium text-siakad-secondary mb-1">Email Login</label><input type="email" name="email" id="editEmail" required class="input-saas w-full"></div>
                    <div><label class="block text-sm font-medium text-siakad-secondary mb-1">Password (kosongkan jika tidak diubah)</label><input type="password" name="password" minlength="8" class="input-saas w-full"></div>
                    <div><label class="block text-sm font-medium text-siakad-secondary mb-1">Nama Perusahaan</label><input type="text" name="nama_perusahaan" id="editNamaPerusahaan" required class="input-saas w-full"></div>
                    <div><label class="block text-sm font-medium text-siakad-secondary mb-1">Jabatan</label><input type="text" name="jabatan_hrd" id="editJabatanHrd" class="input-saas w-full"></div>
                    <div class="flex justify-end gap-3"><button type="button" onclick="closeModal('editModal')" class="px-4 py-2 text-sm text-siakad-secondary hover:bg-gray-100 rounded-lg">Batal</button><button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg text-sm">Simpan</button></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
        function openEditModal(hrd) {
            document.getElementById('editForm').action = `/admin/hrd/${hrd.id}`;
            document.getElementById('editNamaHrd').value = hrd.nama_hrd;
            document.getElementById('editEmail').value = hrd.user.email; // Ambil dari relasi user
            document.getElementById('editNamaPerusahaan').value = hrd.company.nama_perusahaan; // Ambil dari relasi company
            document.getElementById('editJabatanHrd').value = hrd.jabatan_hrd;
            openModal('editModal');
        }
    </script>
</x-app-layout>
