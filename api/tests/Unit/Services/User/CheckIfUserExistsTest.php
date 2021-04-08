<?php

namespace Tests\Unit\Services\User;

use App\Services\User\CheckIfUserExists;
use Faker\Factory;
use Faker\Provider\pt_BR\Person;
use Tests\TestCase;

class CheckIfUserExistsTest extends TestCase
{
    /**
     * @depends Tests\Feature\UserControllerTest::testCreateUserOk
     */
    public function testUserExistThrowsException(array $data) : void
    {
        $this->expectExceptionMessage('EMAIL_EXISTS');

        $faker = Factory::create();
        $faker->addProvider(new Person($faker));
        $email = $data['email'];
        $document = $faker->cpf(false);

        CheckIfUserExists::execute($email, $document);
    }
}
