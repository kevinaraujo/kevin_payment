<?php

namespace Tests\Feature;

use Faker\Factory;
use Faker\Provider\pt_BR\Person;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    public function testRegisterMissingNameParamReturnsBadRequest()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'email' => $faker->email,
            'document' => $faker->cpf,
            'password' => $faker->password
        ];

        $response = $this->post('/api/register', $data);

        $message = [ 'message' => [
            'name' => ['MISSING_PARAM']
        ]];

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    public function testRegisterMissingEmailParamReturnsBadRequest()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'document' => $faker->cpf,
            'password' => $faker->password
        ];

        $response = $this->post('/api/register', $data);
        $message = [ 'message' => [
            'email' => ['MISSING_PARAM']
        ]];

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    public function testRegisterMissingDocumentParamReturnsBadRequest()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => $faker->password
        ];

        $response = $this->post('/api/register', $data);
        $message = [ 'message' => [
            'document' => ['MISSING_PARAM']
        ]];

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    public function testRegisterMissingPasswordParamReturnsBadRequest()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'document' => $faker->cpf
        ];

        $response = $this->post('/api/register', $data);
        $message = [ 'message' => [
            'password' => ['MISSING_PARAM']
        ]];

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    public function testRegisterShortPasswordParamReturnsBadRequest()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'document' => $faker->cpf,
            'password' => $faker->password(4,7)
        ];

        $response = $this->post('/api/register', $data);
        $message = [ 'message' => [
            'password' => ['INVALID_PASSWORD']
        ]];

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    public function testRegisterLongPasswordParamReturnsBadRequest()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'document' => $faker->cpf,
            'password' => $faker->password(13)
        ];

        $response = $this->post('/api/register', $data);
        $message = [ 'message' => [
            'password' => ['INVALID_PASSWORD']
        ]];

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    public function testRegisterInvalidEmailParamReturnsBadRequest()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => str_replace('@', '', $faker->email),
            'document' => $faker->cpf,
            'password' => $faker->password(13)
        ];

        $response = $this->post('/api/register', $data);
        $message = [ 'message' => [
            'email' => ['INVALID_EMAIL']
        ]];

        $response->assertStatus(400);
        $response->assertJson($message);
    }

    public function testRegisterOk()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'document' => $faker->cpf,
            'password' => $faker->password(8,12)
        ];

        $response = $this->post('/api/register', $data);

        $response->assertStatus(200);
        $response->assertJson([ 'message' => 'success']);
    }
}
