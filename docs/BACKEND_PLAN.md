# Backend Project Plan

This file is the canonical working plan for the Laravel backend, admin panel, CMS, and public APIs. Keep it updated after every backend-related change so future work can continue from the current project state instead of chat history.

Last updated: 2026-09-17

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
- Added shared Bootstrap delete confirmation modal behavior for admin screens.

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

### Assets

- Copied required frontend assets into backend public assets:
  - `public/images/MainLogo.png`
  - `public/images/HeroImage.jpg`

### Verification Done

- `php8.4 artisan migrate`
- `php8.4 artisan db:seed`
- `php8.4 artisan route:list`
- `php8.4 artisan test`
- `php8.4 /usr/local/bin/composer validate --no-check-publish`
- `php8.4 artisan view:cache`
- `php8.4 artisan view:clear`
- Local HTTP checks:
  - `/login` returns 200.
  - `/admin` redirects to `/login` when unauthenticated.
  - `/register` returns 404.
  - Logo asset loads.

## Known Environment Notes

- Backend is Laravel 13 and should be run with `php8.4`.
- Local `.env` uses MySQL database `mr_tee`.
- PHP 8.4 SQLite driver is not available in this environment. Avoid assuming in-memory SQLite tests unless the driver is installed.
- Some verification that queries MySQL may require elevated execution because sandboxed commands can fail connecting to local MySQL.
- Browser-facing libraries should stay CDN-based unless there is a real backend/build reason to install locally.

## Next Work Plan

### Phase 1: Admin Foundation Hardening

Status: In progress.

Tasks:

- Review the approved admin design across desktop/mobile.
- Add a reusable admin view structure for:
  - Page headers.
  - Breadcrumbs.
  - Form panels.
  - DataTable wrappers.
  - Delete confirmations. Initial shared Bootstrap modal added.
  - Empty states.
- Add middleware or policies for admin-only access if public frontend user auth is later introduced.
- Add profile/password update screen for logged-in admin users. Done.
- Decide whether to keep the generated password reset flow as-is or redesign it to match the login page.
- Add route-level tests for auth, dashboard, profile, and the Users module. In progress; current admin/profile/users coverage exists.

### Phase 2: Shared Admin Infrastructure

Status: Not started.

Tasks:

- Add reusable form request classes for validation.
- Add base admin controller helpers if repeated patterns emerge.
- Add consistent flash helper behavior for toastr.
- Add standard server-side DataTables response conventions.
- Add shared Blade partials/components for:
  - Status badges.
  - Publish toggles.
  - Sort order fields.
  - Image preview fields.
  - SEO fields.
- Extend the initial confirmation modal pattern as new modules are added.
- Decide and document upload storage approach: public disk, naming conventions, validation rules, image dimensions.

### Phase 3: Media Library

Status: Not started.

Tasks:

- Admin list of media assets.
- Upload image/file.
- Edit alt text and metadata.
- Delete unused media safely.
- Reuse existing `media_assets` table.
- Add API shape for frontend image URLs.
- Add validation for mime type and max size.
- Add thumbnail/preview UI.

### Phase 4: Site Settings

Status: Not started.

Tasks:

- Settings module grouped by:
  - General.
  - Contact.
  - Booking.
  - SEO.
  - Social links if needed.
- Admin edit forms for seeded settings.
- Ensure public settings API exposes only `is_public = true`.
- Replace hardcoded frontend values later via API.

### Phase 5: Services CMS

Status: Not started.

Tasks:

- Service categories CRUD.
- Services CRUD.
- Service hero fields.
- Service SEO fields.
- Service status/publish controls.
- Service sort ordering.
- Service content blocks CRUD.
- Service block items CRUD.
- Related services management.
- Media selection for hero/gallery/block images.
- Prepare output API for standardized frontend service page layout.

### Phase 6: Pricing Catalogue

Status: Not started.

Tasks:

- Price categories CRUD.
- Service items CRUD.
- Load packages CRUD.
- Extra charges CRUD.
- Pricing status workflow:
  - `confirmed`.
  - `placeholder`.
  - `quote_required`.
  - `active`.
- Keep prices in pence internally while displaying pounds in admin forms.
- Add clear warnings for placeholder prices.
- Add API endpoints for:
  - Price category list.
  - Items by category.
  - Load package list.
  - Extra charges.

