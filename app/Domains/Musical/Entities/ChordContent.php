<?php

namespace App\Domains\Musical\Entities;

class ChordContent
{
    public function __construct(
        private ?int $id,
        private string $content
    ){}

    public function id(): ?int
    {
        return $this->id;
    }

    public function content(): string
    {
        return $this->content;
    }
}
