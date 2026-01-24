<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Musician extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status_id',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(MusicianStatus::class, 'status_id');
    }

    public function bands(): BelongsToMany
    {
        return $this->belongsToMany(Band::class, 'musicians_bands')
            ->withPivot('id', 'role_id')
            ->using(MusicianBand::class);
    }

    public function musicianBands(): HasMany
    {
        return $this->hasMany(MusicianBand::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'musician_skill')
            ->using(MusicianSkill::class);
    }
}
