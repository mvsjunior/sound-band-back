<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function musics(): BelongsToMany
    {
        return $this->belongsToMany(Music::class, 'musics_tags')
            ->withPivot('id')
            ->withTimestamps()
            ->using(MusicsTag::class);
    }

    public function musicsTags(): HasMany
    {
        return $this->hasMany(MusicsTag::class, 'tag_id');
    }
}
