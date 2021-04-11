<?php

namespace App\Services\Auth;

use \Emarref\Jwt\Token;
use \Emarref\Jwt\Jwt as EmarrefJwt;
use \Emarref\Jwt\Algorithm\Hs256;
use Emarref\Jwt\Claim\Expiration;
use Emarref\Jwt\Claim\PublicClaim;
use \Emarref\Jwt\Encryption\Factory;
use Emarref\Jwt\Verification\Context;

class Jwt
{
    private $token;
    private $emarrefJwt;
    private $algorithm;
    private $encryption;

    public function __construct()
    {
        $this->token = new Token();
        $this->emarrefJwt = new EmarrefJwt();
        $this->algorithm = new Hs256(env('SECRET_KEY'));
        $this->encryption = Factory::create($this->algorithm);
    }

    public function generate(array $claims, string $dateTime = '30 minutes') : string
    {
        $this->token->addClaim(new Expiration(new \DateTime($dateTime)));

        foreach($claims as $key => $claim) {
            $this->token->addClaim(new PublicClaim($key, json_encode($claim)));
        }

        return $this->emarrefJwt->serialize( $this->token, $this->encryption);
    }

    public function validate(string $jwt) : array
    {
        $token = $this->emarrefJwt->deserialize($jwt);

        $context = new Context($this->encryption);
        $jwtIsValid = $this->emarrefJwt->verify($token, $context);
        $payload = $this->getPayload($token);

        return $payload;
    }

    public function getPayload(Token $token): array
    {
        $payload = $token->getPayload()->getClaims()->getIterator()['user']->getValue();

        return (array) json_decode($payload);
    }
}
