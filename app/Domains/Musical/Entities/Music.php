<?php

namespace App\Domains\Musical\Entities;

class Music 
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $artist,
        private Lyrics $lyrics,
        private Category $category,
        private array  $tags = [],
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

    public function artist(): string
    {
        return $this->artist;
    }

    public function changeArtist(string $newArtistName): void
    {
        $this->artist = $newArtistName;
    }

    public function lyrics(): Lyrics
    {
        return $this->lyrics;
    }

    public function category(): Category
    {
        return $this->category;
    }

    public function tags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }
}