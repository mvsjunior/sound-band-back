<?php

namespace App\Domains\Musical\Actions\Musician;

use App\Domains\Musical\Database\Models\Musician;

class UpdateMusicianAction
{
    public function execute(
        int $id,
        string $name,
        ?string $email,
        ?string $phone,
        int $statusId
    ): bool {
        $musician = Musician::query()->find($id);

        if (!$musician) {
            return false;
        }

        $musician->update([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status_id' => $statusId,
        ]);

        return true;
    }
}
