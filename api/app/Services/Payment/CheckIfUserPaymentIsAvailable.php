<?php

namespace App\Services\Payment;

use App\Models\User;
use App\Models\UserPayment;
use Illuminate\Http\Response;

class CheckIfUserPaymentIsAvailable
{
    private $payer;
    private $paymentTypeId;

    public function __construct(User $payer, int $paymentTypeId)
    {
        $this->payer = $payer;
        $this->paymentTypeId = $paymentTypeId;
    }

    public function execute(): bool
    {
        $userType = UserPayment::query()
            ->where('user_id', $this->payer->id)
            ->where('payment_type_id', $this->paymentTypeId)
            ->first();

        if (!$userType) {
            throw new \Exception(
                'PAYMENT_TYPE_NOT_FOUND',
                Response::HTTP_NOT_FOUND
            );
        }

        return true;
    }
}
