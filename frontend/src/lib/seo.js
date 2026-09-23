// Relative, not `@/lib/api`, so Node's test runner can load this file without the alias.
import { apiGet } from './api.js'

// Absolute public URL of this site, used for canonical links and the sitemap. Inlined at
// build time like the API URL, so it must be referenced literally.
export const SITE_URL = (process.env.NEXT_PUBLIC_SITE_URL || 'http://localhost:3000').replace(/\/+$/, '')

export const SITE_NAME = 'MR. TEE Removals'

const DEFAULT_DESCRIPTION = 'Rubbish removal, house and garden clearance, commercial waste collection and cleaning services, booked online.'

// Shown when a page has no share image and the admin has not set a site default.
const DEFAULT_SHARE_IMAGE = '/images/HeroImage.jpg'

/**
 * Every published page with its route and SEO fields, as managed in the admin. Identical
 * fetches in one render are deduplicated by Next, so each page's metadata and the sitemap
 * share the request.
 */
export async function getPublishedPages() {
  return (await apiGet('pages')) ?? []
}

async function getSettings() {
  return (await apiGet('settings')) ?? {}
}

/**
 * `@handle` from whatever the admin typed: a bare name, a handle, or a profile URL.
 */
export function twitterHandle(value) {
  const name = String(value ?? '').trim().replace(/^https?:\/\/(www\.)?(twitter|x)\.com\//i, '').replace(/^@+/, '').split(/[/?#]/)[0]

  return /^\w{1,15}$/.test(name) ? `@${name}` : null
}

/**
 * Metadata for the page at `routePath`, taken from the admin's SEO fields and falling back
 * to the given title and description when the backend is unreachable or the page has none.
 * A `fallback.indexable` or `fallback.followable` always wins, so pages such as checkout
 * stay out of search even if the admin setting is wrong or unavailable.
 */
export async function metadataFor(routePath, fallback = {}) {
  const [pages, settings] = await Promise.all([getPublishedPages(), getSettings()])
  const page = pages.find((entry) => entry.route_path === routePath)
  const seo = page?.seo ?? {}

  const title = seo.meta_title || fallback.title || page?.title || SITE_NAME
  const description = seo.meta_description || fallback.description || DEFAULT_DESCRIPTION
  const shareTitle = seo.og_title || title
  const shareDescription = seo.og_description || description
  const image = seo.og_image?.url
    ? { url: seo.og_image.url, alt: seo.og_image.alt || shareTitle }
    : { url: settings.seo?.default_share_image_url || DEFAULT_SHARE_IMAGE, alt: shareTitle }
  const canonical = seo.canonical_url || routePath
  const indexable = fallback.indexable ?? seo.is_indexable ?? true
  const followable = fallback.followable ?? seo.is_followable ?? indexable
  const site = twitterHandle(settings.seo?.twitter_handle)

  return {
    title,
    description,
    alternates: { canonical },
    openGraph: {
      title: shareTitle,
      description: shareDescription,
      url: canonical,
      siteName: settings.general?.site_name || SITE_NAME,
      locale: 'en_GB',
      type: 'website',
      images: [image],
    },
    twitter: {
      card: 'summary_large_image',
      title: shareTitle,
      description: shareDescription,
      images: [image.url],
      ...(site ? { site } : {}),
    },
    ...(indexable && followable ? {} : { robots: { index: indexable, follow: followable } }),
  }
}

export const siteMetadataDefaults = {
  metadataBase: new URL(SITE_URL),
  title: SITE_NAME,
  description: DEFAULT_DESCRIPTION,
}

/**
 * Site-wide metadata for the root layout: the defaults above plus the search engine
 * verification codes set in the admin.
 */
export async function siteMetadata() {
  const seo = (await getSettings()).seo ?? {}
  const google = String(seo.google_site_verification ?? '').trim()
  const bing = String(seo.bing_site_verification ?? '').trim()

  if (!google && !bing) return siteMetadataDefaults

  return {
    ...siteMetadataDefaults,
    verification: {
      ...(google ? { google } : {}),
      ...(bing ? { other: { 'msvalidate.01': bing } } : {}),
    },
  }
}

/**
 * schema.org LocalBusiness data for search engines, from the Site Settings and the coverage
 * areas. Blank settings are left out rather than sent empty.
 */
export function localBusinessJsonLd(settings = {}, coverage = []) {
  const business = settings.business ?? {}
  const contact = settings.contact ?? {}
  const social = Object.values(settings.social ?? {}).filter((url) => typeof url === 'string' && /^https?:\/\//.test(url))
  const compact = (object) => Object.fromEntries(Object.entries(object).filter(([, value]) => value !== null && value !== undefined && value !== '' && !(Array.isArray(value) && value.length === 0)))

  const areas = (coverage ?? []).flatMap((region) => (region.areas ?? []).map((area) => area.name))

  return compact({
    '@context': 'https://schema.org',
    '@type': 'LocalBusiness',
    '@id': `${SITE_URL}/#business`,
    name: settings.general?.site_name || SITE_NAME,
    url: SITE_URL,
    image: new URL(settings.seo?.default_share_image_url || DEFAULT_SHARE_IMAGE, SITE_URL).toString(),
    telephone: contact.phone,
    email: contact.email,
    priceRange: business.price_range,
    openingHours: business.opening_hours_spec,
    address: compact({
      '@type': 'PostalAddress',
      streetAddress: business.street_address,
      addressLocality: business.locality || 'Portsmouth',
      addressRegion: business.region || 'Hampshire',
      postalCode: business.postal_code,
      addressCountry: business.country_code || 'GB',
    }),
    areaServed: [...new Set(areas)].map((name) => ({ '@type': 'Place', name })),
    sameAs: social,
  })
}

/**
 * The LocalBusiness data for the root layout, fetched from the API.
 */
export async function getLocalBusinessJsonLd() {
  const [settings, coverage] = await Promise.all([getSettings(), apiGet('coverage')])

  return localBusinessJsonLd(settings, coverage ?? [])
}

/**
 * JSON for a `<script type="application/ld+json">`, with `<` escaped so admin-entered text
 * can never close the script tag.
 */
export function jsonLdString(data) {
  return JSON.stringify(data).replace(/</g, '\\u003c')
}
