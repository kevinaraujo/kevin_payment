<?php

namespace Tests\Unit\Services\Payment;

use App\Models\User;
use App\Services\Payment\CheckIfPayerCanSendMoney;
use Faker\Factory;
use Illuminate\Http\Response;
use Tests\TestCase;

class CheckIfPayerCanSendMoneyTest extends TestCase
{
    public function testPayerIsTheSameAsPayeeThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('TRANSACTION_NOT_ALLOWED');
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $payer = User::where('email', 'client@payment.com')->first();
        $payee = $payer;

        $faker = Factory::create();
        $value = $faker->randomFloat(2,0, $payer->balance);

        $check = new CheckIfPayerCanSendMoney($payer, $payee, $value);
        $response = $check->execute();
    }

    public function testPayerIsNotAllowedProfileToSendMoneyThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('TRANSACTION_NOT_ALLOWED');
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $payer = User::where('email', 'shopkeeper@payment.com')->first();
        $payee = User::where('email', 'client@payment.com')->first();

        $faker = Factory::create();
        $value = $faker->randomFloat(2,0, $payer->balance);

        $check = new CheckIfPayerCanSendMoney($payer, $payee, $value);
        $response = $check->execute();
    }

    public function testPayerHasNotMoneyEnoughToSendThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('INSUFFICIENT_BALANCE');
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $payer = User::where('email', 'client@payment.com')->first();
        $payee = User::where('email', 'shopkeeper@payment.com')->first();

        $value = $payer->balance + 100;

        $check = new CheckIfPayerCanSendMoney($payer, $payee, $value);
        $response = $check->execute();
    }

    public function testPayerSendMoneyOk(): void
    {
        $payer = User::where('email', 'client@payment.com')->first();
        $payee = User::where('email', 'shopkeeper@payment.com')->first();

        $faker = Factory::create();
        $value = $faker->randomFloat(2,0, $payer->balance);

        $check = new CheckIfPayerCanSendMoney($payer, $payee, $value);
        $response = $check->execute();

        $this->assertTrue($response);
    }
}
