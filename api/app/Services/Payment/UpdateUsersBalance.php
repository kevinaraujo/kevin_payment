<?php

namespace App\Services\Payment;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateUsersBalance
{
    private $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function execute(): bool
    {
        $payer = User::find($this->transaction->payer_id);
        $payee = User::find($this->transaction->payee_id);

        $payerBalance = $payer->balance - $this->transaction->value;
        $payeeBalance = $payee->balance + $this->transaction->value;

        $payer->update(['balance' => $payerBalance]);
        $payee->update(['balance' => $payeeBalance]);

        $this->transaction->update([
            'status' => Transaction::STATUS_SUCCESS
        ]);

        return true;
    }
}
