<x-app-layout>
    <x-slot name="header">
        Akses Ditolak
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-medium text-gray-900 dark:text-white">Akses Panel Kaprodi Gagal</h3>
                    <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        <p>
                            Akun Anda saat ini tidak terdaftar sebagai Ketua Program Studi (Kaprodi) di program studi manapun.
                        </p>
                        <p class="mt-1">
                            Silakan hubungi administrator Superadmin untuk pengangkatan jabatan.
                        </p>
                    </div>
                    <div class="mt-6">
                        <a href="{{ url()->previous() }}" class="btn-primary-saas px-4 py-2 text-sm rounded-lg">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
