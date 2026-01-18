<?php

namespace App\Domains\Musical\Entities;

class Tag 
{
    public function __construct(
        private ?int $id,
        private string $name
    )
    {}

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function changeName(string $newName): void
    {
        $this->name = $newName;
    }
}