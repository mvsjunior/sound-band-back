# Sound Band API

Backend for the Sound Band platform. It centralizes authentication and musical catalog data (music, lyrics, categories, tags, and chords) so clients can manage rehearsal material and performance versions.

## Stack

- Laravel 12, PHP 8.3
- MySQL + Redis (Docker)
- Vite + Tailwind for frontend assets

## Run with Docker

- Start services: `docker compose up -d`
- Install dependencies: `docker compose exec app composer install`
- Setup app: `docker compose exec app composer run setup`
- Run tests: `docker compose exec app php artisan test`

## Domains and Business Logic

### Auth
- JWT-based login/registration and session handling.
- Routes live under `/api/auth/*`.

### Musical

#### Core entities
- Music: name, artist, category, lyrics.
- Category: required for each music.
- Lyrics: stored as long text and referenced by music.
- Tags: many-to-many with music.
- Chords: version metadata for each music (one music has many chords).
- Tone: key signature metadata (name + type) used by chords.
- Chord content: detailed chord sheet stored separately from chord metadata.

#### Relationships
- Music belongs to one category and one lyrics.
- Music has many tags (pivot table `musics_tags`).
- Music has many chords.
- Chord belongs to one music and one tone.
- Chord has one chord content.

#### Chords flow
- List music: includes chord metadata (id, version, tone) for quick browsing.
- Chord detail: exposes the full chord content by chord id.

## API Overview

Base URL: `/api`

### Music
- `GET /musical/music` list/filter music (name, artist, categoryId, id)
- `POST /musical/music/store` create music
- `PUT /musical/music/update` update music
- `DELETE /musical/music/delete` delete music

### Categories
- `GET /musical/category`
- `POST /musical/category/store`
- `PUT /musical/category/update`
- `DELETE /musical/category/delete`

### Tags
- `GET /musical/tags`
- `POST /musical/tags/store`
- `PUT /musical/tags/update`
- `DELETE /musical/tags/delete`

### Lyrics
- `GET /musical/lyrics`
- `POST /musical/lyrics/store`
- `PUT /musical/lyrics/update`
- `DELETE /musical/lyrics/delete`

### Chords
- `GET /musical/chords` list chords (filters: id, musicId, toneId)
- `GET /musical/chords/show` chord detail (includes content)
- `POST /musical/chords/store` create chord
- `PUT /musical/chords/update` update chord
- `DELETE /musical/chords/delete` delete chord

## Testing

- Run all tests: `docker compose exec app php artisan test`
- Feature tests are in `tests/Feature`.

## Notes

- Keep secrets out of git; use `.env` from `.env.example`.
- Store runtime files in `storage/`.
