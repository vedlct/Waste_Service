# Backend Project Plan

This file is the canonical working plan for the Laravel backend, admin panel, CMS, and public APIs. Keep it updated after every backend-related change so future work can continue from the current project state instead of chat history.

Last updated: 2026-09-18

## Purpose

Build a Laravel backend that provides:

- A modern admin panel for managing website content, services, pricing, enquiries, reviews, bookings, settings, media, and areas.
- Public APIs for the Next.js frontend to consume dynamic content and booking/catalogue data.
- A clean database foundation with indexes, stable relationships, and order snapshots.
- A maintainable workflow where modules are implemented one at a time and verified before expanding.

## Current Admin Direction

The first admin design has been approved by the user.

Use this direction for future admin modules:

- Bootstrap latest via CDN for layout/components.
- DataTables via CDN for browser table UI.
- Yajra DataTables installed through Composer for server-side Laravel table responses.
- Select2 via CDN where enhanced dropdowns help.
- Toastr via CDN for success/error notifications.
- Frontend brand feel: MR. TEE logo, navy `#11224D`, sky blue `#0497E2`, amber `#F4B942`, clean white panels, soft blue-gray backgrounds.
- Keep modules practical and admin-focused, not marketing-page styled.
- Build module by module. Do not implement all backend modules at once.

## Completed

### Database Foundation

- Added indexed migrations for:
  - Admin user metadata.
  - Media assets.
  - Site settings.
  - Pages, page sections, section items.
  - Media assignments.
  - Service categories.
  - Services.
  - Service relations.
  - Service content blocks and block items.
  - Price categories.
  - Service items.
  - Load packages.
  - Extra charges.
  - Coverage regions and areas.
  - FAQs.
  - Reviews.
  - Contact enquiries.
  - Bookings.
  - Booking addresses.
  - Booking items.
  - Booking payments.

- Added database design notes at `database-design.md`.
- Money is stored in integer pence.
- VAT rates are stored in basis points.
- Booking item rows snapshot names/prices/details so historical bookings survive catalogue changes.

### Seed Data

- Replaced the default Laravel seeder with an idempotent project seeder.
- Seeded:
  - Admin user.
  - Media asset references.
  - Site settings.
  - Pages.
  - Service categories.
  - Services.
  - Service overview blocks/items.
  - Price categories.
  - Service item prices.
  - Load packages.
  - Extra charges.
  - Coverage regions/areas.
  - FAQs.
  - Reviews.

- Local admin credentials:
  - Email: `admin@mrtee.local`
  - Password: `password`

### Auth And Admin Shell

- Installed `laravel/ui` with Composer.
- Generated Laravel auth scaffolding.
- Disabled registration with `Auth::routes(['register' => false])`.
- Redirected `/` and `/home` into the admin flow.
- Set auth redirects to `/admin`.
- Created a custom modern login screen using CDN assets and frontend imagery.
- Extracted the login screen treatment into a reusable auth shell partial.
- Redesigned password reset link, password reset, and password confirmation screens to match the custom login/auth treatment.
- Created an admin layout with:
  - Sidebar.
  - Topbar.
  - Dashboard area.
  - CDN Bootstrap.
  - CDN Bootstrap Icons.
  - CDN DataTables.
  - CDN Select2.
  - CDN toastr.
- Added a logged-in admin profile screen.
- Added profile details update.
- Added current-password-protected password update.
- Extracted profile details/password validation into dedicated Form Request classes.
- Added shared Bootstrap delete confirmation modal behavior for admin screens.
- Added `admin` route middleware to block inactive users and non-admin roles from admin routes.
- Added shared admin controller flash helpers for success/error toastr messages.
- Added shared admin DataTables helpers for table creation, partial rendering, badge rendering, and date formatting.
- Added reusable admin Blade partials for:
  - Page section headers with action slots.
  - Standard page panels.
  - Table cards.
  - Empty states.
  - Breadcrumbs.
  - Status badges.
  - Publish toggles.
  - Sort order fields.
  - Image preview upload fields.
  - SEO fields.
- Refactored Dashboard, Users, and Profile screens to use the shared admin partials.
- Added breadcrumb trails for Dashboard, Profile, and Users screens.
- Replaced the Users-only badge partial with the shared admin status badge partial.
- Refactored the Users server-side DataTables endpoint to use shared admin table helpers.

### Users Module

- Added `/admin/users`.
- Added Yajra DataTables server-side endpoint at `/admin/users/data`.
- Added Users list table.
- Added create user page.
- Added edit user page.
- Added delete user action.
- Blocked deleting the currently logged-in user.
- Added roles: `super_admin`, `admin`, `editor`.
- Added statuses: `active`, `inactive`.
- Updated `User` model fillable/casts for admin metadata.
- Replaced native browser delete confirmation with the shared Bootstrap delete modal.
- Extracted Users create/update validation into dedicated Form Request classes.

### Assets

- Copied required frontend assets into backend public assets:
  - `public/images/MainLogo.png`
  - `public/images/HeroImage.jpg`
- Added media upload configuration at `backend/config/media.php`.
- Documented the upload storage approach in `docs/media-storage.md`.

### Media Library

- Added `MediaAsset` model for the existing `media_assets` table.
- Added `/admin/media` Media Library list screen.
- Added `/admin/media/data` Yajra DataTables endpoint.
- Added `/admin/media/create` upload screen.
- Added `/admin/media` upload handler.
- Added media preview and file detail partials.
- Added Media sidebar navigation.
- Reused the shared admin DataTables helpers and status badge partials.
- Added config-driven media upload validation for images and PDFs.
- Stored uploads on the configured media disk under dated Media Library folders.
- Added `/admin/media/{media}/edit` details screen with preview, file facts, and usage panel.
- Added `/admin/media/{media}` update handler for alt text and metadata.
- Added configurable metadata fields (`title`, `caption`, `credit`) at `config/media.php` stored in the `metadata` JSON column.
- Preserved metadata keys that are not admin-editable when saving the edit form.
- Added `/admin/media/{media}` delete handler that removes the stored file and the library record.
- Added a config-driven `usage_references` map of every table/column that points at `media_assets`.
- Added `MediaAsset::scopeWithUsageCount()`, `usageBreakdown()`, `usageCount()`, and `isInUse()`.
- Blocked deleting media that is still referenced, with an error toast naming the referencing modules.
- Added a `Usage` column and row actions (open, edit, delete) to the Media Library table.
- Skipped disk deletion for seeded assets that point at bundled public files or remote URLs.

### Site Settings Module

- Added `SiteSetting` model for the existing `site_settings` table with `group`, `public`, and `ordered` scopes.
- Added `/admin/settings/{group?}` grouped settings screen with pill navigation.
- Added `/admin/settings/{group}` update handler wrapped in a transaction.
- Added `config/settings.php` for group labels, icons, and descriptions.
- Groups found in the database but missing from config still render with a humanised label.
- Added per-setting input types: `string`, `text`, `integer`, `money`, `boolean`, `url`, `email`.
- Integer settings whose key ends in `_pence` render as pound inputs and are stored back as pence.
- Added per-setting validation driven by the input type via `SiteSetting::inputRules()`.
- Added a per-setting `is_public` toggle so the future public API only exposes approved values.
- Recorded `updated_by` on every saved setting.
- Added Site Settings sidebar navigation.
- Seeded settings now keep admin-edited values on re-seed; re-seeding only refreshes `type`, `label`, and `help_text`.
- Added seeded help text for every setting and a new `social` group (Facebook, Instagram, LinkedIn, X).
- Changed the seeded `contact.email` type from `string` to `email`.

