<x-app-layout>
    <x-slot name="header">
        Biodata Dosen
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1 flex flex-col gap-6">
            <div class="card-saas p-6">
                <div class="text-center mb-6">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ $user->name }}" class="w-24 h-24 mx-auto rounded-xl object-cover mb-4">
                    <h2 class="text-xl font-semibold text-siakad-dark">{{ $user->name }}</h2>
                    <p class="text-sm font-mono text-siakad-secondary">{{ $dosen->nidn }}</p>
                </div>

                <div class="space-y-3 pt-4 border-t border-siakad-light">
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-siakad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="text-siakad-dark">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-siakad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="text-siakad-dark">{{ $dosen->prodi->fakultas->nama ?? '-' }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-siakad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span class="text-siakad-dark">{{ $dosen->prodi->nama ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Update Profile -->
            <div class="card-saas">
                <div class="px-6 py-4 border-b border-siakad-light">
                    <h3 class="font-semibold text-siakad-dark">Informasi Akun</h3>
                    <p class="text-xs text-siakad-secondary mt-1">Perbarui informasi akun Anda</p>
                </div>
                <form action="{{ route('dosen.biodata.update') }}" method="POST" enctype="multipart/form-data" x-data="{photoName: null, photoPreview: null}">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <!-- Profile Photo -->
                        <div>
                            <label class="block text-sm font-medium text-siakad-dark mb-2">Foto Profil</label>
                            <div class="mt-1 flex items-center gap-4">
                                <span class="h-20 w-20 rounded-xl overflow-hidden bg-gray-100">
                                    <img x-show="!photoPreview" src="{{ Auth::user()->profile_photo_url }}" alt="Current Profile Photo" class="h-full w-full object-cover">
                                    <div x-show="photoPreview" class="h-full w-full bg-cover bg-no-repeat bg-center" x-bind:style="'background-image: url(\'' + photoPreview + '\');'"></div>
                                </span>
                                <input type="file" name="photo" class="hidden" x-ref="photo" x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                                ">
                                <button type="button" class="btn-secondary-saas px-4 py-2 text-sm" x-on:click.prevent="$refs.photo.click()">
                                    Ubah Foto
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-siakad-dark mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input-saas w-full px-4 py-2.5 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-siakad-dark mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-saas w-full px-4 py-2.5 text-sm" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-siakad-dark mb-2">NIDN</label>
                                <input type="text" value="{{ $dosen->nidn }}" class="input-saas w-full px-4 py-2.5 text-sm" style="opacity: 0.6;" readonly disabled>
                                <p class="text-xs text-siakad-secondary mt-1">NIDN tidak dapat diubah</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-siakad-dark mb-2">Program Studi</label>
                                <input type="text" value="{{ $dosen->prodi->nama ?? '-' }}" class="input-saas w-full px-4 py-2.5 text-sm" style="opacity: 0.6;" readonly disabled>
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

            <!-- Change Password -->
            <div class="card-saas">
                <div class="px-6 py-4 border-b border-siakad-light">
                    <h3 class="font-semibold text-siakad-dark">Ubah Password</h3>
                    <p class="text-xs text-siakad-secondary mt-1">Pastikan menggunakan password yang kuat</p>
                </div>
                <form action="{{ route('dosen.biodata.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-siakad-dark mb-2">Password Lama</label>
                            <input type="password" name="current_password" class="input-saas w-full px-4 py-2.5 text-sm" required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-siakad-dark mb-2">Password Baru</label>
                                <input type="password" name="password" class="input-saas w-full px-4 py-2.5 text-sm" required minlength="8">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-siakad-dark mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="input-saas w-full px-4 py-2.5 text-sm" required>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-siakad-light flex justify-end">
                        <button type="submit" class="btn-primary-saas px-5 py-2.5 rounded-lg text-sm font-medium">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
