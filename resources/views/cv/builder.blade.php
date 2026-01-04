<x-app-layout>
    <x-slot name="header">
        CV Builder (ATS Friendly)
    </x-slot>

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-siakad-dark dark:text-white">Build Your CV</h2>
            <p class="text-sm text-siakad-secondary dark:text-gray-400 mt-1">Lengkapi profil profesional Anda untuk membuat CV standar internasional.</p>
        </div>
        
        <div class="flex items-center gap-3 bg-white dark:bg-gray-800 p-2 rounded-xl shadow-sm border border-siakad-light dark:border-gray-700">
            <form action="{{ route('alumni.cv-builder.generate') }}" method="GET" target="_blank" class="flex items-center gap-2">
                <div class="relative">
                    <select name="template" class="appearance-none bg-siakad-light/30 dark:bg-gray-700 border-none text-siakad-dark dark:text-white text-sm rounded-lg py-2 pl-3 pr-8 focus:ring-2 focus:ring-siakad-primary cursor-pointer font-medium">
                        <option value="harvard">Harvard Style</option>
                        {{-- <option value="oxford">Oxford Style</option> --}}
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-siakad-secondary">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download PDF
                </button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 px-4 py-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl text-sm font-medium flex items-center gap-2 animate-fade-in">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-xl text-sm font-medium animate-fade-in">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div x-data="{ activeTab: 'profile' }" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-3">
            <div class="card-saas overflow-hidden sticky top-24">
                <nav class="flex flex-col p-2 space-y-1">
                    <button @click="activeTab = 'profile'" 
                        :class="activeTab === 'profile' ? 'bg-siakad-primary text-white shadow-md' : 'text-siakad-secondary hover:bg-siakad-light/50 dark:hover:bg-gray-700 dark:text-gray-400'"
                        class="text-left px-4 py-3 rounded-lg transition-all duration-200 flex items-center gap-3 font-medium text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profil & Summary
                    </button>
                    <button @click="activeTab = 'experience'" 
                        :class="activeTab === 'experience' ? 'bg-siakad-primary text-white shadow-md' : 'text-siakad-secondary hover:bg-siakad-light/50 dark:hover:bg-gray-700 dark:text-gray-400'"
                        class="text-left px-4 py-3 rounded-lg transition-all duration-200 flex items-center gap-3 font-medium text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Pengalaman Kerja
                    </button>
                    <button @click="activeTab = 'education'" 
                        :class="activeTab === 'education' ? 'bg-siakad-primary text-white shadow-md' : 'text-siakad-secondary hover:bg-siakad-light/50 dark:hover:bg-gray-700 dark:text-gray-400'"
                        class="text-left px-4 py-3 rounded-lg transition-all duration-200 flex items-center gap-3 font-medium text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.083 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.083 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v5m0 0v5m0-5h5m-5 0H7"></path></svg>
                        Pendidikan
                    </button>
                    <button @click="activeTab = 'skill'" 
                        :class="activeTab === 'skill' ? 'bg-siakad-primary text-white shadow-md' : 'text-siakad-secondary hover:bg-siakad-light/50 dark:hover:bg-gray-700 dark:text-gray-400'"
                        class="text-left px-4 py-3 rounded-lg transition-all duration-200 flex items-center gap-3 font-medium text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Keahlian (Skills)
                    </button>
                    <button @click="activeTab = 'personal'" 
                        :class="activeTab === 'personal' ? 'bg-siakad-primary text-white shadow-md' : 'text-siakad-secondary hover:bg-siakad-light/50 dark:hover:bg-gray-700 dark:text-gray-400'"
                        class="text-left px-4 py-3 rounded-lg transition-all duration-200 flex items-center gap-3 font-medium text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Data Pribadi
                    </button>
                </nav>
            </div>
        </div>

        <!-- Content Area -->
        <div class="lg:col-span-9">
            
            <!-- PROFILE TAB -->
            <div x-show="activeTab === 'profile'" class="card-saas p-6 md:p-8 animate-fade-in">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-siakad-light dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-bold text-siakad-dark dark:text-white">Profil Profesional</h3>
                        <p class="text-sm text-siakad-secondary dark:text-gray-400">Informasi dasar untuk header CV Anda.</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>

                <form action="{{ route('alumni.cv-builder.profile.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Read Only Fields -->
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Nama Lengkap</label>
                            <input type="text" value="{{ $user->name }}" class="w-full rounded-lg border-siakad-light bg-gray-50 text-gray-500 cursor-not-allowed text-sm font-medium dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400" disabled>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Email</label>
                            <input type="text" value="{{ $user->email }}" class="w-full rounded-lg border-siakad-light bg-gray-50 text-gray-500 cursor-not-allowed text-sm font-medium dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400" disabled>
                        </div>

                        <!-- Editable Fields -->
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Nomor Telepon</label>
                            <input type="text" name="phone_number" value="{{ $cvProfile->phone_number ?? '' }}" class="input-saas w-full text-sm" placeholder="+62...">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Lokasi / Alamat Singkat</label>
                            <input type="text" name="address" value="{{ $cvProfile->address ?? '' }}" class="input-saas w-full text-sm" placeholder="Jakarta, Indonesia">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">LinkedIn URL</label>
                            <input type="url" name="linkedin_url" value="{{ $cvProfile->linkedin_url ?? '' }}" class="input-saas w-full text-sm" placeholder="https://linkedin.com/in/...">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Portfolio URL</label>
                            <input type="url" name="portfolio_url" value="{{ $cvProfile->portfolio_url ?? '' }}" class="input-saas w-full text-sm" placeholder="https://myportfolio.com">
                        </div>
                    </div>

                    <div class="space-y-2 mb-8">
                        <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Professional Summary</label>
                        <textarea name="professional_summary" rows="5" class="input-saas w-full text-sm" placeholder="Tuliskan ringkasan singkat tentang diri Anda, keahlian utama, dan tujuan karir secara profesional...">{{ $cvProfile->professional_summary ?? '' }}</textarea>
                        <p class="text-xs text-gray-400 text-right">Disarankan 2-3 kalimat yang kuat.</p>
                    </div>

                    <div class="pt-6 border-t border-siakad-light dark:border-gray-700 flex justify-end">
                        <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-lg text-sm font-semibold shadow-lg shadow-siakad-primary/20">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <!-- EXPERIENCE TAB -->
            <div x-show="activeTab === 'experience'" x-transition class="space-y-6" style="display: none;">
                <div class="card-saas p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-siakad-light dark:border-gray-700">
                        <div>
                            <h3 class="text-lg font-bold text-siakad-dark dark:text-white">Pengalaman Kerja</h3>
                            <p class="text-sm text-siakad-secondary dark:text-gray-400">Magang, Full-time, atau Part-time.</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>

                    <!-- List Experience -->
                    <div class="space-y-4 mb-8">
                        @forelse($experiences as $exp)
                            <div class="group relative bg-white dark:bg-gray-800 border border-siakad-light dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition-all duration-200">
                                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <form action="{{ route('alumni.cv-builder.experience.destroy', $exp->id) }}" method="POST" onsubmit="return confirm('Hapus pengalaman ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 flex-shrink-0 font-bold text-lg uppercase">
                                        {{ substr($exp->company, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start">
                                            <div>
                                                <h4 class="font-bold text-siakad-dark dark:text-white text-base">{{ $exp->role }}</h4>
                                                <p class="text-sm font-medium text-siakad-primary dark:text-blue-400">{{ $exp->company }}</p>
                                            </div>
                                            <div class="mt-1 sm:mt-0">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    {{ $exp->start_date->format('M Y') }} - 
                                                    {{ $exp->is_current ? 'Present' : ($exp->end_date ? $exp->end_date->format('M Y') : '-') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $exp->location }}
                                        </div>
                                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line leading-relaxed">{{ $exp->description }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada pengalaman</h3>
                                <p class="mt-1 text-sm text-gray-500">Mulai tambahkan riwayat karir Anda.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Add Experience Form -->
                    <div x-data="{ adding: false }">
                        <button x-show="!adding" @click="adding = true" class="w-full py-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-500 hover:border-siakad-primary hover:text-siakad-primary transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Pengalaman Baru
                        </button>

                        <div x-show="adding" x-transition class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6 border border-siakad-light dark:border-gray-700">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-bold text-siakad-dark dark:text-white">Form Pengalaman</h4>
                                <button @click="adding = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                            </div>
                            <form action="{{ route('alumni.cv-builder.experience.store') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="space-y-1">
                                        <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Posisi / Role</label>
                                        <input type="text" name="role" required class="input-saas w-full text-sm" placeholder="e.g. Software Engineer">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Perusahaan</label>
                                        <input type="text" name="company" required class="input-saas w-full text-sm" placeholder="e.g. PT Teknologi Maju">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Lokasi</label>
                                        <input type="text" name="location" class="input-saas w-full text-sm" placeholder="e.g. Jakarta">
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="space-y-1">
                                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Mulai</label>
                                            <input type="date" name="start_date" required class="input-saas w-full text-sm">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Selesai</label>
                                            <input type="date" name="end_date" class="input-saas w-full text-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4 flex items-center">
                                    <input type="checkbox" name="is_current" id="is_current" class="rounded border-gray-300 text-siakad-primary focus:ring-siakad-primary">
                                    <label for="is_current" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Saya masih bekerja di sini</label>
                                </div>
                                <div class="mb-4 space-y-1">
                                    <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Deskripsi</label>
                                    <textarea name="description" rows="3" required class="input-saas w-full text-sm" placeholder="• Jelaskan tanggung jawab dan pencapaian Anda..."></textarea>
                                </div>
                                <div class="flex justify-end gap-3">
                                    <button type="button" @click="adding = false" class="btn-ghost-saas px-4 py-2 rounded-lg text-sm font-medium">Batal</button>
                                    <button type="submit" class="btn-primary-saas px-6 py-2 rounded-lg text-sm font-medium">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EDUCATION TAB -->
            <div x-show="activeTab === 'education'" x-transition class="space-y-6" style="display: none;">
                <div class="card-saas p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-siakad-light dark:border-gray-700">
                        <div>
                            <h3 class="text-lg font-bold text-siakad-dark dark:text-white">Riwayat Pendidikan</h3>
                            <p class="text-sm text-siakad-secondary dark:text-gray-400">Pendidikan formal dari universitas atau sekolah.</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.083 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.083 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v5m0 0v5m0-5h5m-5 0H7"></path></svg>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 mb-8 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    <span class="font-bold">Info:</span> Data pendidikan Anda di {{ config('app.name') }} sudah otomatis tersinkronisasi. Gunakan form di bawah ini hanya untuk menambahkan pendidikan sebelumnya (SMA/D3) atau jenjang lanjut di kampus lain.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- List Education -->
                    <div class="space-y-4 mb-8">
                        @forelse($educations as $edu)
                            <div class="group relative bg-white dark:bg-gray-800 border border-siakad-light dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition-all duration-200">
                                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <form action="{{ route('alumni.cv-builder.education.destroy', $edu->id) }}" method="POST" onsubmit="return confirm('Hapus pendidikan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 flex-shrink-0 font-bold text-lg uppercase">
                                        {{ substr($edu->institution, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start">
                                            <div>
                                                <h4 class="font-bold text-siakad-dark dark:text-white text-base">{{ $edu->institution }}</h4>
                                                <p class="text-sm font-medium text-siakad-primary dark:text-blue-400">
                                                    {{ $edu->degree }} 
                                                    @if($edu->field_of_study) <span class="text-gray-400 mx-1">•</span> {{ $edu->field_of_study }} @endif
                                                </p>
                                            </div>
                                            <div class="mt-1 sm:mt-0">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    {{ $edu->start_date->format('Y') }} - 
                                                    {{ $edu->is_current ? 'Present' : ($edu->end_date ? $edu->end_date->format('Y') : '-') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $edu->location }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">Belum ada pendidikan tambahan</h3>
                            </div>
                        @endforelse
                    </div>

                    <!-- Add Education Form -->
                    <div x-data="{ adding: false }">
                        <button x-show="!adding" @click="adding = true" class="w-full py-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-500 hover:border-siakad-primary hover:text-siakad-primary transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Pendidikan
                        </button>

                        <div x-show="adding" x-transition class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6 border border-siakad-light dark:border-gray-700">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-bold text-siakad-dark dark:text-white">Form Pendidikan</h4>
                                <button @click="adding = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                            </div>
                            <form action="{{ route('alumni.cv-builder.education.store') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="space-y-1">
                                        <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Institusi / Universitas</label>
                                        <input type="text" name="institution" required class="input-saas w-full text-sm" placeholder="e.g. SMA Negeri 1 Jakarta">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Gelar (Degree)</label>
                                        <input type="text" name="degree" required class="input-saas w-full text-sm" placeholder="e.g. SMA, Bachelor, Master">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Bidang Studi</label>
                                        <input type="text" name="field_of_study" class="input-saas w-full text-sm" placeholder="e.g. IPA, Computer Science">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Lokasi</label>
                                        <input type="text" name="location" class="input-saas w-full text-sm">
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="space-y-1">
                                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Mulai</label>
                                            <input type="date" name="start_date" required class="input-saas w-full text-sm">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Selesai</label>
                                            <input type="date" name="end_date" class="input-saas w-full text-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-3">
                                    <button type="button" @click="adding = false" class="btn-ghost-saas px-4 py-2 rounded-lg text-sm font-medium">Batal</button>
                                    <button type="submit" class="btn-primary-saas px-6 py-2 rounded-lg text-sm font-medium">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SKILL TAB -->
            <div x-show="activeTab === 'skill'" x-transition class="space-y-6" style="display: none;">
                <div class="card-saas p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-siakad-light dark:border-gray-700">
                        <div>
                            <h3 class="text-lg font-bold text-siakad-dark dark:text-white">Keahlian (Skills)</h3>
                            <p class="text-sm text-siakad-secondary dark:text-gray-400">Hard skills, soft skills, bahasa, dan tools.</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                    </div>
                    
                    <!-- List Skills -->
                    <div class="flex flex-wrap gap-2 mb-8">
                        @forelse($skills as $skill)
                            <div class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-white dark:bg-gray-800 text-siakad-dark dark:text-white border border-siakad-light dark:border-gray-700 shadow-sm group hover:border-siakad-primary transition-colors">
                                <span class="mr-2 text-xs text-siakad-secondary">{{ $skill->category }}:</span>
                                {{ $skill->name }}
                                <form action="{{ route('alumni.cv-builder.skill.destroy', $skill->id) }}" method="POST" class="ml-2 inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 focus:outline-none opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center w-full py-8 text-gray-500">Belum ada skill ditambahkan.</div>
                        @endforelse
                    </div>

                    <!-- Add Skill Form -->
                    <div class="pt-6 border-t border-siakad-light dark:border-gray-700">
                        <h4 class="font-semibold text-siakad-dark dark:text-white mb-4 text-sm">Tambah Skill Baru</h4>
                        <form action="{{ route('alumni.cv-builder.skill.store') }}" method="POST" class="flex flex-col md:flex-row gap-4">
                            @csrf
                            <div class="flex-1">
                                <input type="text" name="name" required class="input-saas w-full text-sm" placeholder="Nama Skill (e.g. PHP, Public Speaking, English)">
                            </div>
                            <div class="w-full md:w-1/4">
                                <select name="category" class="input-saas w-full text-sm">
                                    <option value="Hard Skills">Hard Skills</option>
                                    <option value="Soft Skills">Soft Skills</option>
                                    <option value="Languages">Languages</option>
                                    <option value="Tools">Tools</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-primary-saas px-6 py-2 rounded-lg text-sm font-medium whitespace-nowrap">Tambah Skill</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- PERSONAL DATA TAB -->
            <div x-show="activeTab === 'personal'" x-transition class="card-saas p-6 md:p-8 animate-fade-in" style="display: none;">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-siakad-light dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-bold text-siakad-dark dark:text-white">Data Pribadi</h3>
                        <p class="text-sm text-siakad-secondary dark:text-gray-400">Informasi personal yang akan ditampilkan di CV.</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <form action="{{ route('alumni.cv-builder.profile.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Nama Lengkap</label>
                            <input type="text" value="{{ $user->name }}" class="w-full rounded-lg border-siakad-light bg-gray-50 text-gray-500 cursor-not-allowed text-sm font-medium dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400" disabled>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Email</label>
                            <input type="text" value="{{ $user->email }}" class="w-full rounded-lg border-siakad-light bg-gray-50 text-gray-500 cursor-not-allowed text-sm font-medium dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400" disabled>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Nomor Telepon</label>
                            <input type="text" name="phone_number" value="{{ $cvProfile->phone_number ?? '' }}" class="input-saas w-full text-sm" placeholder="+62...">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-siakad-secondary uppercase tracking-wider">Alamat</label>
                            <input type="text" name="address" value="{{ $cvProfile->address ?? '' }}" class="input-saas w-full text-sm" placeholder="Jalan, Kota, Provinsi">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-siakad-light dark:border-gray-700 flex justify-end">
                        <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-lg text-sm font-semibold shadow-lg shadow-siakad-primary/20">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
