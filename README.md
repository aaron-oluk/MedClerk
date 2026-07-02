# MedClerk

MedClerk is a clinical education and competency assessment platform for medical students. It combines an interactive library of clinical signs and examination techniques with digital, curriculum aligned assessment tools, giving lecturers a way to standardize scoring and giving students structured feedback on their clinical rotations. It is designed for multi institution deployment, with role based access and a full audit trail on every academic record.

## Tech stack

- Backend: Laravel 12 (PHP 8.2+), RESTful API secured with Laravel Sanctum for the mobile client
- Web frontend: Laravel Blade with Alpine.js for interactivity and Tailwind CSS for styling
- Database: PostgreSQL
- Auth: Laravel Breeze session auth for the web app, Sanctum tokens for API clients

## Core features

- **Clinical learning library**: clinical systems, individual clinical signs with interpretation and diagnostic relevance, multimedia links, and searchable tags
- **Skills**: step by step procedure tutorials mapped to competency codes
- **Digital logbook**: students record clinical encounters against their active rotation, with structured findings captured as distinct fields rather than one block of free text
- **Assessment engine**: curriculum aligned scoring tied to a specific student, skill, and rotation, recorded by the supervising lecturer
- **Feedback**: structured feedback covering strengths, areas to improve, and a follow up date
- **Rotations**: clerkship placements linking a student, supervisor, department, and institution
- **Programs and cohorts**: curricula and student batch tracking over time, with cohort enrollment
- **Institutions**: multi institution support built in from the start
- **Dashboard**: role aware summary of activity, scoped to what each role is allowed to see
- **Role based access**: student, lecturer, admin, and superadmin roles enforced through policies at the controller layer, not just the frontend
- **Audit trail**: every write to assessments, logbook entries, and feedback is recorded in an audit log for institutional accreditation review

## Requirements

- PHP 8.2 or newer, with the pgsql extension
- Composer
- Node.js and npm
- PostgreSQL 13 or newer

## Setup

Clone the repository and install dependencies:

```bash
composer install
npm install
```

Copy the environment file and generate an app key:

```bash
cp .env.example .env
php artisan key:generate
```

Create a PostgreSQL database and role matching your `.env` file (the defaults are `medclerk` for both the database name and username):

```bash
createdb medclerk
psql -d postgres -c "CREATE ROLE medclerk WITH LOGIN PASSWORD 'medclerk' CREATEDB;"
psql -d medclerk -c "GRANT ALL ON SCHEMA public TO medclerk;"
```

Update `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env` if you used different values.

Run migrations and seed demo data:

```bash
php artisan migrate --seed
```

Build frontend assets:

```bash
npm run build
```

Start the app:

```bash
php artisan serve
```

The app is now available at `http://localhost:8000`. For local development with hot reloading, run `npm run dev` in a separate terminal instead of `npm run build`.

## Demo accounts

The seeder creates one account per role, all with the password `password`:

| Role | Email |
| --- | --- |
| Superadmin | `superadmin@medclerk.test` |
| Admin | `admin@medclerk.test` |
| Lecturer | `lecturer@medclerk.test` |
| Student | `student@medclerk.test` |

Seed data also includes one institution (Kabale University), a program and cohort, a clinical system with a sign, a skill, a rotation linking the demo lecturer and student, a logbook entry, an assessment, and feedback, so the app has real data to look at right away.

## Project structure

- `app/Http/Controllers`: web controllers rendering Blade views (session authenticated, role scoped)
- `app/Http/Controllers/Api`: JSON API controllers for the mobile client (Sanctum token authenticated)
- `app/Policies`: authorization rules shared by both the web and API layers
- `app/Models`: Eloquent models for every domain entity
- `resources/views`: Blade templates, organized by feature (`rotations`, `logbook-entries`, `clinical-systems`, `skills`, `assessments`, `feedback`, `programs`, `institutions`)
- `database/migrations`: schema definitions
- `database/seeders`: one seeder per entity, each independently rerunnable via `php artisan db:seed --class=SeederName`
- `routes/web.php`: browser facing routes
- `routes/api.php`: mobile facing REST API, all route names prefixed with `api.` to avoid colliding with the web routes of the same name

## API access

Mobile or other API clients authenticate by posting an email, password, and device name to `POST /api/auth/login`, which returns a Sanctum token. Send that token as a `Bearer` header on subsequent requests to endpoints such as `/api/rotations`, `/api/logbook-entries`, `/api/assessments`, and `/api/feedback`.

## Regenerating IDE helper files

This project uses `barryvdh/laravel-ide-helper` in development to resolve ambiguous Eloquent method signatures for static analysis tools. If your editor reports missing methods after adding new models or changing existing ones, regenerate the helper files:

```bash
php artisan ide-helper:generate
php artisan ide-helper:models --nowrite
```

These files are gitignored and safe to regenerate at any time.

## Tests

```bash
php artisan test
```
