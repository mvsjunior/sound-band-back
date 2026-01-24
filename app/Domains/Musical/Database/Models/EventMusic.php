<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventMusic extends Pivot
{
    protected $table = 'event_music';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'event_id',
        'music_id',
        'tone_id',
        'position',
        'notes',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function music(): BelongsTo
    {
        return $this->belongsTo(Music::class);
    }

    public function tone(): BelongsTo
    {
        return $this->belongsTo(Tone::class);
    }
}
