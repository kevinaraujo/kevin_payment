<?php

namespace Tests\Unit\Services\Payment;

use App\Models\User;
use App\Services\Payment\CheckIfUserPaymentIsAvailable;
use Faker\Factory;
use Illuminate\Http\Response;
use Tests\TestCase;

class CheckIfUserPaymentIsAvailableTest extends TestCase
{
    public function testInvalidUserPaymentIdThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('PAYMENT_TYPE_NOT_FOUND');
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $payer = User::where('email', 'client@payment.com')->first();

        $faker = Factory::create();
        $paymentTypeId = $faker->numberBetween(10, 20);

        $check = new CheckIfUserPaymentIsAvailable($payer, $paymentTypeId);
        $response = $check->execute();
    }

    public function testValidUserPaymentIdOk(): void
    {
        $payer = User::where('email', 'client@payment.com')->first();

        $paymentTypeId = $payer->userPayments->first()->paymentType->id;

        $check = new CheckIfUserPaymentIsAvailable($payer, $paymentTypeId);
        $response = $check->execute();

        $this->assertTrue($response);
    }

}
