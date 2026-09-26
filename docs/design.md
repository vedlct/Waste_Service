# Frontend Design System

The public site follows the look of MR. TEE Removals' own flyers and van livery: royal blue,
ink and white, bold condensed headings, and real photos of rubbish, vans and crews.

## Brand

- Logo: `frontend/public/images/brand/logo.png` (light backgrounds) and `logo-white.png` (dark
  backgrounds), built from the company's own logo artwork. `logo-mark-*.png` is the box icon alone.
- Default share image: `frontend/public/images/brand/og-default.jpg` (Spinnaker Tower and logo).
- Contact details come from the flyers: 078 6213 8033, 023 9378 8241, info@mrteeremovals.co.uk,
  www.mrteeremovals.co.uk. They live in Site Settings (`contact.*`), with fallbacks in
  `frontend/src/lib/site.js`.

## Tokens

Defined in `frontend/src/app/globals.css` under `@theme`, used as Tailwind classes:

| Token | Use |
| --- | --- |
| `brand-50` to `brand-950` | Royal blue scale. `brand-600` (#1f5fd9) is the primary button and accent. Dark surfaces (footer, banners, overlays) use `brand-700` to `brand-950`. |
| `ink` (#0b1220) | Headings and body text only. |
| `mist` (#f4f7fb) | Alternate section background. |
| `line` (#e4e9f2) | Borders and dividers. |

Do not add one-off hex colours. Text on a solid `brand-500` or darker background is white.
The client's direction: professional, white and blue, no black, no dot patterns or other
decoration, and full-size heroes with sharp photos. Pages are mostly white and `mist`, with blue
as the accent. Never use black or `ink` as a background.

## Type

- Body: Inter (`font-sans`).
- Headings: Barlow Condensed (`font-display`), applied to every `h1` and `h2`. Section headings are
  uppercase, bold, `leading-[0.95]`, with the second line in blue.

## Components

- `ui/SectionHeader`: eyebrow, title, blue accent line and intro for content sections.
- `ui/PageBanner`: the photo title band for non-service pages (prices, contact, FAQ, areas).
- `ServiceHero`: the split hero for all 19 service pages; content comes from the service API.
- `.tag-slant`: the slanted blue label from the flyers (for example "PORTSMOUTH").
- `.eyebrow`: the small uppercase label above a heading.
- `home/QuickQuote`: the instant price box in the home hero. It checks the postcode against
  `GET /api/v1/coverage` in the browser (`src/lib/coverage.js`, matching the backend's outward-code rule)
  and sends the visitor to `/prices`.
- Heroes (home, `ServiceHero`, `PageBanner`) all use `ui/PhotoHero`: a full-width photo shown
  completely clear, with no overlay, tint or blur, and the text on a solid white card (over the
  photo on large screens, below it on phones). The home hero fills the screen. No dots, rotated
  panels, floating cards, gradient text or blurred blobs.
- `home/StatsBand`: the one solid blue band on the home page; the area count comes from the
  coverage API.
- Buttons are `rounded-lg`; cards are `rounded-2xl` with a `line` border.

## Photography

Match each photo to what the section says, as UK clearance companies do (a room being cleared
for house clearance, a dumped sofa for furniture, bags of green waste for garden). Prefer bright
daylight real-work photos: crews carrying bags, loaded vans, piles of furniture, garden waste,
and Portsmouth landmarks. Avoid white-background product shots and anything showing London.
When the company supplies its own photos (the crew in branded polos, the van), use them for
the home hero, `WhyChoose` and the service heroes first.

The homepage has two project-owned, logo-referenced campaign images for consistent van branding:

- `frontend/public/images/mr-tee-van-hero-v2.png` — the full-van homepage hero, composed with safe
  space for the desktop content card.
- `frontend/public/images/mr-tee-team-loading.png` — the team loading a sofa into the branded van,
  displayed uncropped in the `WhyChoose` section.
- `frontend/public/images/mr-tee-recycling-step.png` — the team separating cardboard and reusable
  furniture at a licensed-style recycling facility, used as the final `HowItWorks` step.
- `frontend/public/images/how-it-works-booking.png` — a customer confirming a booking with a
  branded team member at the doorstep, used only in the first `HowItWorks` step.
- `frontend/public/images/how-it-works-loading.png` — the team loading a chest of drawers into the
  branded van, used only in the second `HowItWorks` step.

`home/HowItWorks` is a compact light-grey process panel with three white photo cards and restrained
blue accents. Every step has its own dedicated scene so imagery from the hero and `WhyChoose` is
not repeated.

The `home/ServicesShowcase` grid deliberately mixes photographic compositions instead of repeating
crew-and-van scenes. Seven cards use real Pexels stock photography (empty room/boxes, discarded
furniture, garden wheelbarrow, organised garage, demolition waste, office interior and outdoor
waste), while only Man & Van uses the dedicated branded asset
`frontend/public/images/service-man-van-branded.png`. The Pexels source photo IDs are 7203788,
28983909, 26827231, 29286299, 11818456, 16926422 and 17548775.
