# Public API

The Next.js frontend talks to the Laravel backend through a versioned JSON API under `/api/v1`.

This file is the contract. Update it whenever an endpoint is added or changed.

## Conventions

- Base path: `/api/v1`.
- Requests and responses are JSON. Send `Accept: application/json`.
- No authentication yet. Every endpoint listed here is public.
- Validation failures return `422` with Laravel's standard shape:
  ```json
  { "message": "The email field must be a valid email address.", "errors": { "email": ["Please enter a valid email address."] } }
  ```
- Rate limit failures return `429`.
- The Next.js server renders every visitor's page from one IP. It sends `X-Frontend-Key` with the shared `FRONTEND_API_KEY`, and gets its own allowance (`FRONTEND_API_RATE_LIMIT`, default 1200 per minute) instead of the 60 per minute per-IP limit. A missing or wrong key is treated like any other client.
- CORS allowed origins come from `CORS_ALLOWED_ORIGINS` in the backend `.env`, as a comma separated list. The local default covers `http://localhost:3000`.

## Implemented

### `GET /api/v1/health`

Liveness check. Useful for deployment smoke tests.

```json
{ "ok": true, "service": "Mr. Tee", "time": "2026-09-18T12:00:00+00:00" }
```

### `POST /api/v1/enquiries`

Submits the public contact form.

Rate limited to 5 requests per minute per IP (`enquiries.submit.rate_limit_per_minute`).

**Request**

```json
{
  "name": "Jane Smith",
  "phone": "02012345678",
  "email": "jane@example.com",
  "service": "house",
  "message": "I need a full house clearance next week, please call me back.",
  "company": ""
}
```

| Field | Rules |
| --- | --- |
| `name` | required, 2-100 characters |
| `phone` | required, 7-30 characters |
| `email` | required, valid email, max 150 characters, stored lowercase |
| `service` | required, one of `house`, `garden`, `office`, `builders`, `other` |
| `message` | required, 10-2000 characters |
| `company` | honeypot, must stay empty |

The `service` values and their labels live in `backend/config/enquiries.php` under `service_options`, and must stay in step with the picker in `frontend/src/components/contactUs/ContactUs.jsx`. Where an option maps to a real service, the enquiry is linked to that service record; `other` stores only the label.

**Success — `201`**

```json
{ "ok": true, "message": "Thanks, your enquiry has been sent. We will be in touch shortly.", "reference": 42 }
```

**Honeypot — `200`**

If `company` is filled, the submission is accepted silently and stored nowhere, so automated spam sees success and does not retry:

```json
{ "ok": true }
```

**Oversized body — `413`**

Bodies over 20,000 bytes are refused before validation:

```json
{ "message": "Your submission is too large to accept." }
```

This mirrors the behaviour of the existing Next.js route at `frontend/src/app/api/contact/route.js`, which this endpoint is intended to replace during Phase 13.

### `POST /api/v1/bookings`

Submits a booking from the cart and checkout.

Rate limited to 10 requests per minute per IP. Honeypot field: `company_website`.

**Request**

```json
{
  "items": [
    { "type": "service_item", "id": 12, "quantity": 2 },
    { "type": "load_package", "id": 3, "quantity": 1 }
  ],
  "collection": {
    "collection_date": "2026-10-03",
    "saturday_collection": true,
    "notice_minutes": 30,
    "payment_option": "arrival",
    "access_confirmed": true,
    "restricted_access": "yes",
    "access_restrictions": "No lift, third floor.",
    "large_items": "One piano",
    "collection_notes": "Gate code 1234"
  },
  "billing": {
    "first_name": "Jane", "last_name": "Smith", "company": "",
    "phone": "02012345678", "mobile": "07700900123", "email": "jane@example.com",
    "address_line_1": "14 Test Street", "address_line_2": "",
    "city": "Portsmouth", "county": "Hampshire",
    "postcode": "PO1 3AX", "country": "United Kingdom"
  },
  "collection_address": null,
  "company_website": ""
}
```

| Field | Rules |
| --- | --- |
| `items` | required, 1-40 lines |
| `items[].type` | `service_item` or `load_package` |
| `items[].id` | catalogue id; must still be active |
| `items[].quantity` | 1-50 |
| `collection.collection_date` | required, today or later; must be a Saturday when `saturday_collection` is true |
| `collection.saturday_collection` | required boolean |
| `collection.notice_minutes` | required, `30` or `60` |
| `collection.payment_option` | required, `now` or `arrival` |
| `collection.access_confirmed` | required boolean |
| `collection.restricted_access` | required, `yes` or `no` |
| `collection.access_restrictions` | required when `restricted_access` is `yes` |
| `billing.*` | required address block |
| `collection_address` | optional; same shape, used when the waste is elsewhere |

