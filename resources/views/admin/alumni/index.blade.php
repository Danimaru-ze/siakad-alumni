@props(['context' => 'admin'])

<x-app-layout>
    <x-slot name="header">
        Daftar Alumni
    </x-slot>

    <!-- Header & Actions -->
    <div class="mb-6 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                @if($context === 'hrd')
                    Database Alumni untuk Rekrutmen
                @else
                    Database Alumni
                @endif
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Lihat dan filter daftar alumni untuk keperluan rekrutmen.
            </p>
        </div>
        <!-- Filters -->
        <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-3">
            <select name="prodi_id" class="input-saas px-4 py-2 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                <option value="">Semua Prodi</option>
                @foreach($prodis as $prodi)
                    <option value="{{ $prodi->id }}" @selected(request('prodi_id') == $prodi->id)>{{ $prodi->nama }}</option>
                @endforeach
            </select>
            <input type="number" name="tahun_lulus" value="{{ request('tahun_lulus') }}" class="input-saas px-4 py-2 text-sm w-40 dark:bg-gray-900 dark:border-gray-700" placeholder="Thn Lulus">
            <button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg text-sm font-medium">Filter</button>
            <a href="{{ url()->current() }}" class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
        </form>
    </div>

    <!-- Table Card -->
    <div class="card-saas overflow-hidden dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full table-saas">
                <thead class="bg-siakad-light/30 dark:bg-gray-900 border-b border-siakad-light dark:border-gray-700">
                    <tr>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Nama Alumni</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">NIM</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Program Studi</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Tahun Lulus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-siakad-light dark:divide-gray-700">
                    @forelse ($alumni as $alumnus)
                        <tr class="border-b border-siakad-light/50 dark:border-gray-700/50">
                            <td class="py-4 px-5">
                                <div class="font-medium text-siakad-dark dark:text-white">{{ $alumnus->user->name }}</div>
                                <div class="text-xs text-siakad-secondary dark:text-gray-400">{{ $alumnus->user->email }}</div>
                            </td>
                            <td class="py-4 px-5 text-sm font-mono text-siakad-secondary dark:text-gray-400">
                                {{ $alumnus->nim }}
                            </td>
                            <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">
                                {{ $alumnus->prodi->nama }}
                            </td>
                            <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">
                                {{ $alumnus->updated_at->format('Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 bg-siakad-light/50 dark:bg-gray-700/50 rounded-xl flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-siakad-secondary dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <p class="text-siakad-secondary dark:text-gray-400 text-sm">Tidak ada data alumni yang cocok.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($alumni->hasPages())
    <div class="card-saas px-5 py-4 mt-4">
        {{ $alumni->appends(request()->query())->links() }}
    </div>
    @endif
</x-app-layout>
