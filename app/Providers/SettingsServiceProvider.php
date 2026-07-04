<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        try {
            $settings = Setting::pluck('value', 'key')->toArray();

            if (isset($settings['stripe_key'])) {
                config(['stripe.key' => $settings['stripe_key']]);
            }
            if (isset($settings['stripe_secret'])) {
                config(['stripe.secret' => $settings['stripe_secret']]);
            }
            if (isset($settings['stripe_webhook_secret'])) {
                config(['stripe.webhook_secret' => $settings['stripe_webhook_secret']]);
            }

            if (isset($settings['sslcommerz_store_id'])) {
                config(['sslcommerz.store_id' => $settings['sslcommerz_store_id']]);
            }
            if (isset($settings['sslcommerz_store_password'])) {
                config(['sslcommerz.store_pass' => $settings['sslcommerz_store_password']]);
            }
            if (isset($settings['sslcommerz_sandbox'])) {
                config(['sslcommerz.sandbox' => filter_var($settings['sslcommerz_sandbox'], FILTER_VALIDATE_BOOLEAN)]);
            }

            if (isset($settings['mail_mailer'])) {
                config(['mail.default' => $settings['mail_mailer']]);
            }
            if (isset($settings['mail_host'])) {
                config(['mail.mailers.smtp.host' => $settings['mail_host']]);
            }
            if (isset($settings['mail_port'])) {
                config(['mail.mailers.smtp.port' => (int) $settings['mail_port']]);
            }
            if (isset($settings['mail_username'])) {
                config(['mail.mailers.smtp.username' => $settings['mail_username']]);
            }
            if (isset($settings['mail_password'])) {
                config(['mail.mailers.smtp.password' => $settings['mail_password']]);
            }
            if (isset($settings['mail_encryption'])) {
                config(['mail.mailers.smtp.encryption' => $settings['mail_encryption'] ?: null]);
            }
            if (isset($settings['mail_from_address'])) {
                config(['mail.from.address' => $settings['mail_from_address']]);
            }
            if (isset($settings['mail_from_name'])) {
                config(['mail.from.name' => $settings['mail_from_name']]);
            }
        } catch (\Exception $e) {
            //
        }
    }
}