### Shared Admin Infrastructure Added With Services CMS

- Added `GuardsDeletions` controller concern that builds the standard "still in use" error message from a label/count map. Media, and now service categories, both use it.
- Added a reusable media picker partial at `resources/views/admin/partials/media-picker.blade.php`.
- Added `/admin/media/options`, a paginated Select2 source that searches by original name, alt text, and path.
- The picker renders thumbnails in the dropdown and swaps the preview frame on selection; the wiring lives in the admin layout so every picker works without per-page JavaScript.
- Made `admin.partials.seo-fields` reusable: `showKeywords`, `heading`, and `description` are now options, so modules whose table has no keywords column can use it.
- Dashboard service count now goes through the `Service` model, so soft deleted services are excluded.

### Services CMS

- Added models: `Page`, `ServiceCategory`, `Service`, `ServiceContentBlock`, `ServiceBlockItem`.
- `Service::relatedServices()` uses `withPivotValue('relation_type', 'related')` so syncing never deletes other relation types stored in `service_relations`.
- Added Service Categories CRUD at `/admin/service-categories` with a Yajra DataTables endpoint.
- Categories support parent nesting, icon, description, active flag, and sort order.
- Blocked deleting a category that still has services or sub categories.
- Blocked making a category its own parent or moving it under one of its own descendants.
- Added Services CRUD at `/admin/services` with a Yajra DataTables endpoint showing hero thumbnail, category, status, flags, and block count.
- Service form covers hero fields (headline, summary, hero image, description), category, linked page, status, featured, bookable, and sort order.
- Publishing stamps `published_at` once; moving back to draft clears it; archiving keeps the original stamp.
- Related services are managed from the service form and synced with pivot sort order.
- Service SEO is stored on the linked `pages` row, so the SEO panel writes `meta_title` and `meta_description` there and is ignored when no page is linked.
- Services are soft deleted, so bookings and history survive.
- Added Service Content Blocks CRUD nested under a service at `/admin/services/{service}/blocks`.
- Added Service Block Items CRUD nested under a block, listed on the block edit screen.
- Block keys are unique per service and are derived from the heading when left blank.
- Added `config/service_cms.php` for the selectable block components; an unknown legacy component stays selectable so editing never silently rewrites it.
- Nested controllers verify ownership and return 404 when a block or item does not belong to its parent.
- Added Services and Service Categories sidebar navigation.

### Pricing Catalogue

- Added `App\Support\Money` as the single place pounds, pence, VAT basis points, and ex-VAT prices are converted. `SiteSetting` now uses it too.
- Added `config/pricing.php` holding the pricing status workflow (`confirmed`, `active`, `placeholder`, `quote_required`), the charge types, and the default VAT rate.
- Added the `HasPricingStatus` model concern with `pricingStatuses()`, `withPricingStatus()` scope, and `needsPriceReview()`.
- Added models: `PriceCategory`, `ServiceItem`, `LoadPackage`, `ExtraCharge`.
- Added reusable admin partials: `money-field` (pounds input), `pricing-status-field` (status select with descriptions), `pricing-status-badge` (warning icon on statuses that need review), and `placeholder-warning` (list-level alert).
- Added Price Categories CRUD at `/admin/price-categories`, with the media picker for the category image.
- Blocked deleting a price category that still has service items, including soft deleted ones, because `service_items.price_category_id` cascades on delete.
- Added Service Items CRUD at `/admin/service-items` with category and pricing-status filters on the DataTable.
- Service item prices are entered in pounds and stored in `price_pence`; the VAT rate is entered as a percent and stored in basis points.
- Service items show inc-VAT, ex-VAT, and the VAT rate on the list; ex-VAT is derived, not stored.
- A `quote_required` item is rejected if it carries a non-zero price, because `price_pence` is an unsigned column that still needs a value.
- Added Load Packages CRUD at `/admin/load-packages` covering weight, volume, sack equivalent, and loading time.
- A load package's ex-VAT price is derived from the inc-VAT price and the VAT rate when the admin leaves it blank.
- Added Extra Charges CRUD at `/admin/extra-charges` with the charge type and variable flag.
- A fixed charge must have an amount; a variable charge must not, and its stored `amount_pence` is forced to null.
- Service items and load packages are soft deleted so booking snapshots keep resolving; price categories and extra charges are hard deleted.
- Lists warn at the top when any record still uses a placeholder price, and the edit form repeats the warning for that record.
- Added Price Categories, Service Items, Load Packages, and Extra Charges sidebar navigation.
- Dashboard service and price item counts now go through the models, so soft deleted rows are excluded.

### FAQs And Reviews

- Added `Faq` and `Review` models.
- Added `config/reviews.php` holding the moderation statuses (`pending`, `published`, `rejected`, `spam`), the review sources, and the max rating.
- Exactly one status carries `publishes => true`; `Review::publishedStatus()` and the `published` scope read it from config rather than hard coding the string.
- Added FAQ CRUD at `/admin/faqs` with a DataTable filtered by scope (general only) and by service.
- FAQs with no `service_id` are general and belong on the FAQ page; assigning a service moves the FAQ to that service page.
- Added a shared `admin.partials.rating-stars` partial.
- Added Review CRUD at `/admin/reviews` with status, rating, and service filters.
- Added `PATCH /admin/reviews/{review}/moderate` for one-click publish and reject straight from the list.
- Moderation timestamps follow the status and are never edited by hand: `pending` clears both, a publishing status stamps `reviewed_at` and `published_at`, and any other reviewed status keeps `reviewed_at` while clearing `published_at`.
- The reviews list warns when reviews are waiting for moderation and shows how many are live.
- Added FAQs and Reviews sidebar navigation.

### Coverage Areas

- Added `CoverageRegion` and `CoverageArea` models.
- Added Coverage Regions CRUD at `/admin/coverage-regions`, showing the area count and how many are featured.
- Blocked deleting a region that still has areas, because `coverage_areas.coverage_region_id` cascades on delete.
- Added Coverage Areas CRUD at `/admin/coverage-areas` as a flat list with region, featured, and missing-postcode filters.
- Postcode prefixes are normalised to uppercase with spaces stripped, then validated against the UK outward-code shape (`PO1`, `SW1A`, `E14`).
- Latitude and longitude must be set as a pair or left blank; the edit screen links to the coordinates on a map.
- The areas list warns when areas still have no postcode prefix, because that is what a future serviceability check matches on.
- Added `CoverageArea::scopeMatchingPostcode()` so the Phase 10 booking serviceability check has one place to call.
- Added Coverage Regions and Coverage Areas sidebar navigation.

### Public API Skeleton

Brought forward from Phase 12 because the contact enquiry submit endpoint needed somewhere to live.