**Prices are never taken from the request.** The cart lives in browser localStorage, so the
endpoint reads every price from the catalogue using the id, and ignores any money field the
client sends. Each stored line is a snapshot, so later catalogue edits never rewrite an
existing booking.

Two surcharges are added server side rather than being picked by the customer:

- Saturday collection adds the `saturday-collection` extra charge.
- Pay on arrival adds the `pay-on-arrival-callout-fee` extra charge.

A `quote_required` item cannot be booked online and returns a `422` naming the line.

**Success — `201`**

```json
{
  "ok": true,
  "reference": "MT-2609-4821",
  "status": "submitted",
  "payment_status": "unpaid",
  "payment_option": "pay_on_arrival",
  "requires_payment": false,
  "totals": { "currency": "GBP", "subtotal_pence": 15000, "extra_charges_pence": 2500, "vat_pence": 2917, "total_pence": 17500 },
  "lines": [ { "line_type": "service_item", "name": "3 Seat Sofa", "quantity": 2, "unit_price_pence": 7500, "line_total_pence": 15000 } ]
}
```

**Status on creation**

- `payment_option: "arrival"` has nothing to pay online, so the booking is `submitted` straight away and `requires_payment` is `false`.
- `payment_option: "now"` comes back as a `draft` with `requires_payment: true`. Payment capture is Phase 11; until then those bookings sit in the admin "awaiting payment" list.

**Money**

All amounts are integer pence. Catalogue prices are stored inclusive of VAT, so
`subtotal_pence` and `total_pence` are inc-VAT and `vat_pence` is the tax portion inside
them, not an amount to add on.

**Serviceability**

Postcodes outside the listed coverage areas are still accepted. The admin booking detail
screen warns instead, so a job just outside the listed areas can still be taken on.

### `POST /api/v1/reviews`

Submits the public "write a review" form. Rate limited to 3 requests per minute per IP.
Honeypot field: `website`.

```json
{ "reviewer_name": "Jane Smith", "rating": 5, "body": "Quick, tidy and friendly.", "reviewer_email": null, "website": "" }
```

| Field | Rules |
| --- | --- |
| `reviewer_name` | required, 2-100 characters |
| `rating` | required, 1-5 |
| `body` | required, 10-2000 characters |
| `reviewer_email` | optional, never shown publicly |

A submitted review is **always stored as `pending`**, whatever the request says, and only
appears in `GET /api/v1/reviews` once a moderator publishes it. Success returns `201` with a
message telling the visitor it will appear after checking.

## Read endpoints

All read endpoints are `GET`, rate limited to 60 requests per minute per IP, and wrapped in
Laravel's resource envelope: a collection comes back as `{ "data": [ ... ] }` and a single
record as `{ "data": { ... } }`.

**Only published and active records are ever returned.** Draft or unpublished services,
inactive FAQs, items, packages, charges, regions and areas, and reviews that are not
published are invisible here. A slug that exists but is not published returns `404`.

### Image URLs

Every image is `{ id, url, alt, width, height, mime_type }`. A `url` starting with `/` is a
file bundled with the frontend itself (for example `/images/option2.jpg`) and resolves against
the site's own origin. Anything uploaded through the admin Media Library is an absolute URL on
the backend origin.

### Money shape

Every money field uses the same object, so the frontend never re-implements rounding or the
currency symbol:

```json
{ "pence": 7500, "formatted": "£75.00", "currency": "GBP" }
```

`pence` is the source of truth. Catalogue prices are stored inclusive of VAT, so `price` is
inc-VAT and `price_ex_vat` is provided alongside it. `vat_rate` is a display string such as
`"20%"`. A field is `null` when there is no amount, for example a variable extra charge.

### Pricing status

Price-carrying resources expose `pricing_status` so the frontend can label a price rather
than silently showing a number:

- `confirmed` / `active` — trustworthy.
- `placeholder` — provisional, also flagged as `is_provisional: true`.
- `quote_required` — no fixed price, also flagged as `requires_quote: true`. These cannot be
  booked online and `POST /api/v1/bookings` rejects them.

### `GET /api/v1/settings`

Public settings only, grouped by their group name. Settings with `is_public` false never
appear.

```json
{ "data": { "general": { "site_name": "MR. TEE Removals" }, "contact": { "phone": "020 8226 6477", "email": "info@wasteservices.com" }, "booking": { "saturday_collection_surcharge_pence": 5000 }, "seo": { "google_site_verification": null, "twitter_handle": null }, "business": { "locality": "Portsmouth", "region": "Hampshire", "opening_hours_spec": "Mo-Sa 07:00-19:00" }, "social": { "facebook_url": null } } }
```

The frontend reads `contact` for the header, footer and contact page. It reads `seo`
(`default_share_image_url`, `google_site_verification`, `bing_site_verification`,
`twitter_handle`) for share cards and verification tags. It reads `business` (`street_address`,
`locality`, `region`, `postal_code`, `country_code`, `opening_hours_spec`, `price_range`) for the
LocalBusiness structured data.

