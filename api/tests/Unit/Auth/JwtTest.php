<?php

namespace Tests\Unit\Auth;

use App\Services\Auth\Jwt;
use Emarref\Jwt\Exception\InvalidSignatureException;
use Faker\Factory as FakerFactory;
use Faker\Provider\pt_BR\Person;
use PHPUnit\Framework\TestCase;

class JwtTest extends TestCase
{
    private $claims;

    protected function setUp(): void
    {
        parent::setUp();

        $faker = FakerFactory::create();
        $faker->addProvider(new Person($faker));

        $this->claims = [
            'user' => [
                'name' => $faker->name,
                'email' => $faker->email,
                'document' => $faker->cpf(false)
            ]
        ];
    }

    public function testJwtGeneratedOk(): array
    {
        $jwt = new Jwt();
        $newJwt = $jwt->generate($this->claims);
        $this->assertNotEmpty($newJwt);

        return [
            $newJwt,
            $this->claims
        ];
    }

    /**
     * @depends testJwtGeneratedOk
     */
    public function testJwtIsValidOk(array $data): void
    {
        list($newJwt, $claims) = $data;

        $jwt = new Jwt();
        $payload = $jwt->validate($newJwt);

        $this->assertEquals($claims['user'], $payload);
    }

    public function testJwtIsNotValidThrowsException(): void
    {
        $this->expectException(InvalidSignatureException::class);
        $oldJwt = 'eyJhbGciOiJIUzI1NiJ9.eyJleHAiOjE2MTc4OTcxMjQsIm5vbWUiOiJ0ZXN0ZSJ9.e4Vb3v40O4d1FN_P1RKk8h37DA0gBDQQ3sZb_YdWU1E';

        $jwt = new Jwt();
        $jwtIsValid = $jwt->validate($oldJwt);
    }
}

