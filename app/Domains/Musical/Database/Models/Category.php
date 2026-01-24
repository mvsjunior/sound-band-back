<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'music_categories';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function musics(): HasMany
    {
        return $this->hasMany(Music::class, 'category_id');
    }
}
