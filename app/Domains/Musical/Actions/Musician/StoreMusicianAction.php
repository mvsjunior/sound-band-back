<?php

namespace App\Domains\Musical\Actions\Musician;

use App\Domains\Musical\Database\Models\Musician;
use App\Domains\Musical\DTO\MusicianDTO;
use App\Domains\Musical\Mappers\MusicianDTOMapper;

class StoreMusicianAction
{
    public function execute(
        string $name,
        ?string $email,
        ?string $phone,
        int $statusId
    ): MusicianDTO {
        $musician = Musician::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status_id' => $statusId,
        ]);

        $musician->load(['status:id,name']);

        return MusicianDTOMapper::fromArray([
            'id' => $musician->id,
            'name' => $musician->name,
            'email' => $musician->email,
            'phone' => $musician->phone,
            'status_id' => $musician->status_id,
            'status_name' => $musician->status?->name ?? '',
        ]);
    }
}
