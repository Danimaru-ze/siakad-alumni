<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Get all settings as key-value pairs
        $settings = Setting::all()->pluck('value', 'key');
        
        return view('superadmin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_title' => 'required|string|max:255',
            'site_subtitle' => 'nullable|string|max:255',
            'sidebar_title' => 'required|string|max:255',
            'website_description' => 'nullable|string|max:500',
            'university_logo' => 'nullable|image|max:2048',
            'login_title' => 'required|string|max:255',
            'login_subtitle' => 'required|string|max:255',
            'footer_text' => 'required|string|max:255',
        ], [
            'university_logo.image' => 'File harus berupa gambar (jpeg, png, jpg, gif, svg, atau webp).',
            'university_logo.max' => 'Ukuran logo maksimal 2MB.',
        ]);

        // Handle Text Fields
        $fields = ['site_title', 'site_subtitle', 'sidebar_title', 'website_description', 'login_title', 'login_subtitle', 'footer_text'];
        foreach ($fields as $field) {
            Setting::updateOrCreate(
                ['key' => $field],
                ['value' => $request->input($field)]
            );
        }

        // Handle Logo Upload
        if ($request->hasFile('university_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::where('key', 'university_logo')->value('value');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Simple storage call which handles unique naming automatically
            $path = $request->file('university_logo')->store('settings', 'public');

            Setting::updateOrCreate(
                ['key' => 'university_logo'],
                ['value' => $path]
            );
        }

        return redirect()->back()->with('success', 'Pengaturan website berhasil diperbarui.');
    }

    public function reset()
    {
        // Define default settings
        $defaults = [
            'site_title' => 'SIAKAD',
            'site_subtitle' => 'Academic Management',
            'sidebar_title' => 'SIAKAD',
            'website_description' => 'Sistem Informasi Akademik Terpadu',
            'login_title' => 'SIAKAD Academic Management',
            'login_subtitle' => 'Platform manajemen akademik modern berbasis AI untuk efisiensi dan transparansi pendidikan tinggi',
            'footer_text' => '© ' . date('Y') . ' SIAKAD. All rights reserved.',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Reset logo (delete custom logo file if exists but keep the default handling in view)
        $oldLogo = Setting::where('key', 'university_logo')->value('value');
        if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }
        Setting::where('key', 'university_logo')->delete();

        return redirect()->back()->with('success', 'Pengaturan website berhasil di-reset ke default.');
    }
}