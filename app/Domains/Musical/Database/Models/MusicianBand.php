<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MusicianBand extends Pivot
{
    protected $table = 'musicians_bands';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'band_id',
        'musician_id',
        'role_id',
    ];

    public function band(): BelongsTo
    {
        return $this->belongsTo(Band::class);
    }

    public function musician(): BelongsTo
    {
        return $this->belongsTo(Musician::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
