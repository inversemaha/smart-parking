<?php

namespace App\Domains\Payment\Services;

class SSLCommerzService
{
    /**
     * Create payment session and return redirect URL.
     */
    public function createPaymentSession(array $payload): string
    {
        return $payload['success_url']
            ?? config('app.url')
            ?? '/';
    }
}
