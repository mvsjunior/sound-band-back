<?php

namespace App\Domains\Commons\DTO;

Abstract class DTO {

    public function toArray(): array
    {
        return (array) $this;
    }
}