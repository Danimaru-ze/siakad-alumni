<x-app-layout>
    <x-slot name="header">
        Dashboard Alumni
    </x-slot>

    <!-- Greeting -->
    <div class="mb-8 hidden md:block">
        <h1 class="text-2xl font-semibold text-siakad-dark">
            {{ $greeting }}, {{ explode(' ', $user->name)[0] }}! 
            @php
                $hour = now()->hour;
                if ($hour < 11) { $emoji = '🌅'; }
                elseif ($hour < 15) { $emoji = '☀️'; }
                elseif ($hour < 18) { $emoji = '🌤️'; }
                else { $emoji = '🌙'; }
            @endphp
            {{ $emoji }}
        </h1>
        <p class="text-siakad-secondary text-sm mt-1">Selamat datang kembali di portal alumni!</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Profile & IPK Card -->
        <div class="card-saas p-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-xl bg-siakad-primary flex items-center justify-center text-white text-xl font-semibold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-semibold text-siakad-dark">{{ $user->name }}</h3>
                    <p class="text-sm text-siakad-secondary font-mono">{{ $alumni->nim }}</p>
                    <p class="text-xs text-siakad-secondary/70">{{ $alumni->prodi->nama ?? '-' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <!-- IPK Card -->
                <div class="bg-siakad-primary rounded-xl p-4 text-white col-span-2">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[11px] font-medium opacity-80 uppercase tracking-wide">IPK</span>
                    </div>
                    <p class="text-3xl font-bold">{{ number_format($ipkData['ipk'] ?? 0, 2) }}</p>
                    <p class="text-[10px] opacity-70 mt-1">Indeks Prestasi Kumulatif</p>
                </div>
            </div>

            <div class="mt-5 pt-4 border-t border-siakad-light/50">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-siakad-secondary">Total SKS Lulus</span>
                    <span class="font-semibold text-siakad-dark">{{ $ipkData['total_sks'] ?? 0 }} SKS</span>
                </div>
                 <div class="flex items-center justify-between text-sm mt-2">
                    <span class="text-siakad-secondary">Status</span>
                    <span class="font-semibold text-emerald-600 capitalize">{{ $alumni->status }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Action Cards -->
        <div class="lg:col-span-2 grid grid-cols-2 gap-4">
            <a href="{{ route('alumni.jobs.index') }}" class="card-saas p-5 hover:border-siakad-primary/30 group">
                <div class="w-11 h-11 bg-siakad-primary/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-siakad-primary/20 transition">
                    <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="font-semibold text-siakad-dark mb-1">Peluang Karir</h3>
                <p class="text-sm text-siakad-secondary">Lihat lowongan kerja</p>
            </a>

            <a href="{{ route('alumni.transkrip.index') }}" class="card-saas p-5 hover:border-siakad-primary/30 group">
                <div class="w-11 h-11 bg-siakad-secondary/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-siakad-secondary/20 transition">
                    <svg class="w-5 h-5 text-siakad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="font-semibold text-siakad-dark mb-1">Riwayat Akademik</h3>
                <p class="text-sm text-siakad-secondary">Lihat Transkrip & KHS</p>
            </a>

            <a href="{{ route('alumni.biodata.index') }}" class="card-saas p-5 hover:border-siakad-primary/30 group col-span-2">
                <div class="w-11 h-11 bg-siakad-dark/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-siakad-dark/20 transition">
                    <svg class="w-5 h-5 text-siakad-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h3 class="font-semibold text-siakad-dark mb-1">Biodata</h3>
                <p class="text-sm text-siakad-secondary">Perbarui data diri & kontak</p>
            </a>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1">
        <div class="card-saas p-6">
            <h3 class="font-semibold text-siakad-dark mb-4">Grafik Distribusi Nilai</h3>
            <div class="h-64">
                <canvas id="gradeChart"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? '#334155' : '#E3E3E3';
            const textColor = isDark ? '#94A3B8' : '#456882';

            const gradeData = @json($gradeDistribution);
            const ctx = document.getElementById('gradeChart').getContext('2d');
            
            const chartData = {
                labels: Object.keys(gradeData),
                datasets: [{
                    label: 'Jumlah Nilai',
                    data: Object.values(gradeData),
                    backgroundColor: [
                        '#234C6A', '#456882', '#60A5FA', '#3B82F6',
                        '#1D4ED8', '#9CA3AF', '#6B7280'
                    ],
                    borderRadius: 6,
                }]
            };

            new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: textColor,
                                precision: 0
                            },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
