<?php

namespace Tests\Feature\Controllers;

use Faker\Factory;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * @depends Tests\Feature\Controllers\UserControllerTest::testCreateUserOk
     */
    public function testAuthIsOk(array $data): void
    {
        $postData = [
            'email' => $data['email'],
            'password' => $data['password']
        ];

        $response = $this->post('/api/auth', $postData);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'message',
            'access_token'
        ]);
    }

    /**
     * @depends Tests\Feature\Controllers\UserControllerTest::testCreateUserOk
     */
    public function testAuthPasswordIncorrectReturnsNotAuthenticated(array $data): void
    {
        $faker = Factory::create();

        $postData = [
            'email' => $data['email'],
            'password' => $faker->password
        ];

        $response = $this->post('/api/auth', $postData);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['message' => 'INVALID_CREDENTIALS']);
    }

    public function testMissingEmailParamReturnsBadRequest(): void
    {
        $faker = Factory::create();

        $postData = [
            'email' => '',
            'password' => $faker->password
        ];

        $response = $this->post('/api/auth', $postData);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(['message' => ['email' => ['MISSING_PARAM']]]);
    }
}
