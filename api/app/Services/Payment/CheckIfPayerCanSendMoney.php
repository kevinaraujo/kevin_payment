<?php

namespace App\Services\Payment;

use App\Models\User;
use Illuminate\Http\Response;

class CheckIfPayerCanSendMoney
{
    private $payer;
    private $payee;
    private $value;

    public function __construct(User $payer, User $payee, float $value)
    {
        $this->payer = $payer;
        $this->payee = $payee;
        $this->value = $value;
    }

    public function execute(): bool
    {
        if (
            $this->payee->id == $this->payer->id ||
            !$this->payer->types->sends_money
        ) {
            throw new \Exception(
                'TRANSACTION_NOT_ALLOWED',
                Response::HTTP_FORBIDDEN
            );
        }

        if ($this->payer->balance < $this->value) {
            throw new \Exception(
                'INSUFFICIENT_BALANCE',
                Response::HTTP_BAD_REQUEST
            );
        }

        return true;
    }
}
