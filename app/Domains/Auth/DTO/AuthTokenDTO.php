<?php

namespace App\Domains\Auth\DTO;

use App\Domains\Commons\DTO\DTO;

class AuthTokenDTO extends DTO
{
    public function __construct(
        public readonly string $token,
        public readonly string $type = 'bearer',
        public readonly int $expiresIn = 3600
    ) {}
}
