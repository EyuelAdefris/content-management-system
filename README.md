# FullStack CMS — Project Summary

Professional summary and usage guide for the FullStack Content Management System (CMS).

## Project overview

This repository contains a full-featured CMS built with Laravel and modern frontend tooling (Vite/Tailwind). The system supports pages, posts, media uploads, banners, menus, roles/permissions and notifications. It is production-ready for internal demos and evaluation.

## Key features

- Content types: Pages, Posts, Banners, Media
- Menu management with nested `MenuItem`s
- User management with roles & permissions
- File uploads and media library
- Notifications for content changes and new posts
- RESTful controllers and blade views for admin UI

## Tech stack & architecture

- Backend: PHP 8.x, Laravel
- Frontend: Vite, Tailwind CSS
- DB: MySQL / MariaDB (configurable in `.env`)
- Queue: Laravel queue (configurable)

## Important files & locations

- Application controllers: `app/Http/Controllers/`
- Models: `app/Models/` (e.g., `Page.php`, `Post.php`, `Media.php`)
- Routes: `routes/web.php`, `routes/auth.php`
- Migrations: `database/migrations/`
- Factories/Seeders: `database/factories/`, `database/seeders/`

## Setup (Windows / PowerShell)

Prerequisites:

- PHP 8.x and Composer
- Node.js and npm
- MySQL or compatible DB

Quick start commands (run from project root in PowerShell):

```powershell
# install PHP dependencies
composer install

# copy env and set values
copy .env.example .env
# edit .env to set DB credentials and APP_URL

# generate app key
php artisan key:generate

# run migrations and seed sample data
php artisan migrate --seed

# install frontend deps and build (dev)
npm install
npm run dev

# serve application (local)
php artisan serve --host=127.0.0.1 --port=8000
```

If you prefer a fresh DB and seeded demo data:

```powershell
php artisan migrate:fresh --seed
```

## Default/demo credentials

Use these demo credentials for initial access (change immediately on production):

- **Admin email**: `admin@example.com`
- **Admin password**: `Password`

**Editor email**: `editor@example.com`
**Editor password**: `Password`

Notes:

- Credentials are created by seeders. To change them, update the seeder in `database/seeders/` or create a new user via Tinker:

```powershell
php artisan tinker
>>> \App\Models\User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('Password')]);
```

## How to access & common URLs

- Application homepage: `http://127.0.0.1:8000/`
- Admin / login page: `http://127.0.0.1:8000/login` (default auth)

After login, use the admin navigation to access Pages, Posts, Media, Banners and Menus.

## Migrations & seeders

- Run migrations: `php artisan migrate`
- Run seeders: `php artisan db:seed`
- To rebuild DB with seed data: `php artisan migrate:fresh --seed`

## Testing

- Run PHP unit tests: `vendor/bin/phpunit` or `php artisan test`

## Security & production notes

- Replace demo credentials before production.
- Secure `storage/` and `public/uploads` directories with proper permissions.
- Use HTTPS in production and set `APP_ENV=production` and `APP_DEBUG=false` in `.env`.
- Configure queue workers and caching for performance.

## Administration & maintenance

- Create additional admin users via the app or `php artisan tinker`.
- Back up the database regularly; use `mysqldump` or managed backups.

## Contact / Support

If you need help or want a live demo, contact the project owner.

---

This `README.md` covers the main functional aspects and quick steps to run the system locally. For deeper developer notes, review the controllers, models and migration files referenced above.

# content-management-system