### Phase 7: FAQs And Reviews

Status: Not started.

Tasks:

- FAQ CRUD.
- Optional service-specific FAQ assignment.
- FAQ sort ordering.
- Reviews list.
- Review moderation statuses.
- Create/edit/delete reviews.
- Public API for published reviews.
- Public API for active FAQs.

### Phase 8: Coverage Areas

Status: Not started.

Tasks:

- Coverage regions CRUD.
- Coverage areas CRUD.
- Featured area flag.
- Postcode prefix support.
- Lat/lng fields for map support.
- Public API for active regions/areas.
- Later: booking postcode/serviceability checks if needed.

### Phase 9: Contact Enquiries

Status: Not started.

Tasks:

- Store contact form submissions in `contact_enquiries`.
- Admin list with statuses.
- Admin detail page.
- Assign enquiry to admin user.
- Mark responded/closed.
- Optional email notification integration.
- API endpoint for frontend contact form.
- Spam/honeypot behavior matching current frontend logic.

### Phase 10: Bookings

Status: Not started.

Tasks:

- Booking creation API from frontend cart/payment form.
- Booking reference generator.
- Store billing and collection addresses.
- Store booking item snapshots.
- Store collection details:
  - Collection date.
  - Saturday collection.
  - Notice minutes.
  - Access restrictions.
  - Large items.
  - Collection notes.
  - Payment option.
- Admin booking list.
- Admin booking detail.
- Status workflow:
  - Draft.
  - Submitted.
  - Confirmed.
  - Scheduled.
  - Completed.
  - Cancelled.
- Payment status workflow:
  - Unpaid.
  - Deposit paid.
  - Paid.
  - Failed.
  - Refunded.
- Add manual admin notes if required.

### Phase 11: Payments

Status: Not started.

Tasks:

- Decide payment provider.
- Add payment initiation endpoint.
- Store provider references in `booking_payments`.
- Add webhook route and verification.
- Update booking payment status from provider events.
- Support pay-now and pay-on-arrival callout fee flows.

### Phase 12: Public API Layer

Status: Not started.

Tasks:

- Create versioned API routes, likely `/api/v1/...`.
- Add API resources/transformers for stable frontend contracts.
- Endpoints to plan:
  - Site settings.
  - Pages.
  - Services.
  - Service detail by slug.
  - Price categories.
  - Service items.
  - Load packages.
  - Extra charges.
  - FAQs.
  - Reviews.
  - Coverage areas.
  - Contact enquiry submit.
  - Booking submit.
- Add API tests for each endpoint.
- Document request/response examples in `docs/api.md`.

### Phase 13: Frontend Integration Support

Status: Not started.

Tasks:

- Coordinate API payloads with frontend pages.
- Replace hardcoded frontend service/pricing/contact data gradually.
- Support current routes while enabling future standardized service page layout.
- Add CORS/config support if frontend/backend are served from different origins.

### Phase 14: Security, Quality, And Deployment

Status: Not started.

Tasks:

- Add authorization policies/permissions if roles need different access.
- Add rate limiting for public submit endpoints.
- Add request validation everywhere.
- Add activity logging/audit trail if required.
- Add backups/deployment notes.
- Add production `.env` checklist.
- Add queue/mail setup for notifications.
- Add more tests for admin CRUD and API behavior.

## Immediate Next Task Recommendation

Continue Phase 1 hardening next:

1. Add small reusable admin partials for headers, forms, table cards, and empty states.
2. Decide whether password reset views should be redesigned to match the custom login page.
3. Review the approved admin UI on mobile and adjust spacing/navigation where needed.
4. Then move to the Media Library or Site Settings module.

The best next full business module is Site Settings because it is small, useful, and establishes the CRUD pattern before larger modules like Services and Bookings.

## Maintenance Rule

After every backend/admin/API change:

1. Update this plan's `Completed`, `Current Admin Direction`, `Next Work Plan`, or `Known Environment Notes` sections as appropriate.
2. Mark phases as `Not started`, `In progress`, `Blocked`, or `Done`.
3. Add important implementation decisions that future backend work must preserve.
4. Record new verification commands when they matter.
