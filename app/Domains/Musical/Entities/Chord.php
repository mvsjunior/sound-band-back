<?php

namespace App\Domains\Musical\Entities;

class Chord
{
    public function __construct(
        private ?int $id,
        private int $musicId,
        private int $toneId,
        private ?int $chordContentId,
        private ?string $version = null
    ){}

    public function id(): ?int
    {
        return $this->id;
    }

    public function musicId(): int
    {
        return $this->musicId;
    }

    public function toneId(): int
    {
        return $this->toneId;
    }

    public function chordContentId(): ?int
    {
        return $this->chordContentId;
    }

    public function setChordContentId(int $chordContentId): void
    {
        $this->chordContentId = $chordContentId;
    }

    public function version(): ?string
    {
        return $this->version;
    }
}
