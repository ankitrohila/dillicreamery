<?php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Dilli Creamery', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Delhi\'s Finest Dairy', 'type' => 'text', 'group' => 'general'],
            ['key' => 'contact_phone', 'value' => '+91 99999 00000', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_email', 'value' => 'hello@dillicreamery.in', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'address', 'value' => 'Delhi, India', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'whatsapp_number', 'value' => '+91 99999 00000', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'delivery_charge', 'value' => '50', 'type' => 'text', 'group' => 'ecommerce'],
            ['key' => 'free_delivery_above', 'value' => '500', 'type' => 'text', 'group' => 'ecommerce'],
            ['key' => 'razorpay_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'payment'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/dillicreamery', 'type' => 'text', 'group' => 'social'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/dillicreamery', 'type' => 'text', 'group' => 'social'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
