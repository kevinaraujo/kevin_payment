<?php

namespace Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * @depends Tests\Feature\UserControllerTest::testCreateUserOk
     */
    public function testAuthIsOk(array $data) : void
    {
        $postData = [
            'email' => $data['email'],
            'password' => $data['password']
        ];

        $response = $this->post('/api/auth', $postData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'access_token'
        ]);
    }

    /**
     * @depends Tests\Feature\UserControllerTest::testCreateUserOk
     */
    public function testAuthPasswordIncorrectReturnsNotAuthenticated(array $data) : void
    {
        $faker = Factory::create();

        $postData = [
            'email' => $data['email'],
            'password' => $faker->password
        ];

        $response = $this->post('/api/auth', $postData);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'INVALID_CREDENTIALS']);
    }
}