- Registered `routes/api.php` in `bootstrap/app.php` and added the `/api/v1` prefix with `api.v1.*` route names.
- Added `GET /api/v1/health` as a liveness check.
- Added `config/cors.php`. Allowed origins come from `CORS_ALLOWED_ORIGINS` as a comma separated list, defaulting to the local Next.js dev server. `FRONTEND_URL` and `CORS_ALLOWED_ORIGINS` were added to `.env.example`.
- Added rate limiters in `AppServiceProvider`: `api` at 60/minute per IP and `enquiries` at the configured submit limit per IP.
- Added the `public-form` middleware alias for `HandlePublicFormSubmission`, which refuses bodies over 20,000 bytes with a 413 and accepts honeypot hits silently with `{"ok": true}`.
- Started `docs/api.md` as the request/response contract, including the endpoints still planned for Phase 12.

### Contact Enquiries

- Added the `ContactEnquiry` model with `open`/`unassigned` scopes and `openStatuses()` derived from config.
- Added `config/enquiries.php` holding the status workflow (`new`, `in_progress`, `responded`, `closed`, `spam`), the sources, the public service picker options, and the submit limits.
- `responded_at` follows the status: statuses flagged `responds` stamp it, everything else clears it.
- Added `POST /api/v1/enquiries`, which mirrors the validation the existing Next.js contact route applies, including the honeypot and body size behaviour.
- The submitted `service` option resolves to a real service record through the slug in `config/enquiries.php`; `other` stores only the label.
- Submissions record the IP address and a truncated user agent.
- Added the admin enquiry list at `/admin/enquiries` with open, unassigned, status, and assignee filters, plus a banner for open enquiries with nobody assigned.
- Added the admin enquiry detail screen with the customer message shown as submitted, the submission metadata, a mailto reply button, and the status/assignment form.
- Only the workflow fields are editable; the customer's own name, email, phone, and message are never rewritten in admin.
- Assignment is limited to active admin users.
- Enquiries are soft deleted so a spam clear-out stays reversible.
- Notification email is deliberately not built yet; it waits for the queue and mail setup in Phase 14.
- Added an Enquiries sidebar link.

### Bookings

- Added `Booking`, `BookingAddress`, `BookingItem`, and `BookingPayment` models.
- Added `config/bookings.php` holding the status ladder, payment statuses, payment options, notice options, line types, reference format, and submit limits.
- Added `App\Support\BookingReference`, which produces phone-friendly references in the shape `MT-2609-4821` and retries until the reference is unique across soft deleted bookings too.
- Added `App\Support\BookingBuilder`, which turns a validated submission into a booking inside a transaction.
- **Prices are never read from the request.** The cart lives in browser localStorage, so the builder looks every price up in the catalogue by id and ignores any money field the client sends.
- Every booking line is a snapshot of the name, SKU, unit price, and VAT rate, with the catalogue id kept only as a back link.
- Saturday collection and pay-on-arrival surcharges are applied server side from the `extra_charges` catalogue, not picked by the customer.
- A `quote_required` item is rejected at submit, because it has no bookable price.
- Catalogue prices are inclusive of VAT, so `subtotal_pence` and `total_pence` are inc-VAT and `vat_pence` is the tax portion inside them.
- Added `POST /api/v1/bookings` with its own rate limiter and the `public-form` middleware using the `company_website` honeypot.
- The public form posts `now`/`arrival` for the payment option; the request maps them onto the stored `pay_now`/`pay_on_arrival` values.
- A Saturday collection is rejected unless the chosen date actually falls on a Saturday, and restricted access requires a description.
- Added the admin booking list at `/admin/bookings` with upcoming, awaiting-payment, status, and payment status filters, plus a banner for drafts waiting on payment.
- Added the admin booking detail screen showing collection details, the priced line snapshots with totals, both addresses, payment records, and the workflow form.
- Internal admin notes are stored in `bookings.metadata.admin_notes`, so no migration was needed.
- Only the workflow fields are editable in admin; the customer's details, addresses, and line snapshots are never rewritten.
- `submitted_at` and `confirmed_at` are derived from the status stage. Cancelling keeps whatever timestamps the booking already had.
- Bookings are soft deleted so the reference and its payment history stay resolvable.
- Dashboard booking and price item counts now go through the models, so soft deleted rows are excluded.

### Public API Read Layer

- Added `Money::toApi()`, which is the single money shape the API returns: `{pence, formatted, currency}`. Pence stays the source of truth and the frontend never re-implements rounding.
- Added `PageSection` and `SectionItem` models, plus `Page::sections()` and `Service::faqs()`.
- Added 16 API resources under `app/Http/Resources/Api/V1` so every payload has a stable shape independent of the table columns.
- Added read endpoints for settings, pages, services, service detail, price categories, category items, load packages, extra charges, FAQs, reviews, and coverage.
- Every read endpoint returns only published or active records; a slug that exists but is not published returns 404.
- Price-carrying resources expose `pricing_status` plus `is_provisional` and `requires_quote`, so the frontend can label a provisional or quoted price instead of silently showing a number.
- Variable extra charges return a null amount rather than a zero.
- Reviews never expose the reviewer's email address.
- `GET /api/v1/faqs` returns general FAQs by default and service FAQs when given `service=<slug>`.
- Read endpoints are wrapped in the `api` rate limiter at 60 requests per minute per IP.
- `docs/api.md` now documents every endpoint, the money shape, and the pricing status meanings.

### Frontend Integration

- Added `POST /api/v1/reviews`. Visitor reviews are always stored as `pending` with source `website`, so nothing reaches the site until a moderator publishes it. It carries its own rate limiter and the `website` honeypot.
- `GET /api/v1/price-categories?with_items=1` embeds the active items, so the booking page loads the catalogue in one request.
- `MediaResource` returns paths starting with `/` unchanged, because the seeded media points at files in the Next.js `public/images` folder. Rewriting them to the Laravel origin produced broken images.
- Frontend: added `src/lib/api.js`, `NEXT_PUBLIC_API_URL`, and `frontend/.env.example`, and un-ignored `.env.example` in the frontend `.gitignore`.
- Frontend: FAQ page, Areas page, home page reviews, and the prices/booking page now read from the API on the server with a 5 minute revalidate, passing data into the existing client components as props.
- Every one of those pages keeps its original hardcoded content as a fallback. The production build was verified with the backend both running and stopped.
- Frontend: the contact form posts to `POST /api/v1/enquiries`, and the retired Next.js `src/app/api/contact/route.js` (which emailed through Resend) was removed.
- Frontend: the write-a-review form posts to `POST /api/v1/reviews` and tells the visitor the review appears after checking, instead of inserting it into the carousel locally where it vanished on refresh.
- Frontend: the cart now stores structured collection details (date, Saturday flag, notice, payment option, access answers, large items, notes) next to the items, because previously they only existed flattened into a display string and could not be submitted.
- Frontend: cart lines carry `catalogueType` and `catalogueId`. Only those are sent to `POST /api/v1/bookings`; the Saturday surcharge line is display-only because the server adds it.
- Frontend: the payment page submits the booking and shows the reference on success. Lines added before this change carry no catalogue id and are caught with a clear message instead of a failed submit.
- Frontend: **the card number, expiry, and CVC fields were removed from the payment page.** No payment provider is connected, and raw card details must never be posted to the Laravel backend. The page now explains how payment will be taken.
- Frontend: quote-required items show "Quote on request" with a link to the contact page instead of a price and an Add button.
- Frontend: item images prefer an image set on the item in admin, then the bundled per-item photo matched by slug, then the category image, because the seed gives every item its category's image.
- Frontend: images uploaded through the admin come from the API origin and are rendered `unoptimized`, because Next 16 refuses to optimise images from a local IP and enabling `dangerouslyAllowLocalIP` would open an SSRF risk.
- Frontend: fixed the mojibake in the booking price display (`Â£` shown instead of `£`, `Â³` instead of `³`) and in the contact form success message.
- Frontend: removed manual `useMemo` from the booking component's derived values, because the React Compiler is enabled and the manual memoisation broke its optimisation.
- End-to-end check with both servers running: pages render API data, CORS allows the Next.js origin and refuses a foreign one, and a booking, an enquiry, and a review all submit from the Next.js origin. The test records were removed afterwards.

