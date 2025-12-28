<x-app-layout>
    <x-slot name="header">
        Lengkapi Profil HRD & Perusahaan
    </x-slot>

    <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Satu Langkah Lagi
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Untuk dapat membuat lowongan kerja, Anda harus melengkapi informasi tentang Anda dan perusahaan Anda.
            </p>

            @if(session('error'))
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif


            <form action="{{ route('hrd.profile.store') }}" method="POST" class="mt-6 space-y-6">
                @csrf
                
                <fieldset class="border-t border-b border-gray-200 dark:border-gray-700">
                    <legend class="px-4 text-base font-medium text-gray-900 dark:text-gray-100">Profil Anda (HRD)</legend>
                    <div class="p-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="nama_hrd" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap Anda</label>
                            <input type="text" name="nama_hrd" id="nama_hrd" value="{{ old('nama_hrd', Auth::user()->name) }}" class="input-saas mt-1 block w-full" required>
                        </div>
                         <div>
                            <label for="jabatan_hrd" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jabatan Anda</label>
                            <input type="text" name="jabatan_hrd" id="jabatan_hrd" value="{{ old('jabatan_hrd') }}" placeholder="cth: HRD Manager" class="input-saas mt-1 block w-full" required>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border-t border-b border-gray-200 dark:border-gray-700">
                    <legend class="px-4 text-base font-medium text-gray-900 dark:text-gray-100">Profil Perusahaan</legend>
                    <div class="p-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan') }}" class="input-saas mt-1 block w-full" required>
                        </div>
                        <div>
                            <label for="email_perusahaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Resmi Perusahaan</label>
                            <input type="email" name="email_perusahaan" id="email_perusahaan" value="{{ old('email_perusahaan') }}" class="input-saas mt-1 block w-full" required>
                        </div>
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                            <input type="url" name="website" id="website" value="{{ old('website') }}" placeholder="https://..." class="input-saas mt-1 block w-full">
                        </div>
                        <div class="sm:col-span-2">
                             <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Perusahaan</label>
                             <textarea name="alamat" id="alamat" rows="4" class="input-saas mt-1 block w-full" required>{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </fieldset>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary-saas px-6 py-2 rounded-lg">
                        Simpan dan Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
