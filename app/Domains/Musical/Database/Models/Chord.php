<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chord extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'tone',
        'chord_content_id',
        'music_id',
        'version',
    ];

    public function chordContent(): BelongsTo
    {
        return $this->belongsTo(ChordContent::class);
    }

    public function music(): BelongsTo
    {
        return $this->belongsTo(Music::class);
    }
}
