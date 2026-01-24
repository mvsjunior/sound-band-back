<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MusicianSkill extends Pivot
{
    protected $table = 'musician_skill';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'musician_id',
        'skill_id',
    ];

    public function musician(): BelongsTo
    {
        return $this->belongsTo(Musician::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