### `GET /api/v1/pages`

Every published page with its `slug`, `route_path`, `title`, `navigation_label`, `template` and
`seo` block, without sections. The frontend uses it for each page's title and description and
for `sitemap.xml`. `seo.is_indexable` false means the page gets a `noindex` tag and stays out of
the sitemap.

```json
"seo": {
  "meta_title": "House Clearance | MR. TEE Removals",
  "meta_description": "...",
  "og_title": null,
  "og_description": null,
  "og_image": { "id": 4, "url": "https://...", "alt": "...", "width": 1200, "height": 630, "mime_type": "image/jpeg" },
  "canonical_url": null,
  "is_indexable": true,
  "is_followable": true
}
```

Blank `og_title` and `og_description` fall back to the meta title and description. A `null`
`og_image` falls back to the `seo.default_share_image_url` setting. `canonical_url` is either a
path on this site or a full http(s) address; `null` means the page's own route.
`is_followable` false adds `nofollow`.

### `GET /api/v1/pages/{slug}`

A published page with its enabled sections and section items, each in sort order. Returns
`404` for a draft page.

Section items are not seeded yet, so `sections` is currently empty for every page.

### `GET /api/v1/services`

Published services as summaries. Query parameters:

| Parameter | Effect |
| --- | --- |
| `category` | filter by service category slug |
| `featured` | `1` returns only featured services |
| `bookable` | `1` returns only bookable services |

### `GET /api/v1/services/{slug}`

The full service page payload: details, category, hero image, SEO from the linked page,
content blocks with their items, related services as summaries, and the FAQs assigned to
that service.

`seo` is absent when the service has no linked page, because service SEO is stored on the
page record.

### `GET /api/v1/price-categories`

Active categories with their image and an `item_count` of active items.

Pass `with_items=1` to embed each category's active items as `items`, so the booking page
loads the whole catalogue in one request instead of one per category.

### `GET /api/v1/price-categories/{slug}/items`

Active items in one category, in sort order.

### `GET /api/v1/load-packages`

Active load packages with `price`, `price_ex_vat`, a `capacity` block
(`max_weight_kg`, `volume_cubic_yards`, `sack_equivalent`, `loading_time_minutes`) and
`is_popular`.

### `GET /api/v1/extra-charges`

Active extra charges. `is_variable` charges are quoted per job and their `amount` is `null`.

### `GET /api/v1/faqs`

Active FAQs. With no parameter this returns the **general** FAQs for the FAQ page. Pass
`service=<slug>` for the questions attached to one service.

### `GET /api/v1/reviews`

Published reviews, newest first. The reviewer's email address is never exposed.

| Parameter | Effect |
| --- | --- |
| `service` | filter by service slug |
| `min_rating` | only reviews at or above this rating |
| `limit` | default 12, maximum 50 |

### `GET /api/v1/coverage`

Active coverage regions, each with its active areas nested in sort order. `coordinates` is
`null` when an area has no latitude and longitude.

## Frontend integration

The Next.js frontend consumes this API as of Phase 13:

- `src/lib/api.js` holds `apiGet` (server-side reads with a 5 minute revalidate, returning
  `null` on any failure) and `apiPost` (browser submits, always resolving to
  `{ ok, status, data, message, errors }`).
- Every page that reads from the API keeps its original hardcoded content as a fallback, so a
  build or a request never fails just because the backend is down.
- `NEXT_PUBLIC_API_URL` sets the backend base URL and is inlined at build time.
- `src/lib/seo.js` gives every page `generateMetadata` from `GET /api/v1/pages`, matched on `route_path`: the title, description, canonical link, robots tag, Open Graph and X share card. It also builds `sitemap.xml`. The root layout adds the verification tags and a LocalBusiness JSON-LD block from `GET /api/v1/settings` and `GET /api/v1/coverage`. `NEXT_PUBLIC_SITE_URL` sets the absolute site URL for canonical links, share images and the sitemap.
- `src/lib/services.js` feeds the 19 service pages from `GET /api/v1/services/{slug}`. The hero uses `headline`, `summary` and `hero_image`, each falling back to the built-in copy. `components/service/ServiceExtras.jsx` renders the service's extra content blocks, FAQs (with FAQPage JSON-LD) and related services above the quote form. The `service_overview` block is skipped because each page's own design covers it. Links from admin block items are only used when they are site paths or http(s), tel or mailto addresses. Uploaded images load directly from the backend (`unoptimized`), because Next 16 refuses to optimise images from local IPs.
- `src/lib/site.js` reads the contact details for the header, footer and contact page from `GET /api/v1/settings`.
- Payments are deferred, so the checkout never collects card details. A pay-now booking is a
  `draft` that the office follows up by hand.
