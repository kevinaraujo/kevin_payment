<?php

namespace Tests\Unit\Services\User;

use App\Models\User;
use Faker\Factory;
use Faker\Provider\pt_BR\Person;
use PHPUnit\Framework\TestCase;
use App\Services\User\ThrowRightUserMessageException;

class ThrowRightUserMessageExceptionTest extends TestCase
{
    public function testUserDocumentExistsThrowsException() : void
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

        ThrowRightUserMessageException::execute($user, $email, $document);
    }

    public function testUserEmailExistsThrowsException() : void
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

        ThrowRightUserMessageException::execute($user, $email, $document);
    }
}
