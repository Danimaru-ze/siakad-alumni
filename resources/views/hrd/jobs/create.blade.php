<x-app-layout>
    <x-slot name="header">
        Buat Lowongan Kerja Baru
    </x-slot>

    <div class="card-saas">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Detail Lowongan
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Isi detail lowongan kerja di bawah ini. Lowongan akan disimpan sebagai draft.
            </p>
        </div>
        
        <form action="{{ route('hrd.jobs.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Pekerjaan</label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}" class="input-saas mt-1 block w-full" required>
                    </div>
                    
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" placeholder="cth: Jakarta, Remote" class="input-saas mt-1 block w-full" required>
                    </div>

                    <div>
                        <label for="tipe_pekerjaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe Pekerjaan</label>
                        <input type="text" name="tipe_pekerjaan" id="tipe_pekerjaan" value="{{ old('tipe_pekerjaan') }}" placeholder="cth: Full-time, Internship" class="input-saas mt-1 block w-full" required>
                    </div>

                    <div>
                        <label for="kisaran_gaji_min" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gaji Minimum (Opsional)</label>
                        <input type="number" name="kisaran_gaji_min" id="kisaran_gaji_min" value="{{ old('kisaran_gaji_min') }}" class="input-saas mt-1 block w-full" placeholder="cth: 5000000">
                    </div>
                    
                    <div>
                        <label for="kisaran_gaji_max" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gaji Maksimum (Opsional)</label>
                        <input type="number" name="kisaran_gaji_max" id="kisaran_gaji_max" value="{{ old('kisaran_gaji_max') }}" class="input-saas mt-1 block w-full" placeholder="cth: 8000000">
                    </div>

                     <div class="sm:col-span-2">
                        <label for="deadline_lamaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deadline Lamaran (Opsional)</label>
                        <input type="date" name="deadline_lamaran" id="deadline_lamaran" value="{{ old('deadline_lamaran') }}" class="input-saas mt-1 block w-full">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="8" class="input-saas mt-1 block w-full" required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="persyaratan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Persyaratan</label>
                        <textarea name="persyaratan" id="persyaratan" rows="8" class="input-saas mt-1 block w-full" required>{{ old('persyaratan') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-4 rounded-b-lg">
                <a href="{{ route('hrd.jobs.index') }}" class="btn-ghost-saas px-6 py-2 rounded-lg">
                    Batal
                </a>
                <button type="submit" class="btn-primary-saas px-6 py-2 rounded-lg">
                    Simpan Draft
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
