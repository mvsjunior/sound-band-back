<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tone extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'type',
    ];

    public function chords(): HasMany
    {
        return $this->hasMany(Chord::class);
    }

    public function eventMusics(): HasMany
    {
        return $this->hasMany(EventMusic::class);
    }
}
