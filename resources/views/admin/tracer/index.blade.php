<x-app-layout>
    <x-slot name="header">
        Tracer Study
    </x-slot>

    <div>
        <!-- Header & Actions -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Manajemen Tracer Study
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Kelola, pantau, dan lihat hasil dari studi pelacakan alumni.
                </p>
            </div>
            <a href="{{ route('admin.tracer.create') }}" class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Buat Studi Baru
            </a>
        </div>

        <!-- Table Card -->
        <div class="card-saas overflow-hidden dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full table-saas">
                    <thead class="bg-siakad-light/30 dark:bg-gray-900 border-b border-siakad-light dark:border-gray-700">
                        <tr>
                            <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Nama Studi</th>
                            <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="text-center py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Responden</th>
                            <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Target</th>
                            <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Periode</th>
                            <th class="text-right py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-siakad-light dark:divide-gray-700">
                        @forelse ($tracerStudies as $study)
                            <tr>
                                <td class="py-4 px-5">
                                    <p class="font-medium text-siakad-dark dark:text-white">{{ $study->nama }}</p>
                                    <p class="text-xs text-siakad-secondary dark:text-gray-400">
    @if($study->fakultas)
        Target: {{ $study->fakultas->nama }}
    @elseif(is_array($study->target_prodi_ids) && count($study->target_prodi_ids) > 0)
        Target: {{ count($study->target_prodi_ids) }} Program Studi
    @else
        Target: Semua Alumni
    @endif
</p>
                                </td>
                                <td class="py-4 px-5">
                                    @php
                                        $now = now();
                                        $status = 'UPCOMING';
                                        $color = 'gray';
                                        if ($now->between($study->tanggal_mulai, $study->tanggal_selesai)) {
                                            $status = 'AKTIF';
                                            $color = 'emerald';
                                        } elseif ($now->gt($study->tanggal_selesai)) {
                                            $status = 'SELESAI';
                                            $color = 'blue';
                                        }
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium bg-{{$color}}-100 text-{{$color}}-700 dark:bg-{{$color}}-900/50 dark:text-{{$color}}-400 rounded-full">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-center text-sm text-siakad-secondary dark:text-gray-400">
                                    <span class="font-semibold text-siakad-dark dark:text-white">{{ $study->responses->where('status', 'SUDAH_MENGISI')->count() }}</span>
                                    /
                                    <span>{{ $study->responses_count }}</span>
                                </td>
                                <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">
                                    Angkatan {{ $study->target_angkatan }}
                                </td>
                                <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">
                                    {{ $study->tanggal_mulai->format('d M Y') }} - {{ $study->tanggal_selesai->format('d M Y') }}
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.tracer.results', $study) }}" class="p-2 text-siakad-secondary hover:text-siakad-primary hover:bg-siakad-primary/10 rounded-lg transition" title="Lihat Hasil">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                        </a>
                                        <a href="{{ route('admin.tracer.edit', $study) }}" class="p-2 text-siakad-secondary hover:text-amber-600 hover:bg-amber-500/10 rounded-lg transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.tracer.destroy', $study) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus studi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-siakad-secondary hover:text-red-600 hover:bg-red-500/10 rounded-lg transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-siakad-secondary">
                                    <p class="mb-2">Belum ada tracer study yang dibuat.</p>
                                    <a href="#" class="text-sm text-siakad-primary hover:underline">Buat Studi Baru</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             @if ($tracerStudies->hasPages())
                <div class="px-6 py-4 border-t border-siakad-light dark:border-gray-700 bg-white dark:bg-gray-800">
                    {{ $tracerStudies->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>