### Security, Quality, And Deployment

Email notifications:

- Added queued notifications: `NewContactEnquiry`, `NewBooking`, and `NewReview` to the office, and `BookingConfirmation` to the customer's billing email. All implement `ShouldQueue` and wait for the database transaction to commit.
- Added `App\Support\WebsiteNotifier`, called from the three public submit endpoints. A mail failure is reported, never thrown, so it cannot fail the visitor's submission.
- Added a private `notifications` settings group: the office alert email (falling back to the public contact email) and on/off switches for each alert and for the customer confirmation. None of it is exposed by the public API.
- Added `App\Support\MarkdownText::escape()`. Visitor text is escaped before it goes into a Markdown mail line, so a message containing `[click here](https://...)` cannot become a real link in the office inbox.
- Office alerts set Reply-To to the customer, so the office can answer by replying.
- Verified the real path: the notification was queued in the `database` queue, `queue:work` processed it, and the `log` mailer wrote it with the right recipient, subject and admin link.
- Added `SiteSetting::valueOf()` for reading one setting in code.

Role-based access:

- Added `config/admin_access.php`, mapping each admin module to the roles allowed to open it, and the `access-module` gate. An unknown module is denied, so a new module stays locked until it is listed.
- Editors get content modules (media, services, FAQs, reviews, coverage). Admins also get pricing, bookings, enquiries and settings. Only super admins get users and the activity log.
- Every admin route group carries `can:access-module,'<module>'`. **The module name must be quoted**: unquoted, Laravel resolves it as a route parameter, which passed the MediaAsset model or null to the gate. `AdminAccessTest` fails if any admin route is ungated or unquoted.
- The sidebar is grouped (Operations, Content, Pricing, Administration) and only shows links the signed-in role can open.
- Nobody can change their own role or status, and the last active super admin cannot be demoted, deactivated, or deleted.

Audit trail:

- Added the `activity_logs` table (the first migration added after the initial schema), the `ActivityLog` model, and the `LogsActivity` model trait, applied to 17 admin-managed models.
- Only changes made by a signed-in user are logged, so public submissions and seeding stay out. Updates record each changed attribute's old and new value; passwords, remember tokens and timestamps never are.
- Added the super-admin-only Activity Log screen with user, action and record type filters.
- Entries older than `ACTIVITY_RETENTION_DAYS` (default 365) are removed by `model:prune`, scheduled daily in `routes/console.php`.

Login and headers:

- The Users list's "Last Login" column was never filled in; the login event now records it.
- An inactive account is signed straight back out at login with a clear message, instead of signing in and hitting a bare 403.
- Added `AddSecurityHeaders` middleware: `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy` on every response. CSP and HSTS are deliberately left to a later step and the web server; see `docs/deployment.md`.
- Confirmed by test: login throttling after five failures, registration disabled, the admin requiring a signed-in user, and private settings never reaching the public API.

Seeder safety:

- **Re-running `db:seed` used to reset the admin password to `password` every time.** The seeder now only creates the first super admin when the account does not exist, taking the password from `SEED_ADMIN_PASSWORD`, which is required in production.
- The seeder refuses to run against a production database that already has data, because its catalogue upserts would overwrite admin edits. `ALLOW_PRODUCTION_SEED=true` overrides it for a deliberate one-off.

Test isolation:

- **The test suite used to run against the development database `mr_tee`.** Tests switched to it at runtime, and 861 test users, 36 media records, 2 service items and 104 audit rows had piled up in the dev admin.
- Tests now run against a dedicated `mr_tee_testing` database set in `phpunit.xml`, with `RefreshDatabase` seeding once per run and rolling back every test. The base `TestCase` refuses to refresh any database but `mr_tee_testing`.
- Verified that a full test run leaves the dev database unchanged.
- Removed the leftover test data from `mr_tee` after taking a backup to `backend/storage/app/backups/` (git-ignored). The dev admin now has only `admin@mrtee.local`, and the development settings were confirmed to be at their seeded values.

Documentation:

- Added `docs/deployment.md`: server requirements, the production `.env` checklist, first and later deploy steps, the queue worker and scheduler, web server notes, backups with a restore test, the pre-launch checklist, and monitoring.
- Added `MEDIA_DISK`, `ACTIVITY_RETENTION_DAYS`, `SEED_ADMIN_PASSWORD` and `ALLOW_PRODUCTION_SEED` to `backend/.env.example`.

### Frontend Cleanup (Phase 15)

- Repaired the remaining mojibake across nine frontend files: `Â£` to `£`, `Â·` to `·`, `Â©` to `©`, and the broken em dash, en dash and apostrophe sequences. Each sequence was reversed individually and checked by code point, not by eye, because the Windows console mangles them.
- Removed the voucher box from the payment page. The backend has no voucher support, so a code typed there silently did nothing. `bookings.discount_pence` is ready if vouchers are built later.

### Page SEO And Sitemap (Phase 16)

- **Every page on the site had the description "Generated by create next app"** and the title "Waste Service", from the root layout. Only checkout, payment and prices set their own.
- Added `GET /api/v1/pages`, listing published pages with their route and SEO fields.
- Frontend: added `src/lib/seo.js`. All 26 pages now export `generateMetadata`, taking the title, description, canonical link, Open Graph fields and `noindex` from the admin's SEO fields, matched by route path, with a built-in fallback title when the backend is down.
- Frontend: added `sitemap.xml` (published, indexable pages only, revalidated every 5 minutes) and `robots.txt` (disallows checkout and payment), and `NEXT_PUBLIC_SITE_URL` for absolute URLs.
- Added a **Pages & SEO** admin module at `/admin/pages`: edit each page's title, navigation label, meta title, meta description with live length counters, and whether it appears in search. Routes, templates and publishing are deliberately not editable, because the pages are built in code and switching one to draft would not remove it from the site.
- The Pages list warns about pages still carrying a missing or placeholder meta description.
- Editors can use it; it is in the `pages` module, and `Page` now uses `LogsActivity`.
- A service's SEO can be edited from either the service form or its page; both write to the same page record.
- Verified with both servers running: titles, descriptions, canonical links and `noindex` in the rendered HTML, 24 URLs in the sitemap with checkout and payment left out, and robots.txt.

