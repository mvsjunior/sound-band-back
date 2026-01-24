<?php

namespace App\Domains\Auth\DTO;

class AuthTokensDTO
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken,
        public readonly int $expiresIn
    ) {
    }

    public function accessTokenDTO(): AuthTokenDTO
    {
        return new AuthTokenDTO(
            token: $this->accessToken,
            expiresIn: $this->expiresIn
        );
    }
}
