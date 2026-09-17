# Database Design

This backend database is designed for a Laravel admin panel, CMS, public API, and booking flow for the MR. TEE removals website.

## Source Context

- Frontend routes and components in `frontend/src`.
- Service and pricing reference in `docs/MR_TREE_Services_Content_and_Prices.docx`.
- Current catalogue shape:
  - 18 public service pages in the document, plus a live hedge-cutting route in the frontend.
  - 11 individual item pricing groups.
  - 44 individual priced item references in the document.
  - 6 Man & Van load packages.
  - Fixed and variable booking extras such as Saturday collection, callout fee, difficult access, and additional large items.

## Main Table Groups

### Admin Users

- `users` remains Laravel's authentication table.
- Added `role`, `status`, and `last_login_at` for admin-panel access control and staff filtering.

### CMS Content

- `media_assets` stores uploaded images/files once.
- `site_settings` stores public editable settings such as phone, email, opening hours, social links, default location, and SEO defaults.
- `pages`, `page_sections`, and `section_items` support editable text/image content for all public pages.
- `media_assignments` allows reusable galleries or multiple images on services, pages, sections, reviews, or future models.

### Services And Pricing

- `service_categories` stores top-level groups such as Rubbish Removal, Commercial Waste, Cleaning, and Garden Services.
- `services` stores service pages such as House Clearance, Garden Clearance, Window Cleaning, etc.
- `service_relations` supports related/connected services.
- `service_content_blocks` and `service_block_items` support the future consistent service-page layout while still allowing flexible content blocks.
- `price_categories` stores catalogue groups such as Sofas, Mattress & Bed, Garden Items, Bins & Wheelie Bins.
- `service_items` stores individual priced catalogue items.
- `load_packages` stores Man & Van load sizes.
- `extra_charges` stores fixed and variable extras.

### Areas, FAQs, Reviews, Contacts

- `coverage_regions` and `coverage_areas` support the Areas We Cover page and future postcode/area filtering.
- `faqs` supports site-wide and service-specific FAQs.
- `reviews` supports moderation before publishing.
- `contact_enquiries` stores contact form submissions and admin assignment/status.

### Bookings

- `bookings` stores the booking header, collection details, totals, and workflow status.
- `booking_addresses` stores billing and collection addresses separately.
- `booking_items` stores immutable order-line snapshots. Catalogue foreign keys are nullable, so old bookings survive if a catalogue item is later deleted.
- `booking_payments` stores payment attempts and provider references.

## Indexing Strategy

- Public lookups use unique indexes on slugs and route paths.
- Admin list views use compound indexes such as `status + created_at`, `status + published_at`, and `is_active + sort_order`.
- Relationship-heavy tables use foreign-key indexes plus sort-order indexes.
- Bookings are indexed by reference, status, collection date, payment status, service, and user.
- Contact enquiries are indexed by status, service, email, phone, and assigned admin.
- Price and service catalogue tables are indexed for common frontend API queries: active categories/items, active packages, featured services, and published services.

## Money And VAT

Money is stored as integer pence, not decimals. This avoids rounding errors and keeps totals reliable.

VAT rates are stored as basis points. For example, `2000` means `20.00%`.

Bookings store snapshot totals and line prices so later catalogue changes do not mutate old customer records.

## Flexible Content

The frontend service pages are currently inconsistent. The schema supports that transition in two ways:

- Page-level content can be represented in `pages`, `page_sections`, and `section_items`.
- Service-specific reusable blocks can be represented in `service_content_blocks` and `service_block_items`.

When the frontend service-page design is standardized, the admin panel can restrict editors to a smaller set of block components without changing the database.