### Operational Dashboard (Phase 17)

- Replaced the dashboard's record counts and "Recent users" table, left over from the first module, with a "Waiting on you" panel: bookings to confirm, bookings awaiting payment, unassigned enquiries, reviews to moderate, placeholder prices, pages missing SEO, and coverage areas without a postcode.
- Each card is highlighted when it needs action and links to the list that resolves it, with the matching filter preselected. The bookings, enquiries, reviews, service items and coverage areas lists now read their filters from the URL.
- Added an upcoming collections table (next 14 days) and an open enquiries list.
- Every section is limited to the modules the signed-in role can open, so an editor sees only content work.
- A catalogue snapshot (published services, bookable items, live reviews, coverage areas) sits underneath.

### Frontend Unit Tests (Phase 18)

- Added `npm test`, running Node's built-in test runner over `frontend/tests/**/*.test.mjs`, with no new dependencies.
- 23 tests cover `src/lib/api.js` (URL building, the null fallback on errors and network failure, the revalidate window, 422 first-field messages, 429 and 413 messages, non-JSON error pages, `timeAgo`) and `src/lib/seo.js` (admin SEO fields, `noindex`, fallbacks when the backend is down, and the site defaults).
- `seo.js` now imports `./api.js` relatively, because Node does not resolve the `@/` alias.
- The test script silences only Node's `MODULE_TYPELESS_PACKAGE_JSON` warning rather than switching the whole package to `"type": "module"`.

### Admin Search (Phase 19)

- Added `Booking::scopeSearch()`, used by the bookings list's search box. It matches the reference, the customer's full name, email, first address line, the postcode with or without its space, and the phone or mobile number in any format (`020 8226 6477`, `02082266477` and `020-8226-6477` all match).
- Added `ContactEnquiry::scopeSearch()`, matching the name, email, service, message, and the phone number in any format.
- Phone matching only kicks in for a run of four or more digits, so a short number cannot sweep up unrelated records.
- Both lists replace DataTables' default column search with these scopes, and their search boxes say what can be searched.

### Content-Security-Policy (Phase 20)

- Added `AddContentSecurityPolicy` on the `web` middleware group, configured in `config/security.php`. It is sent on HTML responses only; the JSON API does not get it.
- Scripts are limited to the app, jQuery's CDN, jsDelivr, DataTables' CDN and cdnjs, plus inline blocks carrying a per-request nonce from Laravel's `Vite::useCspNonce()`. `object-src 'none'`, `base-uri`, `form-action` and `frame-ancestors` are locked to the app.
- Styles keep `'unsafe-inline'`, because the views use inline style attributes throughout; a nonce cannot cover those.
- Added the `@nonce` Blade directive and put it on all 18 inline scripts. The admin has no inline event handlers.
- `CSP_REPORT_ONLY=true` switches to report-only as an escape hatch; `CSP_ENABLED=false` turns it off.
- No browser runs in this environment, so `ContentSecurityPolicyTest` renders **every** admin GET page, over forty of them, plus the sign-in page, and fails on a page that is not a 200, lacks the header, has an inline script without the nonce, loads a script or stylesheet from a host the policy blocks, or uses an inline event handler or `javascript:` link. It doubles as a smoke test that every admin page renders.

### Crew Day Sheet And Export (Phase 21)

- Added a printable **day sheet** at `/admin/bookings/day-sheet?date=YYYY-MM-DD`: every submitted, confirmed or scheduled job that day, with the collection address, customer phone numbers, the items to collect (surcharge lines left off), access restrictions, large items, collection and office notes, and the amount outstanding.
- Drafts and cancelled bookings are left off the sheet. A pay-now booking still owing money is flagged "should have been paid online: check with the office first".
- Added `Booking::outstandingPence()`: nothing once marked paid, otherwise the total less any recorded payments.
- Added a separate print layout with print CSS; the print button uses a nonce'd script, so the page passes the Content-Security-Policy check with the rest of the admin.
- Added **CSV export** at `/admin/bookings/export`, following the list's current status, payment, scope and search filters, with an optional collection date range. It streams in chunks and starts with a UTF-8 byte order mark so Excel shows the pound sign.
- Added `App\Support\CsvCell::safe()`: a cell starting with `=`, `+`, `-`, `@`, tab or carriage return is prefixed with `'`, so a customer name such as `=HYPERLINK(...)` cannot run as a formula when the office opens the file.
- The bookings list has Day sheet and Export CSV buttons. Both routes sit in the `bookings` module, so editors cannot reach them.
- The new routes are declared before `bookings/{booking}`, which would otherwise read `day-sheet` as a booking id.

### Customer Status Emails (Phase 22)

- Added the queued `BookingStatusChanged` notification, emailed to the customer when the office moves a booking to Confirmed, Scheduled or Cancelled. Which statuses notify is set by `notifies_customer` in `config/bookings.php`.
- It is only sent when the status actually changes, so correcting notes or payment status never re-sends it.
- The booking screen has an "Email the customer about a status change" checkbox, ticked by default, with the address it will go to, so the office can skip the email for a correction.
- A private `notifications.send_customer_status_updates` setting switches the emails off entirely.
- Copy is factual: the date and reference on confirm, the call-ahead notice on schedule, and a "contact us if you did not expect this" line on cancel, without a total.

### Portsmouth And Live Contact Details (Phase 23)

- The client confirmed the business is based in Portsmouth. All London and "Home Counties" wording on the site was replaced, including the home hero, the service copy, the FAQ fallback and the footer.
- The seeded coverage now has three regions: Portsmouth, Havant & Waterlooville, and Fareham & Gosport, with 29 areas. Every area has an outward postcode. The seeder deletes the old London regions (the areas cascade).
- The postcode check now matches the exact outward code (`CoverageArea::outwardCode`), so PO19 and PO16 no longer count as PO1.
- The Areas page fallback lists the same places. Its map searches "<place>, Hampshire, UK".
- The header, footer and contact page now read the phone, email, location and opening hours from Site Settings through `frontend/src/lib/site.js` (`getSiteContact`), with a fallback per field. The root layout fetches it once.
- The seeded contact defaults now match what the footer already showed: `info@wasteservices.com` and `Mon–Sat, 7:00am–7:00pm`. The seeder fixes the old London location, `info@example.com` and `Available 24/7` only when they are still untouched. Anything an admin changed is left alone.

### Full SEO Fields (Phase 24)

- Pages gained `og_title`, `og_description`, `og_image_id` (a Media Library image), `canonical_url` and `is_followable`. The edit screen has a live Google-style preview, a Social sharing section, a Canonical URL field and a "Let search engines follow links" switch.
- `canonical_url` accepts a path on this site or a full http(s) address, nothing else. The share image must be an image, not a PDF.
- `pages.og_image_id` is listed in `config/media.php`, so an image used as a share card cannot be deleted from the Media Library.
- New public settings. In the SEO group: default share image URL, Google and Bing verification codes, and an X handle. A new Business Listing group holds the street, town, county, postcode, country, machine-readable opening hours and price range. The town and county are seeded as Portsmouth and Hampshire.
- The frontend emits Open Graph and X card tags with a share image on every page, a canonical link, and `noindex`/`nofollow` as set. The root layout adds the verification meta tags and a LocalBusiness JSON-LD block, with `areaServed` taken from the coverage areas. JSON-LD is escaped so admin text cannot close the script tag.
- Checkout and payment are seeded as noindex and nofollow, and the frontend forces both even if the backend is down.

