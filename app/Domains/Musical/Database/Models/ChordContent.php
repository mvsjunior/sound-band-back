<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChordContent extends Model
{
    use HasFactory;

    protected $table = 'chord_contents';

    protected $fillable = [
        'content',
    ];

    public function chords(): HasMany
    {
        return $this->hasMany(Chord::class);
    }
}
