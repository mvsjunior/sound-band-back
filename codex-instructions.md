# Codex Front-End Helper: Sound Band API

This file explains how the backend works and how to integrate the Musical domain in the frontend.

## Base URL and Auth
- Base URL: `/api`
- All Musical routes are protected by `auth:api` middleware.
- Send `Authorization: Bearer <token>` on every request to `/api/musical/*`.
- Responses are JSON with `success`, `message`, and `data` fields.

## Response Shapes
- Success:
  ```json
  {"success": true, "message": "...", "data": {}}
  ```
- Validation/Errors:
  ```json
  {"success": false, "message": "...", "errors": {}}
  ```
- List endpoints return:
  ```json
  {
    "success": true,
    "data": {
      "items": [/* DTOs */],
      "pagination": {
        "page": 1,
        "perPage": 15,
        "total": 120,
        "lastPage": 8
      }
    }
  }
  ```

## Pagination
- Query params: `page`, `perPage`
- Defaults: `page=1`, `perPage=15`
- Max `perPage=100`

## Musical Domain Endpoints
All routes are under `/api/musical`.

### Music
- `GET /musical/music` list/filter music
  - Query: `name`, `artist`, `categoryId`, `id`, `page`, `perPage`
  - Response item fields:
    - `id`, `name`, `artist`, `category` (object), `lyrics` (object), `tags[]`, `chords[]`
- `GET /musical/music/show` by `id`
- `POST /musical/music/store`
- `PUT /musical/music/update`
- `DELETE /musical/music/delete`

### Categories
- `GET /musical/category` list
- `GET /musical/category/show` by `id`
- `POST /musical/category/store` (`name`)
- `PUT /musical/category/update` (`id`, `name`)
- `DELETE /musical/category/delete` (`id`)

### Tags
- `GET /musical/tags` list
- `GET /musical/tags/show` by `id`
- `POST /musical/tags/store` (`name`)
- `PUT /musical/tags/update` (`id`, `name`)
- `DELETE /musical/tags/delete` (`id`)

### Lyrics
- `GET /musical/lyrics` list
- `GET /musical/lyrics/show` by `id`
- `POST /musical/lyrics/store` (`content`, `musicId`)
- `PUT /musical/lyrics/update` (`id`, `content`)
- `DELETE /musical/lyrics/delete` (`id`)

### Chords
- `GET /musical/chords` list
  - Query: `id`, `musicId`, `tone`, `page`, `perPage`
- `GET /musical/chords/show` by `id`
- `POST /musical/chords/store`
- `PUT /musical/chords/update`
- `DELETE /musical/chords/delete`

### Musicians
- `GET /musical/musicians` list/filter musicians
  - Query: `name`, `email`, `phone`, `statusId`, `id`, `page`, `perPage`
  - Response includes `skills[]` with `{ id, name }`
- `GET /musical/musicians/show` by `id`
- `POST /musical/musicians/store`
- `PUT /musical/musicians/update`
- `DELETE /musical/musicians/delete`

### Playlists
- `GET /musical/playlists` list/filter playlists
  - Query: `playlistName`, `userId`, `page`, `perPage`
- `GET /musical/playlists/show` by `id`
- `POST /musical/playlists/store` (`name`, optional `musics[]`)
- `PUT /musical/playlists/update` (`id`, `name`, optional `musics[]`)
- `DELETE /musical/playlists/delete` (`id`)

### Skills
- `GET /musical/skills` list/filter skills
  - Query: `name`, `id`, `page`, `perPage`
- `GET /musical/skills/show` by `id`
- `POST /musical/skills/store` (`name`)
- `PUT /musical/skills/update` (`id`, `name`)
- `DELETE /musical/skills/delete` (`id`)

### Musician Skills (pivot)
Represents many-to-many between musicians and skills.
- `GET /musical/musician-skills` list/filter
  - Query: `musicianId`, `skillId`, `page`, `perPage`
- `GET /musical/musician-skills/show` by (`musicianId`, `skillId`)
- `POST /musical/musician-skills/store` (`musicianId`, `skillId`)
- `PUT /musical/musician-skills/update` (`musicianId`, `skillId`, `newMusicianId`, `newSkillId`)
- `DELETE /musical/musician-skills/delete` (`musicianId`, `skillId`)

### DTO Field Notes
- `SkillDTO`: `{ id, name }`
- `MusicianSkillDTO`: `{ musicianId, musicianName, skillId, skillName }`
- `MusicianDTO`: `{ id, name, email, phone, status: { id, name } }`
- `MusicianDTO` now includes `skills[]`: `{ id, name, email, phone, status: { id, name }, skills: [{ id, name }] }`
- `PlaylistDTO`: `{ id, name, userId, userName, musics: [{ id, name, position }] }`
- `MusicDTO`: `{ id, name, artist, category, lyrics, tags[], chords[] }`

## Front-End Implementation Suggestions
- Use a shared API client with base URL `/api` and auth header injection.
- Use consistent list views with filters + pagination controls.
- For Musician Skills UI:
  - Provide two dropdowns (musician, skill) for create/update.
  - When listing, show `musicianName` and `skillName` for readability.
- Handle validation errors by surfacing `errors` from the response.

## Example Request
```http
GET /api/musical/skills?page=1&perPage=10
Authorization: Bearer <token>
```

## Useful Postman Collection
- See `docs/sound-band.postman_collection.json` for example payloads.
