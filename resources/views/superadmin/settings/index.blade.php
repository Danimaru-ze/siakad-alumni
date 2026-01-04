<x-app-layout>
    <x-slot name="header">
        Pengaturan Website
    </x-slot>

    <!-- Messages -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-medium">
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm text-siakad-secondary dark:text-gray-400">Kelola identitas visual dan konfigurasi dasar sistem.</p>
        </div>
    </div>

    <div class="card-saas p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ route('superadmin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Logo Section -->
            <div class="border-b border-gray-100 dark:border-gray-700 pb-8">
                <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-6">Logo & Branding</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-siakad-secondary dark:text-gray-400 mb-2">Logo Universitas</label>
                        <div class="flex items-start gap-4">
                            <div class="w-24 h-24 bg-gray-50 dark:bg-gray-700 rounded-lg border border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if(isset($settings['university_logo']))
                                    <img src="{{ asset('storage/' . $settings['university_logo']) }}" alt="Preview" class="w-full h-full object-contain p-2" id="logo-preview">
                                @else
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="university_logo" id="university_logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-siakad-primary file:text-white hover:file:bg-siakad-dark cursor-pointer mb-2" accept="image/jpeg,image/png,image/gif,image/svg+xml" onchange="previewImage(this)">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Format: PNG, JPG, SVG. Maks 2MB. Disarankan rasio 1:1.</p>
                                @error('university_logo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identity Section -->
            <div class="border-b border-gray-100 dark:border-gray-700 pb-8">
                <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-6">Identitas Umum</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="site_title" class="block text-sm font-medium text-siakad-secondary dark:text-gray-400 mb-1">Judul Website (Frontend)</label>
                        <input type="text" name="site_title" id="site_title" 
                            value="{{ old('site_title', $settings['site_title'] ?? config('app.name')) }}"
                            class="input-saas w-full"
                            placeholder="Contoh: Universitas Indonesia">
                        @error('site_title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="site_subtitle" class="block text-sm font-medium text-siakad-secondary dark:text-gray-400 mb-1">Subtitle Website</label>
                        <input type="text" name="site_subtitle" id="site_subtitle" 
                            value="{{ old('site_subtitle', $settings['site_subtitle'] ?? 'Academic Management') }}"
                            class="input-saas w-full"
                            placeholder="Contoh: Academic Management">
                        @error('site_subtitle') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="sidebar_title" class="block text-sm font-medium text-siakad-secondary dark:text-gray-400 mb-1">Judul Sidebar</label>
                        <input type="text" name="sidebar_title" id="sidebar_title" 
                            value="{{ old('sidebar_title', $settings['sidebar_title'] ?? 'SIAKAD') }}"
                            class="input-saas w-full"
                            placeholder="Contoh: SIAKAD">
                        @error('sidebar_title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="website_description" class="block text-sm font-medium text-siakad-secondary dark:text-gray-400 mb-1">Deskripsi Website</label>
                        <textarea name="website_description" id="website_description" rows="2"
                            class="input-saas w-full resize-none"
                            placeholder="Deskripsi singkat untuk meta tags...">{{ old('website_description', $settings['website_description'] ?? '') }}</textarea>
                        @error('website_description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Login Page Section -->
            <div class="border-b border-gray-100 dark:border-gray-700 pb-8">
                <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-6">Halaman Login</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="login_title" class="block text-sm font-medium text-siakad-secondary dark:text-gray-400 mb-1">Judul Halaman Login</label>
                        <input type="text" name="login_title" id="login_title" 
                            value="{{ old('login_title', $settings['login_title'] ?? 'SIAKAD Academic Management') }}"
                            class="input-saas w-full">
                        @error('login_title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="login_subtitle" class="block text-sm font-medium text-siakad-secondary dark:text-gray-400 mb-1">Slogan Login</label>
                        <textarea name="login_subtitle" id="login_subtitle" rows="2"
                            class="input-saas w-full resize-none">{{ old('login_subtitle', $settings['login_subtitle'] ?? '') }}</textarea>
                        @error('login_subtitle') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Footer Section -->
            <div>
                <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-6">Footer</h3>
                <div>
                    <label for="footer_text" class="block text-sm font-medium text-siakad-secondary dark:text-gray-400 mb-1">Teks Copyright</label>
                    <input type="text" name="footer_text" id="footer_text" 
                        value="{{ old('footer_text', $settings['footer_text'] ?? '© 2026 SIAKAD. All rights reserved.') }}"
                        class="input-saas w-full">
                    @error('footer_text') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-gray-700">
                <!-- Reset Button -->
                <button type="button" onclick="if(confirm('Reset semua pengaturan ke default?')) document.getElementById('reset-form').submit()" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                    Reset Default
                </button>

                <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-lg text-sm font-medium shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
        
        <form id="reset-form" action="{{ route('superadmin.settings.reset') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('logo-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        // Replace svg with img
                        const container = input.parentElement.previousElementSibling;
                        container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain p-2" id="logo-preview">`;
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>