# AGENTS.md

## Cursor Cloud specific instructions

### Overview

Mumaapix is a photography studio management app built with **Laravel 10**, **Livewire 2**, and **Bootstrap 5** (Material Dashboard 2 theme). It manages client bookings, photography pipelines, M-Pesa payments, and role-based dashboards (admin/photographer/editor).

### System dependencies (pre-installed in snapshot)

- PHP 8.1 (from `ppa:ondrej/php`) with extensions: mbstring, xml, zip, curl, sqlite3, mysql, gd, bcmath, intl, dom
- Composer 2.x at `/usr/local/bin/composer`
- MySQL 8.0 (Ubuntu package)
- `doctrine/dbal` (added to `composer.json` — required by existing migrations)

### Starting MySQL

MySQL must be started manually (no systemd in container):

```bash
sudo mysqld --user=mysql --bind-address=127.0.0.1 --port=3306 \
  --datadir=/var/lib/mysql --socket=/var/run/mysqld/mysqld.sock &
sleep 3
```

After starting, set non-strict SQL mode (the `DatabaseSeeder` factory omits the `role` column):

```bash
sudo mysql -h 127.0.0.1 -u root -e "SET GLOBAL sql_mode='NO_ENGINE_SUBSTITUTION';"
```

### Database setup

```bash
sudo mysql -h 127.0.0.1 -u root -e "CREATE DATABASE IF NOT EXISTS material_free_livewire;"
php artisan migrate --no-interaction
```

The default `DatabaseSeeder` fails in MySQL strict mode because the `UserFactory` does not set the `role` column. Seed users via tinker instead:

```bash
php artisan tinker --execute="
\App\Models\User::create(['name'=>'Admin','email'=>'admin@material.com','password'=>'secret','role'=>'admin']);
\App\Models\User::create(['name'=>'Editor','email'=>'editor@mumaapix.com','password'=>'secret','role'=>'editor']);
\App\Models\User::create(['name'=>'Photographer','email'=>'photographer@mumaapix.com','password'=>'secret','role'=>'photo']);
"
```

### Running the dev server

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Default login: `admin@material.com` / `secret`

### Tests

```bash
php artisan test
```

The feature test `Tests\Feature\ExampleTest` fails (302 vs 200) because `/` redirects to `/sign-in` — this is a pre-existing issue.

### Lint

No dedicated linter is configured in the codebase (no PHPStan, Pint, or PHP-CS-Fixer). Syntax checking can be done via `php -l` on individual files.

### Key caveats

- Migrations use MySQL-specific syntax (`CHARACTER SET`, `COLLATE`, `AFTER`), so SQLite cannot be used as the dev database.
- The `.env` file must use `DB_CONNECTION=mysql` with `DB_USERNAME=root` and empty password for the local MySQL instance.
- `php artisan storage:link` must be run once to create the `public/storage` symlink.
- The M-Pesa payment route is commented out (under maintenance) in `routes/web.php`.
