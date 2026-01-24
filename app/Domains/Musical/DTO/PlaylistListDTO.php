<?php

namespace App\Domains\Musical\DTO;

class PlaylistListDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $userId,
        public readonly string $userName
    )
    {
    }
}