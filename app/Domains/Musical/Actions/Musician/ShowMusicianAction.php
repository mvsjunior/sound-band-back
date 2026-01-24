<?php

namespace App\Domains\Musical\Actions\Musician;

use App\Domains\Musical\Database\Models\Musician;
use App\Domains\Musical\DTO\MusicianDTO;
use App\Domains\Musical\Mappers\MusicianDTOMapper;

class ShowMusicianAction
{
    public function execute(int $id): ?MusicianDTO
    {
        $musician = Musician::query()
            ->select(['id', 'name', 'email', 'phone', 'status_id'])
            ->with(['status:id,name', 'skills:id,name'])
            ->find($id);

        if (!$musician) {
            return null;
        }

        return MusicianDTOMapper::fromArray([
            'id' => $musician->id,
            'name' => $musician->name,
            'email' => $musician->email,
            'phone' => $musician->phone,
            'status_id' => $musician->status_id,
            'status_name' => $musician->status?->name ?? '',
            'skills' => $musician->skills->map(fn ($skill) => [
                'id' => $skill->id,
                'name' => $skill->name,
            ])->all(),
        ]);
    }
}
