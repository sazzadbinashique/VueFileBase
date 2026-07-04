<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Future Bridge Foundation', 'type' => 'text', 'group' => 'site'],
            ['key' => 'site_description', 'value' => 'Building bridges between hope and opportunity across Bangladesh.', 'type' => 'textarea', 'group' => 'site'],
            ['key' => 'logo_url', 'value' => '', 'type' => 'text', 'group' => 'site'],
            ['key' => 'favicon_url', 'value' => '', 'type' => 'text', 'group' => 'site'],
            ['key' => 'address', 'value' => 'Dhaka, Bangladesh', 'type' => 'textarea', 'group' => 'site'],
            ['key' => 'phone', 'value' => '', 'type' => 'text', 'group' => 'site'],
            ['key' => 'email', 'value' => 'info@futurebridge.com', 'type' => 'text', 'group' => 'site'],

            ['key' => 'stripe_key', 'value' => '', 'type' => 'password', 'group' => 'payment'],
            ['key' => 'stripe_secret', 'value' => '', 'type' => 'password', 'group' => 'payment'],
            ['key' => 'stripe_webhook_secret', 'value' => '', 'type' => 'password', 'group' => 'payment'],
            ['key' => 'sslcommerz_sandbox', 'value' => '1', 'type' => 'boolean', 'group' => 'payment'],
            ['key' => 'sslcommerz_store_id', 'value' => '', 'type' => 'password', 'group' => 'payment'],
            ['key' => 'sslcommerz_store_password', 'value' => '', 'type' => 'password', 'group' => 'payment'],

            ['key' => 'mail_mailer', 'value' => 'log', 'type' => 'select', 'group' => 'mail'],
            ['key' => 'mail_host', 'value' => '', 'type' => 'text', 'group' => 'mail'],
            ['key' => 'mail_port', 'value' => '', 'type' => 'text', 'group' => 'mail'],
            ['key' => 'mail_username', 'value' => '', 'type' => 'text', 'group' => 'mail'],
            ['key' => 'mail_password', 'value' => '', 'type' => 'password', 'group' => 'mail'],
            ['key' => 'mail_encryption', 'value' => '', 'type' => 'select', 'group' => 'mail'],
            ['key' => 'mail_from_address', 'value' => '', 'type' => 'text', 'group' => 'mail'],
            ['key' => 'mail_from_name', 'value' => '', 'type' => 'text', 'group' => 'mail'],
        ];

        foreach ($settings as $s) {
            Setting::firstOrCreate(
                ['key' => $s['key']],
                $s
            );
        }
    }
}
