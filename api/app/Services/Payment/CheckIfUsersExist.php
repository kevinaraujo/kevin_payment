<?php

namespace App\Services\Payment;

use App\Models\User;
use Illuminate\Http\Response;

class CheckIfUsersExist
{
    private $payer;
    private $payee;

    public function __construct(int $payerId, int $payeeId)
    {
        $this->payer = User::find($payerId);
        $this->payee = User::find($payeeId);
    }

    public function execute(): array
    {
        if (!$this->payer) {
            throw new \Exception(
                'PAYER_NOT_FOUND',
                Response::HTTP_NOT_FOUND
            );
        }

        if (!$this->payee) {
            throw new \Exception(
                'PAYEE_NOT_FOUND',
                Response::HTTP_NOT_FOUND
            );
        }

        return [
            $this->payer,
            $this->payee
        ];
    }
}
