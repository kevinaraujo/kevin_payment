<?php

namespace App\Services\Payment;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateUsersBalance
{
    private $transaction;
    private $payer;
    private $payee;
    private $revert;

    public function __construct(Transaction $transaction, bool $revert = false)
    {
        $this->transaction = $transaction;
        $this->revert = $revert;

        $this->payer = User::find($transaction->payer_id);
        $this->payee = User::find($transaction->payee_id);
    }

    public function execute(): bool
    {
        $payerBalance = $this->payer->balance - $this->transaction->value;
        $payeeBalance = $this->payee->balance + $this->transaction->value;

        if ($this->revert) {
            $payerBalance = $this->payer->balance + $this->transaction->value;
            $payeeBalance = $this->payee->balance - $this->transaction->value;
        }

        $this->payer->update(['balance' => $payerBalance]);
        $this->payee->update(['balance' => $payeeBalance]);

        return true;
    }
}
