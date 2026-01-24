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
- Chords tone: enum value (e.g. `C`, `C#`, `D`, `Eb`, `E`, `F`, `F#`, `G`, `Ab`, `A`, `Bb`, `B`, `Cm`, `C#m`, `Dm`, `Ebm`, `Em`, `Fm`, `F#m`, `Gm`, `Abm`, `Am`, `Bbm`, `Bm`).
- Tone: key signature metadata (tone enum + type).
- Chord content: detailed chord sheet stored separately from chord metadata.
- Musicians: roster with status, contact details.
- Skills: catalog of musician abilities/roles.
- Musician skills: many-to-many associations between musicians and skills.

#### Relationships
- Music belongs to one category and one lyrics.
- Music has many tags (pivot table `musics_tags`).
- Music has many chords.
- Chord belongs to one music and has a tone enum.
- Chord has one chord content.
- Musician belongs to one status.
- Musician has many skills (pivot table `musician_skill`).

#### Chords flow
- List music: includes chord metadata (id, version, tone) for quick browsing.
- Chord detail: exposes the full chord content by chord id.

## API Overview

Base URL: `/api`

List endpoints return `data.items` plus `data.pagination` and accept `page` and `perPage` query params.

### Music
- `GET /musical/music` list/filter music (name, artist, categoryId, id, page, perPage)
- `GET /musical/music/show` fetch a single music by id
- `POST /musical/music/store` create music
- `PUT /musical/music/update` update music
- `DELETE /musical/music/delete` delete music

### Categories
- `GET /musical/category` (page, perPage)
- `GET /musical/category/show`
- `POST /musical/category/store`
- `PUT /musical/category/update`
- `DELETE /musical/category/delete`

### Tags
- `GET /musical/tags` (page, perPage)
- `GET /musical/tags/show`
- `POST /musical/tags/store`
- `PUT /musical/tags/update`
- `DELETE /musical/tags/delete`

### Lyrics
- `GET /musical/lyrics` (page, perPage)
- `GET /musical/lyrics/show`
- `POST /musical/lyrics/store`
- `PUT /musical/lyrics/update`
- `DELETE /musical/lyrics/delete`

### Chords
- `GET /musical/chords` list chords (filters: id, musicId, tone, page, perPage)
- `GET /musical/chords/show` chord detail (includes content)
- `POST /musical/chords/store` create chord
- `PUT /musical/chords/update` update chord
- `DELETE /musical/chords/delete` delete chord

### Musicians
- `GET /musical/musicians` list/filter musicians (name, email, phone, statusId, id, page, perPage)
- `GET /musical/musicians/show` fetch a single musician by id
- `POST /musical/musicians/store` create musician
- `PUT /musical/musicians/update` update musician
- `DELETE /musical/musicians/delete` delete musician

### Musician skills
- `GET /musical/musician-skills` list/filter musician skills (musicianId, skillId, page, perPage)
- `GET /musical/musician-skills/show` fetch a musician-skill association (musicianId, skillId)
- `POST /musical/musician-skills/store` create a musician-skill association
- `PUT /musical/musician-skills/update` update a musician-skill association
- `DELETE /musical/musician-skills/delete` delete a musician-skill association

## Testing

- Run all tests: `docker compose exec app php artisan test`
- Feature tests are in `tests/Feature`.

## Notes

- Keep secrets out of git; use `.env` from `.env.example`.
- Store runtime files in `storage/`.

## Architecture Pattern

Each domain lives in `app/Domains/<Domain>` and follows the same directory layout:

- `Actions/`: use cases that orchestrate domain logic for controllers.
- `Database/`: repositories and Eloquent data access.
- `DTO/`: response and data transfer shapes.
- `Entities/`: core domain objects.
- `Exceptions/`: domain-specific errors.
- `Http/`: controllers and request validation.
- `Mappers/`: array-to-DTO mapping helpers.
- `Routes/`: domain routes.

Use Actions from controllers to keep HTTP thin and concentrate business logic for reuse and testing.
