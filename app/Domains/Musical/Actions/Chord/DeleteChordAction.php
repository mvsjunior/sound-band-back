<?php

namespace App\Domains\Musical\Actions\Chord;

use App\Domains\Musical\Database\Models\Chord;
use App\Domains\Musical\Database\Models\ChordContent;
use Illuminate\Support\Facades\DB;

class DeleteChordAction
{
    public function execute(int $id): void
    {
        DB::transaction(function () use ($id) {
            $chord = Chord::query()->select(['id', 'chord_content_id'])->find($id);
            if (!$chord) {
                return;
            }

            $contentId = $chord->chord_content_id;
            $chord->delete();

            if ($contentId) {
                ChordContent::query()->whereKey($contentId)->delete();
            }
        });
    }
}
