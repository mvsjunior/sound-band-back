<?php

namespace App\Domains\Musical\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Music extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'musics';

    protected $fillable = [
        'name',
        'artist',
        'lyrics_id',
        'category_id',
    ];

    public function lyrics(): BelongsTo
    {
        return $this->belongsTo(Lyrics::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function chords(): HasMany
    {
        return $this->hasMany(Chord::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'musics_tags')
            ->withPivot('id')
            ->withTimestamps()
            ->using(MusicsTag::class);
    }

    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class, 'playlist_music')
            ->withPivot('position')
            ->using(PlaylistMusic::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_music')
            ->withPivot('id', 'tone_id', 'position', 'notes')
            ->using(EventMusic::class);
    }

    public function eventMusics(): HasMany
    {
        return $this->hasMany(EventMusic::class);
    }

    public function playlistMusics(): HasMany
    {
        return $this->hasMany(PlaylistMusic::class);
    }
}
