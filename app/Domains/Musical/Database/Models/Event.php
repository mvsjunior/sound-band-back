<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'event_type_id',
        'band_id',
        'starts_at',
        'ends_at',
        'canceled_at',
        'notes',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }

    public function band(): BelongsTo
    {
        return $this->belongsTo(Band::class);
    }

    public function musics(): BelongsToMany
    {
        return $this->belongsToMany(Music::class, 'event_music')
            ->withPivot('id', 'tone_id', 'position', 'notes')
            ->using(EventMusic::class);
    }

    public function eventMusics(): HasMany
    {
        return $this->hasMany(EventMusic::class);
    }
}
