<x-app-layout>
    <x-slot name="header">
        Dashboard Kaprodi - {{ $prodi->nama }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-4">Selamat Datang, Bapak/Ibu {{ $user->name }}</h3>
                    <p class="mb-4">Anda sedang mengakses panel manajemen Program Studi <strong>{{ $prodi->nama }}</strong>.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <!-- Card Statistik Sederhana (Placeholder) -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-xl border border-blue-100 dark:border-blue-800">
                            <h4 class="font-bold text-blue-800 dark:text-blue-300">Total Dosen</h4>
                            <p class="text-3xl font-bold mt-2">{{ $prodi->dosen()->count() }}</p>
                        </div>
                         <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-xl border border-green-100 dark:border-green-800">
                            <h4 class="font-bold text-green-800 dark:text-green-300">Total Mahasiswa</h4>
                            <p class="text-3xl font-bold mt-2">{{ $prodi->mahasiswa()->count() }}</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-6 rounded-xl border border-purple-100 dark:border-purple-800">
                            <h4 class="font-bold text-purple-800 dark:text-purple-300">Kurikulum Aktif</h4>
                            <p class="text-3xl font-bold mt-2">2024/2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