### Service Pages From The API (Phase 25)

- All 19 service pages fetch `GET /api/v1/services/{slug}` once. The hero heading, paragraph and image come from the service's headline, summary and hero image in the admin, each falling back to the page's built-in copy. The hero's Call button uses the phone number from Site Settings.
- A shared `ServiceExtras` section, placed just above the quote form, shows admin-added content blocks (content, feature list, steps, gallery, pricing highlight, FAQ list, call to action), the FAQs assigned to the service with FAQPage structured data, and related services. It renders nothing when there is nothing to show, so pages look as before until an admin adds content.
- The `service_overview` block is not rendered, because the page's own design already covers it. The block form explains this, and the service form now says where the headline, summary and description appear.
- Links from block items are only rendered for site paths and http(s), tel or mailto addresses.
- The remaining "Chingford" copy (about 45 places) now says Portsmouth. The copy-paste hero fallbacks (Wait & Load titled "Junk Collection", "outdoor space" on the shop and warehouse pages) now use the seeded summaries. The House Clearance seed now uses the image and full intro the page actually shows.
- **Rate limit fix.** The Next.js server renders every page from one IP, so the 60 per minute per-IP limit throttled builds (every request returned 429 in testing), and would throttle production revalidation too. The server now sends `X-Frontend-Key`, matching `FRONTEND_API_KEY` in both `.env` files, and gets a separate 1200 per minute allowance. Local keys were generated in `backend/.env` and `frontend/.env.local`. Production needs its own matching key.

### Verification Done For Earlier Work

- `php8.4 artisan migrate`
- `php8.4 artisan db:seed`
- `php8.4 artisan route:list`
- `php8.4 artisan test`
- `php8.4 /usr/local/bin/composer validate --no-check-publish`
- `php8.4 artisan view:cache`
- `php8.4 artisan view:clear`
- `php8.4 artisan test --filter=AdminPanelTest`
- Local HTTP checks:
  - `/login` returns 200.
  - `/admin` redirects to `/login` when unauthenticated.
  - `/register` returns 404.
  - Logo asset loads.

### Verification Done Through Phase 8

Run on Windows/Laragon with PHP 8.4.25 on 2026-09-18:

- `php artisan migrate` (created the `mr_tee` schema from scratch).
- `php artisan db:seed` (added the `social` group, help text, and the `contact.email` type change).
- `php artisan route:list` (confirmed the new media and settings routes).
- `php artisan view:cache` then `php artisan view:clear` (all Blade views compile).
- `php artisan storage:link`.
- `php artisan test` - 163 passed (after Phase 25), against the isolated `mr_tee_testing` database.
- `npm run lint`, `npm test` (47 passed), and `npm run build` in `frontend/`, with the backend running and with it stopped.

Note: `SiteSettingsTest::test_settings_update_validates_input_by_type` depends on the reseeded `contact.email` type, so run `db:seed` before the tests.

## Behaviour Worth Knowing

- `faqs.service_id` cascades on delete, but `services` is soft deleted. Soft deleting a service therefore leaves its FAQs in place; they are only removed by a force delete. The same applies to `service_content_blocks` and `service_block_items`.
- `service_items.price_category_id` cascades on delete, which is why deleting a price category is blocked while it still has items, including soft deleted ones.
- `reviews.service_id` and `contact_enquiries.service_id` null out on delete instead of cascading, so the record survives the service.
- `coverage_areas.coverage_region_id` cascades on delete, which is why deleting a region is blocked while it still has areas.

## Known Environment Notes

- Backend is Laravel 13 and requires PHP 8.4 or newer, because `composer.lock` pins Symfony 8.1 packages that require PHP >= 8.4.1.
- Local `.env` uses MySQL database `mr_tee`.
- Browser-facing libraries should stay CDN-based unless there is a real backend/build reason to install locally.

### Windows / Laragon Setup

Set up on 2026-09-18 so backend verification can run on this machine:

- PHP 8.4.25 (TS, vs17) installed at `G:/laragon/bin/php/php-8.4.25-Win32-vs17-x64`. Laragon lists it under PHP > Version alongside the existing 8.3.16.
- Its `php.ini` was copied from the 8.3 install with `extension_dir` repointed. Three third-party extensions were disabled because their DLLs are not bundled: `php_sqlsrv.dll`, `php_pdo_sqlsrv.dll`, `php_mongodb.dll`. The deprecated `session.sid_length` and `session.sid_bits_per_character` settings were commented out.
- Laragon's default CLI `php` is still 8.3.16, which cannot run this project. Call the 8.4 binary explicitly, for example `G:/laragon/bin/php/php-8.4.25-Win32-vs17-x64/php.exe artisan test`, or switch the Laragon PHP version.
- Composer is at `C:/ProgramData/ComposerSetup/bin/composer.phar`; run it through the 8.4 binary.
- `vendor/` installed, `.env` created from `.env.example`, app key generated, and `php artisan storage:link` run.
- The `mr_tee` MySQL database was created on the local Laragon MySQL (root, no password). The unrelated legacy `mrtree` database on the same server belongs to the old site and must not be touched.
- `sqlite3` and `pdo_sqlite` load, but the tests use MySQL on purpose so they match production SQL.
- Tests need the `mr_tee_testing` MySQL database to exist (create it once with `CREATE DATABASE mr_tee_testing`). `RefreshDatabase` migrates and seeds it on each run.
- Laragon's MySQL can stop between sessions. If artisan reports `SQLSTATE[HY000] [2002]`, start it from Laragon, or run `G:/laragon/bin/mysql/mysql-8.4.3-winx64/bin/mysqld.exe --defaults-file=G:/laragon/bin/mysql/mysql-8.4.3-winx64/my.ini --standalone`.

## Next Work Plan

### Phase 1: Admin Foundation Hardening

Status: Done, except the visual review below.

Tasks:

- Review the approved admin design across desktop/mobile. **Still open: needs a person with a browser and a phone.** The sidebar was regrouped and made scrollable in Phase 14, and every admin page is confirmed to render by `ContentSecurityPolicyTest`, but nobody has looked at it on a small screen.
- Add a reusable admin view structure: page headers, breadcrumbs, form panels, DataTable wrappers, delete confirmations, empty states. Done, and used by every module.
- Add middleware or policies for admin-only access. Done; the `admin` middleware plus role-based module access from Phase 14.
- Add profile/password update screen for logged-in admin users. Done.
- Decide whether to keep the generated password reset flow as-is or redesign it. Done; reset and confirm views use the shared auth shell.
- Add route-level tests for auth, dashboard, profile, and the Users module. Done; every admin page is now exercised.
- Harden repeated admin test runs against shared MySQL data collisions. Done; superseded by the isolated `mr_tee_testing` database in Phase 14.

