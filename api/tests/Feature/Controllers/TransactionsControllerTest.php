<?php

namespace Tests\Feature\Controllers;

use App\Models\PaymentType;
use App\Services\Auth\Jwt;
use Faker\Factory as FakerFactory;
use Faker\Provider\pt_BR\Person;
use Illuminate\Http\Response;
use Tests\TestCase;
use \App\Models\User;

class TransactionsControllerTest extends TestCase
{
    private $validJwt;
    private $headers;
    private $data;
    private $faker;

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

        $faker = FakerFactory::create();

        $client = User::where('email', 'client@payment.com')->first();
        $shopKeeper = User::where('email', 'shopkeeper@payment.com')->first();
        $creditCard = PaymentType::where('code', 'cc')->first();
        $value = $faker->randomFloat(2, 10, 100);

        $this->data = [
            'payer' => $client->id,
            'payee' => $shopKeeper->id,
            'payment_type' => $creditCard->id,
            'value' => $value,
            'description' => sprintf('Debt to %s', $shopKeeper->name)
        ];

        $this->faker = $faker;
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
        $response = $this->post('/api/transactions', $this->data, $this->headers);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson(['message' => 'success']);

        return $this->validJwt;
    }

    public function testValidJwtAndInvalidPaymentUserReturnsNotFoundResponse(): void
    {
        $data = $this->data;
        $faker = $this->faker;

        $data['payment_type'] = $faker->numberBetween(1000,2000);

        $response = $this->post('/api/transactions', $data, $this->headers);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson(['message' => 'PAYMENT_TYPE_NOT_FOUND']);
    }

    public function testValidJwtAndInsufficientReturnsBadRequestResponse(): void
    {
        $data = $this->data;
        $faker = $this->faker;

        $data['value'] = $faker->numberBetween(10000,20000);

        $response = $this->post('/api/transactions', $data, $this->headers);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(['message' => 'INSUFFICIENT_BALANCE']);
    }

    public function testValidJwtAndMissingPayerParamReturnsBadRequestResponse(): void
    {
        $data = $this->data;
        unset($data['payer']);

        $response = $this->post('/api/transactions', $data, $this->headers);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(['message' => ['payer' => ['MISSING_PARAM']]]);
    }

    public function testValidJwtAndMissingPayeeParamReturnsBadRequestResponse(): void
    {
        $data = $this->data;
        unset($data['payee']);

        $response = $this->post('/api/transactions', $data, $this->headers);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(['message' => ['payee' => ['MISSING_PARAM']]]);
    }

    public function testValidJwtAndMissingPaymentTypeParamReturnsBadRequestResponse(): void
    {
        $data = $this->data;
        unset($data['payment_type']);

        $response = $this->post('/api/transactions', $data, $this->headers);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(['message' => ['payment_type' => ['MISSING_PARAM']]]);
    }

    public function testValidJwtAndMissingValueParamReturnsBadRequestResponse(): void
    {
        $data = $this->data;
        unset($data['value']);

        $response = $this->post('/api/transactions', $data, $this->headers);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(['message' => ['value' => ['MISSING_PARAM']]]);
    }

    public function testValidJwtAndMissingDescriptionParamReturnsBadRequestResponse(): void
    {
        $data = $this->data;
        unset($data['description']);

        $response = $this->post('/api/transactions', $data, $this->headers);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(['message' => ['description' => ['MISSING_PARAM']]]);
    }
}
