<?php

namespace Tests\Unit\Services\User;

use App\Models\User;
use App\Services\User\AuthenticateUser;
use Faker\Factory;
use Tests\TestCase;

class AuthenticateUserTest extends TestCase
{
    /**
     * @depends Tests\Feature\Controllers\UserControllerTest::testCreateUserOk
     */
    public function testAuthenticateUserWrongEmailThrowsException(array $data): void
    {
        $this->expectExceptionCode(401);
        $this->expectExceptionMessage('INVALID_CREDENTIALS');

        $faker = Factory::create();

        $user = AuthenticateUser::execute(
            $faker->email,
            $data['password']
        );
    }

    /**
     * @depends Tests\Feature\Controllers\UserControllerTest::testCreateUserOk
     */
    public function testAuthenticateUserWrongPasswordThrowsException(array $data): void
    {
        $this->expectExceptionCode(401);
        $this->expectExceptionMessage('INVALID_CREDENTIALS');

        $faker = Factory::create();

        $user = AuthenticateUser::execute(
            $data['email'],
            $faker->password
        );
    }

    /**
     * @depends Tests\Feature\Controllers\UserControllerTest::testCreateUserOk
     */
    public function testAuthenticateUserExistsOk(array $data): void
    {
        $user = AuthenticateUser::execute(
            $data['email'],
            $data['password']
        );

        $this->assertIsObject(new User(), $user);
    }
}