### Phase 2: Shared Admin Infrastructure

Status: Done.

Tasks:

- Add reusable form request classes for validation. Done; every admin and API write goes through one.
- Add base admin controller helpers if repeated patterns emerge. Done: `BuildsAdminDataTables`, `FlashesMessages`, `GuardsDeletions`.
- Add consistent flash helper behavior for toastr. Done.
- Add standard server-side DataTables response conventions. Done.
- Add shared Blade partials for status badges, publish toggles, sort order fields, image preview fields, and SEO fields. Done, plus the media picker, money field, pricing status field, rating stars, and placeholder warning added later.
- Extend the initial confirmation modal pattern as new modules are added. Done; every module's delete uses it.
- Decide and document upload storage approach. Done; see `docs/media-storage.md` and `backend/config/media.php`.

### Phase 3: Media Library

Status: Done for admin. Public API shape is carried into Phase 12.

Tasks:

- Admin list of media assets. Done.
- Upload image/file. Done.
- Edit alt text and metadata. Done.
- Delete unused media safely. Done; deletion is blocked while any `usage_references` table still points at the asset.
- Reuse existing `media_assets` table. Done.
- Add API shape for frontend image URLs. Deferred to Phase 12; `MediaAsset::$url` already resolves disk, absolute, and remote paths.
- Add validation for mime type and max size. Done.
- Add thumbnail/preview UI. Done for the list and the edit screen.

### Phase 4: Site Settings

Status: Done for admin. Public API endpoint is carried into Phase 12.

Tasks:

- Settings module grouped by:
  - General. Done.
  - Contact. Done.
  - Booking. Done.
  - SEO. Done.
  - Social links. Done; seeded as the `social` group.
- Admin edit forms for seeded settings. Done.
- Ensure public settings API exposes only `is_public = true`. Admin toggle and `SiteSetting::scopePublic()` added; the endpoint itself lands in Phase 12.
- Replace hardcoded frontend values later via API. Not started.

### Phase 5: Services CMS

Status: Done for admin. The output API is carried into Phase 12.

Tasks:

- Service categories CRUD. Done.
- Services CRUD. Done.
- Service hero fields. Done; headline, summary, hero image, and description.
- Service SEO fields. Done; stored on the linked page record.
- Service status/publish controls. Done; draft, published, archived with automatic `published_at` handling.
- Service sort ordering. Done.
- Service content blocks CRUD. Done.
- Service block items CRUD. Done.
- Related services management. Done; synced from the service form with pivot sort order.
- Media selection for hero/gallery/block images. Done; shared Select2 media picker backed by `/admin/media/options`.
- Prepare output API for standardized frontend service page layout. Deferred to Phase 12.

### Phase 6: Pricing Catalogue

Status: Done for admin. The API endpoints are carried into Phase 12.

Tasks:

- Price categories CRUD. Done.
- Service items CRUD. Done, with category and pricing status filters.
- Load packages CRUD. Done.
- Extra charges CRUD. Done.
- Pricing status workflow. Done; `confirmed`, `placeholder`, `quote_required`, and `active` are defined in `config/pricing.php`.
- Keep prices in pence internally while displaying pounds in admin forms. Done through `App\Support\Money` and the shared `money-field` partial.
- Add clear warnings for placeholder prices. Done; a list banner, a status badge with a warning icon, and a repeat warning on the edit form.
- Add API endpoints for price category list, items by category, load package list, and extra charges. Deferred to Phase 12.

### Phase 7: FAQs And Reviews

Status: Done for admin. The public endpoints are carried into Phase 12.

Tasks:

- FAQ CRUD. Done.
- Optional service-specific FAQ assignment. Done; a FAQ with no service is general.
- FAQ sort ordering. Done.
- Reviews list. Done, with status, rating, and service filters.
- Review moderation statuses. Done; defined in `config/reviews.php`.
- Create/edit/delete reviews. Done, plus one-click publish and reject from the list.
- Public API for published reviews. Deferred to Phase 12; `Review::scopePublished()` is ready.
- Public API for active FAQs. Deferred to Phase 12; `Faq::scopeActive()` and `scopeGeneral()` are ready.

### Phase 8: Coverage Areas

Status: Done for admin. The public endpoints are carried into Phase 12.

Tasks:

- Coverage regions CRUD. Done.
- Coverage areas CRUD. Done, as a flat list with a region filter.
- Featured area flag. Done, with a filter and a region-level count.
- Postcode prefix support. Done; normalised and validated as a UK outward code.
- Lat/lng fields for map support. Done; set as a pair, with a map link on the edit screen.
- Public API for active regions/areas. Deferred to Phase 12; `scopeActive()`, `scopeFeatured()`, and `scopeOrdered()` are ready.
- Later: booking postcode/serviceability checks if needed. `CoverageArea::scopeMatchingPostcode()` is in place for Phase 10.

### Phase 9: Contact Enquiries

Status: Done. The notification email was added in Phase 14.

Tasks:

- Store contact form submissions in `contact_enquiries`. Done.
- Admin list with statuses. Done, with open/unassigned/status/assignee filters.
- Admin detail page. Done.
- Assign enquiry to admin user. Done; active admin users only.
- Mark responded/closed. Done; `responded_at` is derived from the status.
- Optional email notification integration. Done in Phase 14.
- API endpoint for frontend contact form. Done at `POST /api/v1/enquiries`.
- Spam/honeypot behavior matching current frontend logic. Done through the `public-form` middleware, plus per-IP rate limiting.

### Phase 10: Bookings

Status: Done, except the payment-driven status change which belongs to Phase 11.

Decisions made when this was built:

- A pay-on-arrival booking has nothing to pay online, so it is created as `submitted`. A pay-now booking is created as `draft` and Phase 11 flips it to `submitted` when the payment succeeds. Until then those drafts are surfaced in the admin awaiting-payment list and have to be chased by hand.
- References are `MT-YYMM-NNNN`, digits only after the prefix so they can be read out over the phone.
- Out-of-area postcodes are accepted, not rejected. The admin detail screen warns instead, so a job just outside the listed areas can still be taken on.

Tasks:

- Booking creation API from frontend cart/payment form. Done at `POST /api/v1/bookings`.
- Booking reference generator. Done.
- Store billing and collection addresses. Done.
- Store booking item snapshots. Done, priced from the catalogue rather than the request.
- Store collection details. Done; date, Saturday flag, notice minutes, access restrictions, large items, notes, and payment option.
- Admin booking list. Done.
- Admin booking detail. Done.
- Status workflow. Done; draft, submitted, confirmed, scheduled, completed, cancelled.
- Payment status workflow. Done; unpaid, deposit paid, paid, failed, refunded.
- Add manual admin notes if required. Done; stored in `bookings.metadata.admin_notes`.

### Phase 11: Payments

Status: Deferred by the client on 2026-09-18. No payment provider is to be added for now.

Consequences while this stays deferred:

- A pay-now booking is created as a `draft` and nothing flips it to `submitted`. Those bookings sit in the admin awaiting-payment list and have to be chased by hand.
- `booking_payments` stays empty. The admin booking detail screen already shows an empty payments panel.
- If online payment is not coming at all, consider making pay-on-arrival the only payment option on the public form, so customers are not offered a pay-now flow that cannot complete.

