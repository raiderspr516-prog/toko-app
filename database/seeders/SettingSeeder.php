<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'store_name' => 'TokoKu',
            'store_description' => 'Belanja online mudah dan terpercaya.',
            'store_phone' => '0800-0000-0000',
            'store_email' => 'cs@tokoku.test',
            'store_address' => 'Jl. Contoh No. 1, Jakarta',
            'bank_account_name' => 'PT TokoKu Indonesia',
            'bank_account_number' => '1234567890',
            'bank_name' => 'Bank Contoh',
            'qris_image_path' => null, // wajib diupload admin lewat menu Pengaturan
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
