<?php

namespace App\Domains\Auth\DTO;

use App\Domains\Commons\DTO\DTO;

class NewUserDTO extends DTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    ) {}
}