Tasks, for when it is picked up again:

- Decide payment provider.
- Add payment initiation endpoint.
- Store provider references in `booking_payments`.
- Add webhook route and verification.
- Update booking payment status from provider events.
- Support pay-now and pay-on-arrival callout fee flows.

### Phase 12: Public API Layer

Status: Done.

Decisions made when this was built:

- Money is returned as `{pence, formatted, currency}` everywhere, not as a bare integer or a bare string. Pence is the source of truth; the formatted string exists so the frontend never re-implements rounding or the currency symbol.
- Catalogue prices are inclusive of VAT, so `price` is inc-VAT and `price_ex_vat` sits alongside it.
- Pricing status is always exposed, because a placeholder or quote-required price must be labelled rather than shown as a plain number.

Tasks:

- Create versioned API routes. Done; the skeleton was brought forward in Phase 9.
- Add API resources/transformers for stable frontend contracts. Done; 16 resources.
- Endpoints: settings, pages, services, service detail, price categories, service items, load packages, extra charges, FAQs, reviews, coverage areas, contact enquiry submit, booking submit. All done.
- Add API tests for each endpoint. Done.
- Document request/response examples in `docs/api.md`. Done.

### Phase 13: Frontend Integration Support

Status: Done for the pages that carry catalogue, pricing, and submission data.

Tasks:

- Coordinate API payloads with frontend pages. Done.
- Replace hardcoded frontend service/pricing/contact data gradually. Done for FAQs, reviews, coverage areas, the price catalogue, load packages, and the Saturday surcharge. The individual service pages still use their hardcoded copy; see below.
- Support current routes while enabling future standardized service page layout. Current routes are untouched. `GET /api/v1/services/{slug}` is ready for a standardised layout when the service pages are rebuilt.
- Add CORS/config support if frontend/backend are served from different origins. Done and verified.

Found during integration, and still open:

- **The frontend's old hardcoded prices did not match the backend catalogue.** For example the 2 Seater Sofa was £70 on the site and is £75 in the backend, and the Mini Load was £34.99 and is £100. The site now shows the backend prices, which are all still marked `placeholder`. The client needs to confirm the real prices in the admin before launch.
- The voucher box on the payment page does nothing. There is no voucher support in the backend, and `discount_pence` is always 0. Either build vouchers or remove the box.
- The mojibake seen in the booking page also exists in `FlatClearanceIntro.jsx`, `FlyTippingServices.jsx`, `HouseClearanceServices.jsx`, and `Footer.jsx`. Those files were not touched by this phase.
- The individual service pages (`/houseClearance` and the rest) still render their own hardcoded copy rather than `GET /api/v1/services/{slug}`. Moving them over means rebuilding each page on a shared layout, which is a design task in its own right.
- The frontend has no automated tests. Its verification is lint, a production build with the backend up and down, and the manual end-to-end run recorded above.

### Phase 14: Security, Quality, And Deployment

Status: Done.

Tasks:

- Add authorization policies/permissions if roles need different access. Done; module-level role access.
- Add rate limiting for public submit endpoints. Done in Phase 9 and 13.
- Add request validation everywhere. Done; every admin and API write goes through a Form Request.
- Add activity logging/audit trail if required. Done.
- Add backups/deployment notes. Done in `docs/deployment.md`.
- Add production `.env` checklist. Done in `docs/deployment.md`.
- Add queue/mail setup for notifications. Done.
- Add more tests for admin CRUD and API behavior. Done, and the suite now runs isolated from the dev database.

Deliberately left for later:

- A Content-Security-Policy. Done later in Phase 20.
- HSTS, which belongs on the production web server.

## Immediate Next Task Recommendation

Phases 1 to 25 are done. The client's answers on 2026-09-19 set the remaining work:

1. **SEO content.** In the admin, fill in the Google Search Console verification code, the default share image and the Business Listing address, then replace the placeholder meta descriptions.
2. **Service content.** Assign FAQs and related services to each service in the admin; they appear on the service pages automatically.
3. **Prices.** The client confirms them in the admin; no code needed.
4. **Payments.** The client will add a provider later; nothing to do now.
5. **Launch.** Work through the pre-launch checklist in `docs/deployment.md`.

Deliberately deferred engineering: HSTS, which belongs on the production web server.

Carry these decisions forward:

- Deletion of any record that other tables reference follows the Media Library pattern: block the delete and name the referencing modules through the `GuardsDeletions` concern. Check soft deleted children too, as the price category guard does.
- All money is converted through `App\Support\Money`; never inline a `* 100` or `/ 100`. The API shape is `Money::toApi()`.
- Money that reaches the server from a browser is never trusted. Prices are looked up from the catalogue by id, as `BookingBuilder` does. The frontend sends ids and quantities only.
- Card details are never collected by this site or posted to the backend. Any future payment provider must use its own hosted fields or redirect.
- Anything a customer buys is stored as a snapshot, with the catalogue id kept only as a back link.
- Public read endpoints expose published/active records only, and never leak an email address or an admin-only field.
- Every frontend page that reads from the API keeps a hardcoded fallback, so the site and its build survive the backend being down.
- Server-side API reads go through `apiGet`, which sends the `X-Frontend-Key`. Never expose that key with a `NEXT_PUBLIC_` prefix.
- Admin-entered links rendered on the site go through `safeHref`. Admin text placed in JSON-LD goes through `jsonLdString`.
- Anything a visitor submits for public display lands as pending and waits for moderation.
- Visitor text is escaped with `MarkdownText::escape()` before it goes into any email.
- Every new admin module is added to `config/admin_access.php` and its routes wrapped in `can:access-module,'<module>'`, quoted. `AdminAccessTest` enforces this.
- Every new admin-managed model uses the `LogsActivity` trait.
- Emails are queued notifications sent through `WebsiteNotifier` or a similar isolating wrapper, never inline.
- Tests run against `mr_tee_testing` only, and never switch database at runtime.
- Never re-seed production.
- Image fields use `admin.partials.media-picker`, never a second uploader.
- Any new table that points at `media_assets` must be added to `config/media.php` under `usage_references`.
- Records that bookings reference are soft deleted, not hard deleted.
- Nested admin screens verify parent ownership and 404 on a mismatch.
- Status vocabularies live in config, not in Blade or controller literals.
- Workflow timestamps are derived from the status in the model, not edited by admins.
- Admin lists that depend on data being filled in warn at the top when it is missing.
- Public endpoints go under `/api/v1`, carry a named rate limiter, and use the `public-form` middleware when they accept a form submission. Document them in `docs/api.md` in the same change.
- Data submitted by a customer is never rewritten in admin; only workflow fields are editable.

## Maintenance Rule

After every backend/admin/API change:

1. Update this plan's `Completed`, `Current Admin Direction`, `Next Work Plan`, or `Known Environment Notes` sections as appropriate.
2. Mark phases as `Not started`, `In progress`, `Blocked`, or `Done`.
3. Add important implementation decisions that future backend work must preserve.
4. Record new verification commands when they matter.
