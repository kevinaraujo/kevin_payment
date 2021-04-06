<?php

namespace Tests\Feature;

use Faker\Factory;
use Faker\Provider\pt_BR\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    public function testRegisterMissingNameParamReturnsBadRequest()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'email' => $faker->email,
            'cpfcnpj' => $faker->cpf,
            'password' => $faker->password
        ];

        $response = $this->post('/api/register', $data);

        $response->assertStatus(400);
        $response->assertJson([ 'message' => 'MISSING_PARAM']);
    }

    public function testRegisterMissingEmailParamReturnsBadRequest()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'cpfcnpj' => $faker->cpf,
            'password' => $faker->password
        ];

        $response = $this->post('/api/register', $data);

        $response->assertStatus(400);
        $response->assertJson([ 'message' => 'MISSING_PARAM']);
    }

    public function testRegisterMissingCpfCnpjParamReturnsBadRequest()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => $faker->password
        ];

        $response = $this->post('/api/register', $data);

        $response->assertStatus(400);
        $response->assertJson([ 'message' => 'MISSING_PARAM']);
    }

    public function testRegisterMissingPasswordParamReturnsBadRequest()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'cpfcnpj' => $faker->cpf
        ];

        $response = $this->post('/api/register', $data);

        $response->assertStatus(400);
        $response->assertJson([ 'message' => 'MISSING_PARAM']);
    }
}
