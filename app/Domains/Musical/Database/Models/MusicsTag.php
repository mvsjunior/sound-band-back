<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MusicsTag extends Pivot
{
    protected $table = 'musics_tags';

    public $timestamps = true;

    public $incrementing = true;

    protected $fillable = [
        'music_id',
        'tag_id',
    ];

    public function music(): BelongsTo
    {
        return $this->belongsTo(Music::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
