<?php

namespace Tests\Unit\Auth;

use App\Services\Auth\Jwt;
use Emarref\Jwt\Exception\InvalidSignatureException;
use Faker\Factory;
use Faker\Factory as FakerFactory;
use Faker\Provider\pt_BR\Person;
use http\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class JwtTest extends TestCase
{
    public function testJwtGeneratedOk(): string
    {
        $faker = FakerFactory::create();
        $faker->addProvider(new Person($faker));

        $claims = [
            'user' => [
                'name' => $faker->name,
                'email' => $faker->email,
                'document' => $faker->cpf(false)
            ]
        ];

        $jwt = new Jwt();
        $newJwt = $jwt->generate($claims);

        $this->assertNotEmpty($newJwt);

        return $newJwt;
    }

    /**
     * @depends testJwtGeneratedOk
     */
    public function testJwtIsValidOk(string $newJwt): void
    {
        $jwt = new Jwt();
        $jwtIsValid = $jwt->validate($newJwt);
        $this->assertTrue($jwtIsValid);
    }

    public function testJwtIsNotValidThrowsException(): void
    {
        $this->expectException(InvalidSignatureException::class);
        $oldJwt = 'eyJhbGciOiJIUzI1NiJ9.eyJleHAiOjE2MTc4OTcxMjQsIm5vbWUiOiJ0ZXN0ZSJ9.e4Vb3v40O4d1FN_P1RKk8h37DA0gBDQQ3sZb_YdWU1E';

        $jwt = new Jwt();
        $jwtIsValid = $jwt->validate($oldJwt);
    }
}

