<?php

namespace Tests\Unit\Services\Register;

use App\Models\User;
use Faker\Factory;
use Faker\Provider\pt_BR\Person;
use PHPUnit\Framework\TestCase;
use App\Services\Register\CheckIfUserExists;

class CheckIfUserExistsTest extends TestCase
{
    public function testUserDocumentExistsThrowsException()
    {
        $this->expectExceptionMessage('DOCUMENT_EXISTS');

        $faker = Factory::create();
        $faker->addProvider(new Person($faker));
        $cpf = $faker->cpf(false);

        $user = new User([
            'name' => $faker->name,
            'email' => $faker->email,
            'document' => $faker->cpf
        ]);

        $email = $faker->email;
        $document = $user->document;

        CheckIfUserExists::execute($user, $email, $document);
    }

    public function testUserEmailExistsThrowsException()
    {
        $this->expectExceptionMessage('EMAIL_EXISTS');

        $faker = Factory::create();
        $faker->addProvider(new Person($faker));
        $cpf = $faker->cpf(false);

        $user = new User([
            'name' => $faker->name,
            'email' => $faker->email,
            'document' => $faker->cpf
        ]);

        $email = $user->email;
        $document = $faker->cpf;

        CheckIfUserExists::execute($user, $email, $document);
    }
}
