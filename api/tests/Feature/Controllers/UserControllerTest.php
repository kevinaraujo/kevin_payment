<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Services\Format\FormatDocument;
use Faker\Factory;
use Faker\Provider\pt_BR\Person;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testCreateUserOk(): array
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'document' => $faker->cpf,
            'password' => $faker->password(8,12)
        ];

        $response = $this->post('/api/users', $data);

        $format = new FormatDocument($data['document']);

        $user = User::query()
            ->where('document', $format->getDocument())
            ->where('email', $data['email'])
            ->first();

        $this->assertIsObject((new User()), $user);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([ 'message' => 'success']);

        return [
            'email' => $user->email,
            'password' => $data['password'],
            'document' => $user->document
        ];
    }

    public function testCreateUserMissingNameParamReturnsBadRequest(): void
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'email' => $faker->email,
            'document' => $faker->cpf,
            'password' => $faker->password
        ];

        $response = $this->post('/api/users', $data);

        $message = [ 'message' => [
            'name' => ['MISSING_PARAM']
        ]];

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson($message);
    }

    public function testCreateUserMissingEmailParamReturnsBadRequest(): void
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'document' => $faker->cpf,
            'password' => $faker->password
        ];

        $response = $this->post('/api/users', $data);
        $message = [ 'message' => [
            'email' => ['MISSING_PARAM']
        ]];

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson($message);
    }

    public function testCreateUserMissingDocumentParamReturnsBadRequest(): void
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => $faker->password
        ];

        $response = $this->post('/api/users', $data);
        $message = [ 'message' => [
            'document' => ['MISSING_PARAM']
        ]];

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson($message);
    }

    public function testCreateUserMissingPasswordParamReturnsBadRequest(): void
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'document' => $faker->cpf
        ];

        $response = $this->post('/api/users', $data);
        $message = [ 'message' => [
            'password' => ['MISSING_PARAM']
        ]];

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson($message);
    }

    public function testCreateUserShortPasswordParamReturnsBadRequest(): void
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'document' => $faker->cpf,
            'password' => $faker->password(4,7)
        ];

        $response = $this->post('/api/users', $data);
        $message = [ 'message' => [
            'password' => ['INVALID_PASSWORD']
        ]];

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson($message);
    }

    public function testCreateUserLongPasswordParamReturnsBadRequest(): void
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'document' => $faker->cpf,
            'password' => $faker->password(13)
        ];

        $response = $this->post('/api/users', $data);
        $message = [ 'message' => [
            'password' => ['INVALID_PASSWORD']
        ]];

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson($message);
    }

    public function testCreateUserInvalidEmailParamReturnsBadRequest(): void
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => str_replace('@', '', $faker->email),
            'document' => $faker->cpf,
            'password' => $faker->password(13)
        ];

        $response = $this->post('/api/users', $data);
        $message = [ 'message' => [
            'email' => ['INVALID_EMAIL']
        ]];

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson($message);
    }

    /**
     * @depends testCreateUserOk
     */
    public function testCreateUserExistentEmailParamReturnsConflitResponse(array $data): void
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $data['email'],
            'document' => $faker->cpf,
            'password' => $faker->password(8,12)
        ];

        $response = $this->post('/api/users', $data);
        $message = [ 'message' => 'EMAIL_EXISTS'];

        $response->assertStatus(Response::HTTP_CONFLICT);
        $response->assertJson($message);
    }

    /**
     * @depends testCreateUserOk
     */
    public function testCreateUserExistentDocumentParamReturnsConflitResponse(array $data): void
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'document' => $data['document'],
            'password' => $faker->password(8,12)
        ];

        $response = $this->post('/api/users', $data);
        $message = [ 'message' => 'DOCUMENT_EXISTS'];

        $response->assertStatus(Response::HTTP_CONFLICT);
        $response->assertJson($message);

    }

    public function testGetPaymentTypesButUserDoesntExistReturnsNotFound(): void
    {
        $this->withoutMiddleware();

        $response = $this->get('/api/users/100000000000/payments-types');
        $message = [ 'message' => 'USER_NOT_FOUND'];

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson($message);
    }

    /**
     * @depends testCreateUserOk
     */
    public function testGetPaymentTypesReturnsOk(array $data) : void
    {
        $this->withoutMiddleware();

        $user = User::where('document', $data['document'])->first();

        $response = $this->get(sprintf('/api/users/%s/payments-types',$user->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'message',
            'payment_types'
        ]);
    }

    public function testGetUserBalanceButUserDoesntExistReturnsNotFound(): void
    {
        $this->withoutMiddleware();

        $response = $this->get('/api/users/100000000000/balance');
        $message = [ 'message' => 'USER_NOT_FOUND'];

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson($message);
    }

    /**
     * @depends testCreateUserOk
     */
    public function testUserBalanceReturnsOk(array $data) : void
    {
        $this->withoutMiddleware();

        $user = User::where('document', $data['document'])->first();

        $response = $this->get(sprintf('/api/users/%s/balance',$user->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'success',
            'user' => [
                'email' => $user->email,
                'balance' => $user->balance
            ]
        ]);
    }
}
