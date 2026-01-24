<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MusicianStatus extends Model
{
    use HasFactory;

    protected $table = 'musician_statuses';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function musicians(): HasMany
    {
        return $this->hasMany(Musician::class, 'status_id');
    }
}
