# AGENTS.md

## Quick Start

```bash
composer dev          # Full dev environment (artisan serve + queue:listen + pail + vite)
composer test         # Clear config cache + run Pest/PHPUnit
npm run dev           # Vite dev server only
npm run build         # Production asset build
./vendor/bin/pint     # PHP formatting (Laravel Pint, default rules)
```

## Architecture

- **Laravel 12** backend (PHP 8.2+) + **Vue 3 SPA** frontend.
- Single catch-all route in `routes/web.php` renders `resources/views/app.blade.php` shell. All data flows through `/api/*`.
- Frontend uses **file-based routing** via `vite-plugin-pages` — routes are generated from `resources/js/pages/` directory tree. The file path IS the route.
- Vite alias: `@` resolves to `resources/js`.
- `composer dev` runs 4 concurrent processes: `artisan serve`, `artisan queue:listen --tries=1`, `artisan pail`, and `npm run dev`.

## Gotchas

- **No JS linter/formatter** — no ESLint, Prettier, or TypeScript config exists. Only PHP has Laravel Pint.
- **No static analysis** — no PHPStan, Psalm, or TypeScript. PHP and JS are both effectively untyped.
- **Tests are placeholders** — only `ExampleTest.php` stubs in `tests/Unit/` and `tests/Feature/`. No real coverage.
- **`RefreshDatabase` is commented out** in `tests/Pest.php` — tests do NOT auto-reset DB state.
- **Queued mailables** — `DonationReceipt` implements `ShouldQueue`. Queue worker must be running to send emails.
- **Dynamic config from DB** — `SettingsServiceProvider` overrides `config()` at boot. Stripe keys, SSLCommerz credentials, and mail settings come from the `settings` table, not `.env`.
- **Dual payment gateways** — SSLCommerz (BDT, Bangladesh) and Stripe (USD, international). Routing logic is in `DonationController`.
- **Bilingual content** — UI strings via vue-i18n (`resources/js/translations/en.json` / `bn.json`). Content fields stored as duplicate columns (`title`/`title_bn`). Use `useLangStore().f()` to pick the right field based on current locale.
- **Legacy `fbf/` directory** — static HTML from an earlier version of the site. Not part of the Vue app; ignore it.

## Key Paths

| Path | What lives here |
|------|----------------|
| `app/Http/Controllers/Admin/` | 11 admin CRUD controllers |
| `app/Http/Controllers/` | 8 public controllers (Auth, Donation, Project, Gallery, Video, CmsPage, SSLCommerz, Stripe) |
| `app/Services/` | `StripeService`, `SSLCommerzService` (payment gateway abstractions) |
| `app/Models/` | Eloquent models: User, Project, Donation, GalleryImage, Video, CmsPage, Role, Permission, Setting |
| `app/Http/Middleware/` | `AdminMiddleware`, `PermissionMiddleware` |
| `resources/js/pages/` | File-based Vue routes — source of truth for frontend routing |
| `resources/js/stores/` | 4 Pinia stores: auth, admin, lang, theme |
| `routes/api.php` | All REST API endpoints (Sanctum-authenticated) |
| `database/migrations/` | 20 migration files |
| `database/seeders/` | RolePermissionSeeder (4 roles, 27 permissions), ContentSeeder, SettingsSeeder |
| `config/stripe.php`, `config/sslcommerz.php` | Payment gateway config (env-driven, overridden by DB settings) |

## RBAC

Custom role-based access control (not Spatie). Four seeded roles: `admin`, `manager`, `editor`, `user`. 27 granular permissions. Admin panel access gated by `AdminMiddleware` (checks `isAdmin()`). Fine-grained actions gated by `PermissionMiddleware` with named permissions. Admin Sanctum tokens use the `['admin']` ability.

## Docker

`docker-compose.yml` defines 4 services on `fbf-network`: `app` (PHP 8.3-FPM), `web` (Nginx, port `${APP_PORT:-8000}`), `db` (MySQL 8, port `${DB_PORT:-3306}`, database `filebase`), `node` (Node 22, runs `npm install && npm run dev` on port 5173). Custom configs in `docker/nginx.conf` and `docker/php.ini` (20M upload, 256M memory, Asia/Dhaka timezone).
