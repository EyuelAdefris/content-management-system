# FullStack CMS — Evaluation Guide

Welcome to the Content Management System (CMS). This system is a fully functional, production-ready backend built with Laravel, designed for high performance, modern UI/UX, and secure content management.

## 🚀 Quick Access (Manager Evaluation)

You can access the live production system to test the functionality here:
**Live URL:** [https://content-management-system-production-1e86.up.railway.app](https://content-management-system-production-1e86.up.railway.app)

*(If testing locally, use `http://127.0.0.1:8000/login`)*

### Test Credentials
Please use the following credentials to log in and evaluate the system:
- **Admin Email:** `admin@example.com`
- **Admin Password:** `Password`

- **Editor Email:** `editor@example.com`
- **Editor Password:** `Password`

---

## 🌟 Core Functionality Overview

This CMS is equipped with enterprise-grade features that you can test immediately:

1. **Modern, Responsive Dashboard**
   - Click the **Dark Mode / Light Mode** toggle (Sun/Moon icon) in the top right to see the UI flawlessly adapt.
   - Professional, non-intrusive **SweetAlert popup notifications** (slide-in toasts for success messages, and sleek centered dialogs for delete confirmations).

2. **Cloud Media Library**
   - Navigate to **Media** and try uploading an image. 
   - The system is fully integrated with **Cloudinary**. Images are instantly securely transferred to the cloud and served globally via CDN, ensuring lightning-fast load times and zero data loss during server deployments.

3. **Content Management**
   - **Pages & Posts:** Create, edit, and format content using the integrated WYSIWYG editor.
   - **Banners:** Manage homepage marketing banners seamlessly.
   - **Menus:** Build complex, nested navigation menus dynamically from the admin panel.

4. **Role-Based Access Control (RBAC)**
   - The **Admin** account has full control, including the ability to manage other Users and assign roles.
   - The **Editor** account has restricted permissions focused purely on content creation.

---

## Tech Stack & Architecture

- **Backend:** PHP 8.x, Laravel 11/10
- **Frontend:** Tailwind CSS, AlpineJS, SweetAlert2
- **Storage:** Cloudinary (Cloud Storage / CDN)
- **Database:** MySQL / MariaDB

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
