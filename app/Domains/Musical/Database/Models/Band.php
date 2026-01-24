<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Band extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    public function musicians(): BelongsToMany
    {
        return $this->belongsToMany(Musician::class, 'musicians_bands')
            ->withPivot('id', 'role_id')
            ->using(MusicianBand::class);
    }

    public function musicianBands(): HasMany
    {
        return $this->hasMany(MusicianBand::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
