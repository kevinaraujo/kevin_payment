<?php

namespace App\Services\Payment;

use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class ValidateTransaction
{
    private $transaction;
    private $api;

    public function __construct(Transaction $transaction)
    {
        $this->api = env('API_TRANSACTION_VALIDATION');
        $this->transaction = $transaction;
    }

    public function execute(): bool
    {
        $response = Http::post($this->api);

        if ($response->status() != Response::HTTP_OK) {
            throw new \Exception(
                sprintf('%s api error: %s', self::class, $response->body()),
                $response->status()
            );
        }

        return true;
    }
}
