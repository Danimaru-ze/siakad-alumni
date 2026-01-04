<x-app-layout>
    <x-slot name="header">
        Dashboard Kaprodi
    </x-slot>

    <!-- Welcome Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-siakad-dark dark:text-white">Selamat Datang, {{ Auth::user()->name }}</h2>
            <p class="text-siakad-secondary dark:text-gray-400">Kaprodi {{ $prodi->nama ?? '-' }} • Semester {{ $activeTa->nama ?? 'Tidak Aktif' }}</p>
        </div>
        <div class="flex gap-2">
            <span class="px-3 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 rounded-full text-xs font-semibold">Tahun Akademik: {{ $activeTa->tahun ?? '-' }} {{ $activeTa->semester ?? '' }}</span>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- SECTION A: DATA RINGKASAN SEBAGAI KAPRODI --}}
    {{-- ============================================= --}}
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-3 flex items-center gap-2">
            <svg class="w-6 h-6 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Ringkasan Program Studi
        </h3>
    </div>
    <!-- Key Metrics Prodi -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card-saas p-6 dark:bg-gray-800"><p class="text-sm font-medium text-siakad-secondary dark:text-gray-400 uppercase">Mahasiswa Aktif</p><h3 class="text-3xl font-bold text-siakad-dark dark:text-white mt-2">{{ $prodiStats['total_mahasiswa'] }}</h3></div>
        <div class="card-saas p-6 dark:bg-gray-800"><p class="text-sm font-medium text-siakad-secondary dark:text-gray-400 uppercase">Dosen Homebase</p><h3 class="text-3xl font-bold text-siakad-dark dark:text-white mt-2">{{ $prodiStats['total_dosen'] }}</h3></div>
        <div class="card-saas p-6 dark:bg-gray-800"><p class="text-sm font-medium text-siakad-secondary dark:text-gray-400 uppercase">Rata-rata IPK</p><h3 class="text-3xl font-bold text-siakad-dark dark:text-white mt-2">{{ number_format($prodiStats['rata_ipk'], 2) }}</h3></div>
        <div class="card-saas p-6 dark:bg-gray-800"><p class="text-sm font-medium text-siakad-secondary dark:text-gray-400 uppercase">Kelas Aktif</p><h3 class="text-3xl font-bold text-siakad-dark dark:text-white mt-2">{{ $prodiStats['kelas_aktif'] }}</h3></div>
    </div>

    <!-- Charts Prodi -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="card-saas p-6 dark:bg-gray-800"><h3 class="font-bold text-lg text-siakad-dark dark:text-white mb-4">Tren Mahasiswa per Angkatan</h3><div id="chartAngkatan" class="w-full h-80"></div></div>
        <div class="card-saas p-6 dark:bg-gray-800"><h3 class="font-bold text-lg text-siakad-dark dark:text-white mb-4">Sebaran Nilai Semester Ini</h3><div id="chartNilai" class="w-full h-80"></div></div>
    </div>
    
    <!-- Lists Prodi -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="card-saas dark:bg-gray-800 overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-emerald-500 to-teal-600 text-white"><h3 class="font-bold">Mahasiswa Berprestasi (Top 5 IPK)</h3></div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($topMahasiswa as $mhs)
                <div class="p-4 flex items-center gap-3"><div class="flex-1 min-w-0"><p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $mhs->user->name }}</p><p class="text-xs text-gray-500">{{ $mhs->nim }}</p></div><span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $mhs->ipk }}</span></div>
                @empty
                <div class="p-6 text-center text-gray-500 text-sm">Belum ada data.</div>
                @endforelse
            </div>
        </div>
        <div class="card-saas dark:bg-gray-800 overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-red-500 to-pink-600 text-white"><h3 class="font-bold">Perlu Perhatian Khusus (IPK < 2.5)</h3></div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($warningMahasiswa as $mhs)
                <div class="p-4 flex items-center gap-3"><div class="flex-1 min-w-0"><p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $mhs->user->name }}</p><p class="text-xs text-gray-500">{{ $mhs->nim }}</p></div><span class="text-sm font-bold text-red-600 dark:text-red-400">{{ $mhs->ipk }}</span></div>
                @empty
                <div class="p-6 text-center text-gray-500 text-sm">Tidak ada mahasiswa dengan IPK rendah.</div>
                @endforelse
            </div>
        </div>
    </div>

    <hr class="my-12 border-gray-200 dark:border-gray-700">

    {{-- ========================================= --}}
    {{-- SECTION B: DATA PERSONAL SEBAGAI DOSEN  --}}
    {{-- ========================================= --}}
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-3 flex items-center gap-2">
            <svg class="w-6 h-6 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            Ringkasan Peran Dosen
        </h3>
    </div>
    <!-- Dosen Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card-saas p-6 dark:bg-gray-800"><p class="text-sm font-medium text-siakad-secondary dark:text-gray-400 uppercase">Kelas Diampu</p><h3 class="text-3xl font-bold text-siakad-dark dark:text-white mt-2">{{ $totalKelas }}</h3></div>
        <div class="card-saas p-6 dark:bg-gray-800"><p class="text-sm font-medium text-siakad-secondary dark:text-gray-400 uppercase">Total Mahasiswa Ajar</p><h3 class="text-3xl font-bold text-siakad-dark dark:text-white mt-2">{{ $totalMahasiswa }}</h3></div>
        <div class="card-saas p-6 dark:bg-gray-800">
            <p class="text-sm font-medium text-siakad-secondary dark:text-gray-400 uppercase">Input Nilai</p>
            <div class="flex items-baseline mt-2">
                <h3 class="text-3xl font-bold text-siakad-dark dark:text-white">{{ $persentaseNilai }}%</h3>
                <span class="text-xs text-siakad-secondary ml-2">({{ $nilaiDiinput }}/{{ $totalNilai }})</span>
            </div>
        </div>
        <div class="card-saas p-6 dark:bg-gray-800"><p class="text-sm font-medium text-siakad-secondary dark:text-gray-400 uppercase">Mahasiswa Bimbingan</p><h3 class="text-3xl font-bold text-siakad-dark dark:text-white mt-2">{{ $mahasiswaBimbinganCount }}</h3></div>
    </div>
    
    <!-- Dosen Personal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
             <!-- Jadwal Hari Ini -->
            <div class="card-saas p-6 dark:bg-gray-800">
                <h3 class="text-lg font-bold text-siakad-dark dark:text-white mb-4">Jadwal Mengajar Hari Ini ({{ $hariIni }})</h3>
                <div class="space-y-3">
                    @forelse($kelasHariIni as $kelas)
                    <div class="flex items-center gap-4 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <div class="w-20 text-center">
                            <p class="font-bold text-siakad-primary">{{ \Carbon\Carbon::parse($kelas->jadwal->first()->jam_mulai)->format('H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($kelas->jadwal->first()->jam_selesai)->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-siakad-dark dark:text-white">{{ $kelas->mataKuliah->nama_mk }}</p>
                            <p class="text-sm text-gray-500">Kelas {{ $kelas->nama_kelas }}</p>
                        </div>
                        <div class="ml-auto">
                            <a href="{{ route('dosen.penilaian.show', $kelas->id) }}" class="btn-primary-saas text-xs">Detail Kelas</a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-gray-500">Tidak ada jadwal mengajar hari ini.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Nilai & Bimbingan -->
        <div class="space-y-8">
            <div class="card-saas p-6 dark:bg-gray-800">
                <h3 class="font-bold text-siakad-dark dark:text-white mb-4">Aktivitas Penilaian Terkini</h3>
                <div class="space-y-4">
                    @forelse($recentNilai as $nilai)
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Nilai untuk <span class="font-bold">{{ Str::limit($nilai->mahasiswa->user->name, 15) }}</span></p>
                            <p class="text-xs text-gray-500">{{ $nilai->kelas->mataKuliah->nama_mk }}</p>
                        </div>
                        <div class="ml-auto text-right">
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $nilai->nilai_huruf }}</span>
                            <p class="text-xs text-gray-400">{{ $nilai->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-center text-gray-500 py-4">Belum ada aktivitas penilaian.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Kelas Diampu -->
    <div class="mt-8">
        <div class="card-saas p-6 dark:bg-gray-800">
            <h3 class="text-lg font-bold text-siakad-dark dark:text-white mb-4">Daftar Kelas Semester Ini</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jml. Mhs</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($kelasList as $kelas)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-gray-900 dark:text-white">{{ $kelas->mataKuliah->nama_mk }}</div><span class="text-xs text-gray-500">{{$kelas->mataKuliah->kode_mk}}</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $kelas->nama_kelas }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $kelas->krsDetail->count() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @foreach($kelas->jadwal as $jadwal)
                                    <div>{{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</div>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('dosen.penilaian.show', $kelas->id) }}" class="text-indigo-600 hover:text-indigo-900">Input Nilai</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">Anda tidak mengampu kelas apapun semester ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart 1: Tren Mahasiswa per Angkatan
            if (document.querySelector("#chartAngkatan")) {
                var optionsAngkatan = {
                    series: [{ name: 'Jumlah Mahasiswa', data: {!! json_encode($mahasiswaPerAngkatan->values()) !!} }],
                    chart: { type: 'bar', height: 320, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#234C6A'],
                    plotOptions: { bar: { borderRadius: 4, horizontal: false, columnWidth: '55%' } },
                    dataLabels: { enabled: false },
                    xaxis: { categories: {!! json_encode($mahasiswaPerAngkatan->keys()) !!}, labels: { style: { colors: '#6B7280' } } },
                    yaxis: { labels: { style: { colors: '#6B7280' } } },
                    grid: { borderColor: document.body.classList.contains('dark') ? '#374151' : '#f1f1f1' }
                };
                new ApexCharts(document.querySelector("#chartAngkatan"), optionsAngkatan).render();
            }

            // Chart 2: Sebaran Nilai (Bar)
            if (document.querySelector("#chartNilai")) {
                var optionsNilai = {
                    series: [{ name: 'Jumlah', data: {!! json_encode(array_values($sebaranNilai->toArray())) !!} }],
                    chart: { type: 'bar', height: 320, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#8b5cf6'],
                    plotOptions: { bar: { borderRadius: 4, horizontal: true, } },
                    xaxis: { categories: {!! json_encode(array_keys($sebaranNilai->toArray())) !!}, labels: { style: { colors: '#6B7280' } } },
                    yaxis: { labels: { style: { colors: '#6B7280' } } },
                    grid: { show: false }
                };
                new ApexCharts(document.querySelector("#chartNilai"), optionsNilai).render();
            }
        });
    </script>
    @endpush
</x-app-layout>