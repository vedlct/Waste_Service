# Deployment

How to run the MR. TEE site in production. Two apps are deployed:

- `backend/` — Laravel 13: the admin panel at `/admin` and the public API at `/api/v1`.
- `frontend/` — Next.js 16: the public website. It reads from and submits to the backend API.

They can live on different domains, for example `https://www.example.co.uk` for the site and
`https://admin.example.co.uk` for the backend. CORS is configured for that.

## Server requirements

Backend:

- PHP **8.4 or newer**. `composer.lock` pins Symfony 8.1, which needs PHP 8.4.1+; PHP 8.3 cannot install the dependencies.
- PHP extensions: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `curl`, `zip`, `gd`, `intl`.
- MySQL 8.
- Composer 2.
- A process manager (Supervisor on Linux) for the queue worker, and cron for the scheduler.

Frontend:

- Node.js 22 and npm.

## Backend `.env` checklist

Start from `backend/.env.example`. Every line below must be checked before go-live.

| Setting | Production value | Why |
| --- | --- | --- |
| `APP_ENV` | `production` | Enables the production safety checks, including the seeder guard. |
| `APP_DEBUG` | `false` | `true` shows stack traces and environment values to anyone who hits an error. |
| `APP_KEY` | generated once with `php artisan key:generate` | Encrypts sessions and cookies. Never change it after launch. |
| `APP_URL` | the backend's public `https://` URL | Used for the "Open in admin" links in office emails. |
| `LOG_LEVEL` | `warning` or `error` | `debug` fills the disk. |
| `DB_*` | the production MySQL credentials | Use a dedicated MySQL user, not `root`. |
| `SESSION_SECURE_COOKIE` | `true` | Admin session cookies are only sent over HTTPS. |
| `MAIL_MAILER` | `smtp` (or the provider's driver) | The default `log` writes emails to a file and sends nothing. |
| `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_SCHEME` | from the mail provider | Office alerts and booking confirmations depend on this. |
| `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` | a real address on the site's domain | Mail from `hello@example.com` will be rejected or marked as spam. |
| `QUEUE_CONNECTION` | `database` | All emails are queued; they are only sent while a worker runs. |
| `CORS_ALLOWED_ORIGINS` | the frontend's origin(s), comma separated, for example `https://www.example.co.uk` | The public API refuses browsers from any other origin. |
| `FRONTEND_URL` | the frontend's public URL | Shown as the page address in the admin's search preview. |
| `FRONTEND_API_KEY` | a long random value, the **same** as the frontend's | Lets the Next.js server read the API above the 60 per minute per-IP limit. Without it, page rendering is throttled and pages fall back to built-in content. |
| `MEDIA_DISK` | `public` | Where Media Library uploads are stored. |
| `ACTIVITY_RETENTION_DAYS` | `365` or the client's retention policy | How long the admin audit trail is kept. |
| `SEED_ADMIN_PASSWORD` | a strong password, **first deploy only** | Seeding production refuses to run without it. Remove it from `.env` afterwards. |
| `ALLOW_PRODUCTION_SEED` | `false` | Leave it off; see "Never re-seed production" below. |
| `CSP_ENABLED` / `CSP_REPORT_ONLY` | `true` / `false` | The admin's Content-Security-Policy. Report-only is an escape hatch, not a steady state. |

## Frontend environment

`frontend/.env.local` (or the hosting platform's environment settings):

| Setting | Value |
| --- | --- |
| `NEXT_PUBLIC_API_URL` | the backend's public URL, for example `https://admin.example.co.uk` |
| `NEXT_PUBLIC_SITE_URL` | the site's own public URL, for example `https://www.example.co.uk`; used for canonical links and `sitemap.xml` |
| `FRONTEND_API_KEY` | the same value as the backend's `FRONTEND_API_KEY`. Server-only: never prefix it with `NEXT_PUBLIC_`. |

The two `NEXT_PUBLIC_` settings are **inlined at build time**. Set them before `npm run build`, and rebuild whenever they change.

## First deploy

Backend, from `backend/`:

```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env            # then fill in every value from the checklist above
php artisan key:generate
php artisan migrate --force
SEED_ADMIN_PASSWORD='a-strong-password' php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Then:

1. Sign in at `/admin/login` as `admin@mrtee.local` with the seeded password.
2. Under **Users**, create a named super admin account for each real person, then deactivate or delete the seed account.
3. Under **Site Settings → Notifications**, set the office alert email.

Frontend, from `frontend/`:

```bash
npm ci
npm run build
npm run start      # or deploy the build to the hosting platform
```

## Later deploys

Backend:

```bash
php artisan down
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart          # the worker picks up the new code
php artisan up
```

Frontend: `npm ci && npm run build`, then restart or redeploy.

### Never re-seed production

`db:seed` upserts the catalogue by slug. Running it on a live site would reset every price,
service, FAQ and review an admin has edited back to the seed values. The seeder therefore
**refuses to run in production once the database has data**. Only set
`ALLOW_PRODUCTION_SEED=true` for a deliberate, backed-up one-off.

Re-seeding never changes an existing admin account's password.

## Long-running processes

### Queue worker (required)

Every email (office alerts for enquiries, bookings and reviews, the customer booking
confirmation, and the customer status updates) is queued. **Without a running worker, no email is ever sent.**

Supervisor example (`/etc/supervisor/conf.d/mrtee-worker.conf`):

```ini
[program:mrtee-worker]
command=php /var/www/mrtee/backend/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/mrtee/backend/storage/logs/worker.log
stopwaitsecs=3600
```

Failed emails are kept in `failed_jobs`. Check them with `php artisan queue:failed` and retry with
`php artisan queue:retry all`.

### Scheduler (required)

The scheduler prunes the admin audit trail daily. Add to the web user's crontab:

```cron
* * * * * cd /var/www/mrtee/backend && php artisan schedule:run >> /dev/null 2>&1
```

## Web server

- Serve the backend with the document root at `backend/public`. Nothing else in `backend/` may be web-reachable.
- Serve everything over HTTPS. Add `Strict-Transport-Security: max-age=31536000; includeSubDomains` at the web server once HTTPS is confirmed working. It is deliberately not set by the app.
- The app already sends `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy` and `Permissions-Policy` on every response.
- The admin and sign-in pages send a nonce-based Content-Security-Policy (`config/security.php`). Scripts may only come from the app itself, the four allow-listed CDNs, or an inline block carrying the request's nonce.
  - If a browser console ever reports a CSP violation after a change, set `CSP_REPORT_ONLY=true` to stop blocking while it is fixed. `CSP_ENABLED=false` switches the header off entirely.
  - Every new inline script must be written `<script @nonce>`. `ContentSecurityPolicyTest` renders every admin page and fails on one without it.

## Backups

What to back up:

1. The MySQL database — bookings, enquiries, the catalogue, settings, users, and the audit trail.
2. `backend/storage/app/public` — every file uploaded through the Media Library.

Database, daily, kept for at least 30 days:

```bash
mysqldump --single-transaction --routines --no-tablespaces -u backup_user -p mrtee_production \
  | gzip > /backups/mrtee-$(date +%F).sql.gz
```

Uploads, daily:

```bash
tar -czf /backups/mrtee-uploads-$(date +%F).tar.gz -C /var/www/mrtee/backend/storage/app public
```

Keep at least one copy off the server. **Test a restore** before launch and then periodically:

```bash
gunzip < /backups/mrtee-YYYY-MM-DD.sql.gz | mysql -u root -p mrtee_restore_test
```

## Before launch

- [ ] The client has confirmed every catalogue price in the admin and moved it from `placeholder` to `confirmed`. The admin lists warn about any that are left.
- [ ] Every coverage area that should pass the postcode check has a postcode prefix. The admin list warns about any that are missing.
- [ ] The office alert email is set, and a real test enquiry, review and booking each arrived by email.
- [ ] The customer booking confirmation and status update emails read correctly, or have been switched off in Site Settings → Notifications.
- [ ] The seed super admin account has been replaced by named accounts.
- [ ] A decision has been made on the voucher box on the payment page, which does nothing yet.
- [ ] Payments are deferred: pay-now bookings arrive as drafts and are chased by hand. The office knows this, or the payment option has been limited to pay-on-arrival.
- [ ] A database restore has been tested.

## Monitoring

- Application errors: `backend/storage/logs/laravel.log`. Consider shipping it to an error tracker.
- Stuck email: `php artisan queue:failed`, and the `jobs` table growing means the worker is down.
- Admin changes: the **Activity Log** screen (super admins only) records who changed what, including every price and booking status change.
