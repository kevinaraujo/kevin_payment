<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Format\FormatDocument;
use Faker\Factory;
use Faker\Provider\pt_BR\Person;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testCreateUserOk() : array
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
        $response->assertStatus(201);
        $response->assertJson([ 'message' => 'success']);

        return [
            'email' => $user->email,
            'password' => $data['password'],
            'document' => $user->document
        ];
    }

    public function testCreateUserMissingNameParamReturnsBadRequest() : void
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

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    public function testCreateUserMissingEmailParamReturnsBadRequest() : void
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

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    public function testCreateUserMissingDocumentParamReturnsBadRequest() : void
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

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    public function testCreateUserMissingPasswordParamReturnsBadRequest() : void
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

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    public function testCreateUserShortPasswordParamReturnsBadRequest() : void
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

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    public function testCreateUserLongPasswordParamReturnsBadRequest() : void
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

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    public function testCreateUserInvalidEmailParamReturnsBadRequest() : void
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

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    /**
     * @depends testCreateUserOk
     */
    public function testCreateUserExistentEmailParamReturnsConflitResponse(array $data) : void
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

        $response->assertStatus(409);
        $response->assertJson($message);
    }

    /**
     * @depends testCreateUserOk
     */
    public function testCreateUserExistentDocumentParamReturnsConflitResponse(array $data) : void
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

        $response->assertStatus(409);
        $response->assertJson($message);
    }
}
