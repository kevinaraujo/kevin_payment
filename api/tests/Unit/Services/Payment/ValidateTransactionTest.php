<?php

namespace Tests\Unit\Services\Payment;

use App\Models\Transaction;
use App\Services\Payment\ValidateTransaction;
use PHPUnit\Framework\TestCase;

class ValidateTransactionTest extends TestCase
{
    public function testValidateOk() : void
    {
        $transaction = new Transaction();

        $validationTransaction = new ValidateTransaction($transaction);
        $response = $validationTransaction->execute();

        $this->assertTrue($response);
    }
}
