<x-app-layout>
    <x-slot name="header">
        Profil Perusahaan
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Company Info Card -->
        <div class="lg:col-span-1 flex flex-col gap-6">
            <div class="card-saas p-6">
                <div class="text-center mb-6">
                    <div class="w-24 h-24 mx-auto rounded-xl bg-siakad-primary flex items-center justify-center text-white text-4xl font-bold mb-4">
                        {{ strtoupper(substr($hrdProfile->company->nama_perusahaan, 0, 1)) }}
                    </div>
                    <h2 class="text-xl font-semibold text-siakad-dark">{{ $hrdProfile->company->nama_perusahaan }}</h2>
                    <p class="text-sm text-siakad-secondary">{{ $hrdProfile->company->website ?? '-' }}</p>
                </div>

                <div class="space-y-3 pt-4 border-t border-siakad-light">
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-siakad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="text-siakad-dark">{{ $hrdProfile->company->email }}</span>
                    </div>
                    @if($hrdProfile->company->telepon)
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-siakad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.113L9.349 19.04a11.135 11.135 0 006.101 6.101l4.493-1.498a1 1 0 011.113-.502h4.493a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.113L9.349 19.04a11.135 11.135 0 006.101 6.101l4.493-1.498a1 1 0 011.113-.502h3.28a2 2 0 012 2V21a2 2 0 01-2 2h-1C9 23 3 17 3 9V5z"></path></svg>
                        <span class="text-siakad-dark">{{ $hrdProfile->company->telepon }}</span>
                    </div>
                    @endif
                    @if($hrdProfile->company->alamat)
                    <div class="flex items-start gap-3 text-sm">
                        <svg class="w-5 h-5 text-siakad-secondary mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="text-siakad-dark text-left">{{ $hrdProfile->company->alamat }}</span>
                    </div>
                    @endif
                    @if($hrdProfile->company->deskripsi)
                    <div class="flex items-start gap-3 text-sm">
                        <svg class="w-5 h-5 text-siakad-secondary mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="text-siakad-dark text-left">{{ \Illuminate\Support\Str::limit($hrdProfile->company->deskripsi, 150) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Update Company Info Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card-saas">
                <div class="px-6 py-4 border-b border-siakad-light">
                    <h3 class="font-semibold text-siakad-dark">Perbarui Informasi Perusahaan</h3>
                    <p class="text-xs text-siakad-secondary mt-1">
                        Informasi ini akan ditampilkan pada halaman detail lowongan kerja.
                    </p>
                </div>
                <form action="{{ route('hrd.company-profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="nama_perusahaan" class="block text-sm font-medium text-siakad-dark mb-2">Nama Perusahaan</label>
                                <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan', $hrdProfile->company->nama_perusahaan) }}" class="input-saas w-full" required>
                            </div>
                            <div>
                                <label for="email_perusahaan" class="block text-sm font-medium text-siakad-dark mb-2">Email Resmi Perusahaan</label>
                                <input type="email" name="email" id="email_perusahaan" value="{{ old('email', $hrdProfile->company->email) }}" class="input-saas w-full" required>
                            </div>
                            <div>
                                <label for="website" class="block text-sm font-medium text-siakad-dark mb-2">Website</label>
                                <input type="url" name="website" id="website" value="{{ old('website', $hrdProfile->company->website) }}" placeholder="https://..." class="input-saas w-full">
                            </div>
                            <div>
                                <label for="telepon_perusahaan" class="block text-sm font-medium text-siakad-dark mb-2">Telepon Perusahaan</label>
                                <input type="text" name="telepon" id="telepon_perusahaan" value="{{ old('telepon', $hrdProfile->company->telepon) }}" class="input-saas w-full">
                            </div>
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-siakad-dark mb-2">Alamat Perusahaan</label>
                                <textarea name="alamat" id="alamat" rows="3" class="input-saas w-full" required>{{ old('alamat', $hrdProfile->company->alamat) }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-siakad-dark mb-2">Deskripsi Singkat Perusahaan</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4" class="input-saas w-full" placeholder="Jelaskan secara singkat tentang perusahaan Anda...">{{ old('deskripsi', $hrdProfile->company->deskripsi) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-siakad-light flex justify-end">
                        <button type="submit" class="btn-primary-saas px-5 py-2.5 rounded-lg text-sm font-medium">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
