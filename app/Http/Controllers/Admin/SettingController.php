<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct(private AuditLogService $auditLogService) {}

    public function edit()
    {
        $keys = [
            'store_name', 'store_description', 'store_phone', 'store_email', 'store_address',
            'bank_account_name', 'bank_account_number', 'bank_name', 'qris_image_path',
        ];

        $settings = collect($keys)->mapWithKeys(fn ($k) => [$k => Setting::get($k)]);

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'store_description' => ['nullable', 'string'],
            'store_phone' => ['nullable', 'string', 'max:30'],
            'store_email' => ['nullable', 'email'],
            'store_address' => ['nullable', 'string'],
            'bank_account_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'qris_image' => ['nullable', 'image', 'max:2048'],
        ]);

        foreach ($validated as $key => $value) {
            if ($key === 'qris_image') {
                continue;
            }
            Setting::set($key, $value);
        }

        if ($request->hasFile('qris_image')) {
            $old = Setting::get('qris_image_path');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('qris_image')->store('settings', 'public');
            Setting::set('qris_image_path', $path);
        }

        $this->auditLogService->log('update_settings');

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
