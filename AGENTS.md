# Repository Guidelines

## Project Structure & Module Organization
- `app/`: application logic (controllers, models, services).
- `routes/`: HTTP route definitions (`web.php`, `api.php`).
- `resources/`: frontend assets and views (Vite + Tailwind).
- `database/`: migrations, factories, seeders.
- `tests/`: PHPUnit test suite.
- `config/`: environment-driven configuration.
- `public/`: web entry point and static assets.

## Build, Test, and Development Commands
- `composer install`: install PHP dependencies.
- `npm install`: install frontend tooling.
- `composer run setup`: initial setup (.env, key, migrate, build).
- `composer run dev`: run Laravel server, queue worker, log tail, and Vite.
- `php artisan serve`: run the backend only (no Vite).
- `npm run dev`: Vite dev server for frontend assets.
- `npm run build`: production asset build.
- `composer run test` or `php artisan test`: run the test suite.

## Coding Style & Naming Conventions
- PHP: follow PSR-12 conventions; use 4 spaces for indentation.
- Run `vendor/bin/pint` to format PHP code.
- Class names: `StudlyCase`; methods/variables: `camelCase`.
- File naming: match class names; migration files use timestamp prefixes.

## Testing Guidelines
- Framework: PHPUnit via Laravel’s `artisan test`.
- Tests live in `tests/` and should be named `*Test.php`.
- No coverage threshold is defined; add tests for new endpoints and core logic.

## Commit & Pull Request Guidelines
- Commit messages are short, descriptive, and written in Portuguese (no formal prefixing).
- PRs should include: a concise description, linked issue (if any), and testing notes.
- Add screenshots or curl examples for API changes when behavior is user-facing.

## Configuration & Security Tips
- Copy `.env.example` to `.env` and keep secrets out of git.
- Use `storage/` for runtime logs/uploads; avoid committing generated files.
- JWT and Sanctum are in use; document auth changes in PR descriptions.
