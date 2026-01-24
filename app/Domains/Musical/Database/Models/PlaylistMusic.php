<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PlaylistMusic extends Pivot
{
    protected $table = 'playlist_music';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'playlist_id',
        'music_id',
        'position',
    ];

    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }

    public function music(): BelongsTo
    {
        return $this->belongsTo(Music::class);
    }
}
