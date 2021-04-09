<?php

namespace Tests\Feature\Controllers;

use App\Models\PaymentType;
use App\Services\Auth\Jwt;
use Faker\Factory;
use Faker\Factory as FakerFactory;
use Faker\Provider\pt_BR\Person;
use Illuminate\Http\Response;
use Tests\TestCase;
use \App\Models\User;

class TransactionsControllerTest extends TestCase
{
    private $headers;
    private $validJwt;

    protected function setUp(): void
    {
        parent::setUp();

        $faker = FakerFactory::create();
        $faker->addProvider(new Person($faker));

        $claims = [
            'user' => [
                'name' => $faker->name,
                'email' => $faker->email,
                'document' => $faker->cpf(false)
            ]
        ];

        $jwt = new Jwt();
        $this->validJwt = $jwt->generate($claims);
        $this->headers = [
            'Authorization' => sprintf('Bearer %s', $this->validJwt)
        ];
    }

    public function testWrongJwtThrowsException(): void
    {
        $jwt = 'abc2';
        $headers = [
            'Authorization' => sprintf('Bearer %s', $jwt)
        ];

        $response = $this->post('/api/transactions', [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['message' => 'INVALID_CREDENTIALS']);
    }

    public function testInvalidJwtThrowsException(): void
    {
        $jwt = 'eyJhbGciOiJIUzI1NiJ9.eyJleHAiOjE2MTc4OTcxMjQsIm5vbWUiOiJ0ZXN0ZSJ9.e4Vb3v40O4d1FN_P1RKk8h37DA0gBDQQ3sZb_YdWU1E';

        $headers = [
            'Authorization' => sprintf('Bearer %s', $jwt)
        ];

        $response = $this->post('/api/transactions', [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['message' => 'INVALID_CREDENTIALS']);
    }

    public function testValidJwtAndTransactionsOk(): string
    {
        $faker = Factory::create();

        $client = User::where('email', 'client@payment.com')->first();
        $shopKeeper = User::where('email', 'shopkeeper@payment.com')->first();
        $creditCard = PaymentType::where('code', 'cc')->first();
        $value = $faker->randomFloat(2, 10, $client->balance);

        $data = [
            'payer' => $client->id,
            'payee' => $shopKeeper->id,
            'payment_type' => $creditCard->id,
            'value' => $value,
            'description' => sprintf('Debt to %s', $shopKeeper->name)
        ];

        $response = $this->post('/api/transactions', $data, $this->headers);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson(['message' => 'success']);

        return $this->validJwt;
    }

}
