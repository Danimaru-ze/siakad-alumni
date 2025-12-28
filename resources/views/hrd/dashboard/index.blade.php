<x-app-layout>
    <x-slot name="header">
        Dashboard HRD
    </x-slot>

    <!-- Welcome Banner -->
    <div class="mb-6 p-4 sm:p-6 bg-gradient-to-r from-siakad-primary to-siakad-dark rounded-xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-white">Selamat Datang, {{ $user->name }}!</h2>
                <p class="text-sm text-white/80">
                    Anda login sebagai HRD untuk perusahaan <span class="font-bold">{{ $user->hrdProfile->company->nama_perusahaan }}</span>.
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="card-saas p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-siakad-primary/10 rounded-xl flex items-center justify-center">
                     <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-siakad-dark">{{ $stats['total_jobs'] }}</p>
                    <p class="text-xs text-siakad-secondary">Total Lowongan</p>
                </div>
            </div>
        </div>
        <div class="card-saas p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-siakad-dark">{{ $stats['active_jobs'] }}</p>
                    <p class="text-xs text-siakad-secondary">Lowongan Aktif</p>
                </div>
            </div>
        </div>
        <div class="card-saas p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-siakad-dark">{{ $stats['pending_jobs'] }}</p>
                    <p class="text-xs text-siakad-secondary">Menunggu Approval</p>
                </div>
            </div>
        </div>
        <div class="card-saas p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-siakad-dark">{{ $stats['total_alumni'] }}</p>
                    <p class="text-xs text-siakad-secondary">Total Alumni</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-2 card-saas p-6">
            <h3 class="font-semibold text-siakad-dark mb-4">Status Lowongan Anda</h3>
            <div class="h-64">
                <canvas id="jobsStatusChart"></canvas>
            </div>
        </div>
        <div class="lg:col-span-3 card-saas p-6">
            <h3 class="font-semibold text-siakad-dark mb-4">Sebaran Alumni per Program Studi</h3>
            <div class="h-64">
                <canvas id="alumniProdiChart"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const siakadPrimary = '#234C6A';
            const siakadSecondary = '#456882';
            const emerald = '#10B981';
            const amber = '#F59E0B';
            const red = '#EF4444';

            // Jobs by Status Chart (Doughnut)
            const jobsData = @json($jobsByStatus);
            const jobsCtx = document.getElementById('jobsStatusChart')?.getContext('2d');
            if (jobsCtx && Object.keys(jobsData).length) {
                new Chart(jobsCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(jobsData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                        datasets: [{
                            data: Object.values(jobsData),
                            backgroundColor: [
                                amber,  // pending
                                emerald, // approved
                                red,     // rejected
                                siakadSecondary // flagged
                            ],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        },
                        cutout: '60%'
                    }
                });
            }

            // Alumni by Prodi Chart (Bar)
            const alumniData = @json($alumniByProdi);
            const alumniCtx = document.getElementById('alumniProdiChart')?.getContext('2d');
            if (alumniCtx && Object.keys(alumniData).length) {
                new Chart(alumniCtx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(alumniData),
                        datasets: [{
                            label: 'Jumlah Alumni',
                            data: Object.values(alumniData),
                            backgroundColor: siakadPrimary,
                            borderRadius: 4,
                            barThickness: 20,
                        }]
                    },
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
                                grid: {
                                    drawBorder: false,
                                },
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>