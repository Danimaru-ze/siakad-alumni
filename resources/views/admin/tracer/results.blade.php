<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Hasil: {{ $tracer->nama }}
            </h2>
            <a href="{{ route('admin.tracer.export', $tracer) }}" class="btn-secondary-saas px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Download Excel
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Summary Card --}}
        <div class="card-saas">
            <div class="p-6 border-b border-siakad-light">
                <h3 class="text-lg font-medium text-siakad-dark">Ringkasan Hasil</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-siakad-light/20 p-4 rounded-lg">
                    <h4 class="text-sm text-siakad-secondary font-medium">Jumlah Responden</h4>
                    <p class="text-3xl font-semibold text-siakad-dark mt-2">{{ $totalResponses }}</p>
                </div>
                <div class="bg-siakad-light/20 p-4 rounded-lg">
                    <h4 class="text-sm text-siakad-secondary font-medium">Total Target Alumni</h4>
                    <p class="text-3xl font-semibold text-siakad-dark mt-2">{{ $totalTargets }}</p>
                </div>
                <div class="bg-siakad-light/20 p-4 rounded-lg">
                    <h4 class="text-sm text-siakad-secondary font-medium">Tingkat Respons</h4>
                    <div class="flex items-center gap-4 mt-2">
                        <p class="text-3xl font-semibold text-siakad-dark">{{ number_format($responseRate, 1) }}%</p>
                        <div class="w-full bg-siakad-light rounded-full h-2.5">
                            <div class="bg-siakad-primary h-2.5 rounded-full" style="width: {{ number_format($responseRate, 2) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Question Results --}}
        <h3 class="text-lg font-medium text-siakad-dark">Detail Jawaban per Pertanyaan</h3>
        
        @foreach ($questionResults as $id => $result)
            <div class="card-saas">
                <div class="p-6 border-b border-siakad-light">
                    <h4 class="font-semibold text-siakad-dark">{{ $loop->iteration }}. {{ $result['pertanyaan'] }}</h4>
                    <p class="text-xs text-siakad-secondary mt-1">
                        {{ $result['total_menjawab'] }} dari {{ $totalResponses }} alumni menjawab pertanyaan ini.
                    </p>
                </div>
                <div class="p-6">
                    @if (in_array($result['tipe'], ['radio', 'dropdown', 'checkbox']))
                        @if (($result['stats'] instanceof \Illuminate\Support\Collection || is_array($result['stats'])) && !empty($result['stats']))
                            <div class="h-64 md:h-80">
                                <canvas id="chart-{{ $id }}"></canvas>
                            </div>
                        @else
                            <p class="text-siakad-secondary text-sm text-center py-10">Belum ada jawaban untuk pertanyaan ini.</p>
                        @endif
                    @elseif(in_array($result['tipe'], ['short_text', 'long_text']))
                         @if (!empty($result['stats']))
                             <div class="max-h-72 overflow-y-auto space-y-3 pr-4">
                                @foreach ($result['stats'] as $answer)
                                    <div class="p-3 border rounded-lg text-sm text-siakad-dark bg-gray-50 dark:bg-gray-800/50">
                                        {{ $answer }}
                                    </div>
                                @endforeach
                            </div>
                         @else
                            <p class="text-siakad-secondary text-sm text-center py-10">Belum ada jawaban untuk pertanyaan ini.</p>
                         @endif
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const questionResults = {!! json_encode($questionResults) !!};

            for (const id in questionResults) {
                const result = questionResults[id];
                if (['radio', 'dropdown', 'checkbox'].includes(result.tipe)) {
                    const stats = result.stats;
                    const canvas = document.getElementById(`chart-${id}`);
                    
                    if (canvas && stats && Object.keys(stats).length > 0) {
                        const labels = Object.keys(stats);
                        const data = Object.values(stats);

                        new Chart(canvas, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Jumlah Jawaban',
                                    data: data,
                                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                    borderColor: 'rgba(59, 130, 246, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>