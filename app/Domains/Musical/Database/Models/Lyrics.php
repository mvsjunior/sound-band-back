<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lyrics extends Model
{
    use HasFactory;

    protected $table = 'lyrics';

    protected $fillable = [
        'content',
    ];

    public function musics(): HasMany
    {
        return $this->hasMany(Music::class);
    }
}
