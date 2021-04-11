<?php

namespace Tests\Unit\Services\Payment;

use App\Models\User;
use App\Services\Payment\CheckIfUsersExist;
use Illuminate\Http\Response;
use Tests\TestCase;

class CheckIfUsersExistTest extends TestCase
{
    public function testPayerDoesNotExistThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('PAYER_NOT_FOUND');
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $payerId = 0;
        $payee = User::where('email', 'shopkeeper@payment.com')->first();

        $check = new CheckIfUsersExist($payerId, $payee->id);
        $response = $check->execute();
    }

    public function testPayeeDoesNotExistThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('PAYEE_NOT_FOUND');
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $payer = User::where('email', 'client@payment.com')->first();
        $payeeId = 0;

        $check = new CheckIfUsersExist($payer->id, $payeeId);
        $response = $check->execute();
    }

    public function testPayerAndPayeeExistOk(): void
    {
        $payer = User::where('email', 'client@payment.com')->first();
        $payee = User::where('email', 'shopkeeper@payment.com')->first();

        $check = new CheckIfUsersExist($payer->id, $payee->id);
        $response = $check->execute();

        $this->assertIsArray($response);
        $this->assertEquals([$payer, $payee], $response);
    }
}
