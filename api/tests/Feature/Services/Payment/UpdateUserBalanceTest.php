<?php

namespace Tests\Feature\Services\Payment;

use App\Models\PaymentType;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPayment;
use App\Services\Payment\UpdateUsersBalance;
use Tests\TestCase;

class UpdateUserBalanceTest extends TestCase
{
    public function testTransferPayerToPayeeOk(): array
    {
        $payer = User::where('email', 'client@payment.com')->first();
        $payee = User::where('email', 'shopkeeper@payment.com')->first();
        $userPayment = UserPayment::where('user_id', $payer->id)->first();

        $value = 55;
        $expectedPayerBalance = $payer->balance - $value;
        $expectedPayeeBalance = $payee->balance + $value;

        $transaction = Transaction::create([
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'user_payment_id' => $userPayment->id,
            'value' => $value,
            'status' => Transaction::STATUS_PENDING,
            'description' => sprintf('Debt to %s', $payee->name)
        ]);

        $updateUserBalance = new UpdateUsersBalance($transaction);
        $response = $updateUserBalance->execute();

        $updatedPayer = User::where('email', 'client@payment.com')->first();
        $updatedPayee = User::where('email', 'shopkeeper@payment.com')->first();

        $this->assertEquals($expectedPayerBalance, $updatedPayer->balance);
        $this->assertEquals($expectedPayeeBalance, $updatedPayee->balance);

        return [
            'payer' => $updatedPayer,
            'payee' => $updatedPayee,
            'user_payment' => $userPayment
        ];
    }

    /**
     * @depends testTransferPayerToPayeeOk
     */
    public function testTransferPayerToPayeeRevertedOk(array $users): void
    {
        $payer = $users['payer'];
        $payee = $users['payee'];
        $userPayment = $users['user_payment'];

        $value = 60;
        $expectedPayerBalance = $payer->balance + $value;
        $expectedPayeeBalance = $payee->balance - $value;

        $transaction = Transaction::create([
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'user_payment_id' => $userPayment->id,
            'value' => $value,
            'status' => Transaction::STATUS_PENDING,
            'description' => sprintf('Debt to %s', $payee->name)
        ]);

        $updateUserBalance = new UpdateUsersBalance($transaction, true);
        $response = $updateUserBalance->execute();

        $updatedPayer = User::where('email', 'client@payment.com')->first();
        $updatedPayee = User::where('email', 'shopkeeper@payment.com')->first();

        $this->assertEquals($expectedPayerBalance, $updatedPayer->balance);
        $this->assertEquals($expectedPayeeBalance, $updatedPayee->balance);
    }
}
