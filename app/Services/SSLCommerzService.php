<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SSLCommerzService
{
    protected string $initUrl;
    protected string $validationUrl;
    protected string $storeId;
    protected string $storePass;

    public function __construct()
    {
        $config = config('sslcommerz');
        $env = $config['sandbox'] ? 'sandbox' : 'live';
        $this->initUrl = $config['urls'][$env]['init'];
        $this->validationUrl = $config['urls'][$env]['validation'];
        $this->storeId = $config['store_id'];
        $this->storePass = $config['store_pass'];
    }

    public function initiate(array $data): array
    {
        $payload = array_merge([
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePass,
            'total_amount' => $data['amount'],
            'currency' => config('sslcommerz.currency', 'BDT'),
            'tran_id' => $data['transaction_id'],
            'success_url' => config('sslcommerz.success_url'),
            'cancel_url' => config('sslcommerz.cancel_url'),
            'fail_url' => config('sslcommerz.fail_url'),
            'ipn_url' => config('sslcommerz.ipn_url'),
            'cus_name' => $data['cus_name'] ?? 'Donor',
            'cus_email' => $data['cus_email'] ?? '',
            'cus_phone' => $data['cus_phone'] ?? '',
            'cus_add1' => $data['cus_add1'] ?? '',
            'cus_city' => $data['cus_city'] ?? '',
            'cus_country' => $data['cus_country'] ?? 'Bangladesh',
            'product_name' => $data['product_name'] ?? 'Donation',
            'product_category' => $data['product_category'] ?? 'Donation',
            'product_profile' => 'general',
            'shipping_method' => 'NO',
            'num_of_item' => 1,
        ], $data);

        $response = Http::asForm()->post($this->initUrl, $payload);

        return $response->json();
    }

    public function validate(string $valId): array
    {
        $url = $this->validationUrl
            . '?val_id=' . $valId
            . '&store_id=' . $this->storeId
            . '&store_passwd=' . $this->storePass
            . '&v=1&format=json';

        $response = Http::get($url);
        return $response->json();
    }
}
