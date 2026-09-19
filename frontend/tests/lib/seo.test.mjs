import { afterEach, describe, test } from 'node:test'
import assert from 'node:assert/strict'
import {
  getPublishedPages,
  jsonLdString,
  localBusinessJsonLd,
  metadataFor,
  siteMetadata,
  siteMetadataDefaults,
  twitterHandle,
} from '../../src/lib/seo.js'

const realFetch = globalThis.fetch

afterEach(() => {
  globalThis.fetch = realFetch
})

// Answers each API path from `routes`; anything else is a 404.
function serve(routes) {
  globalThis.fetch = async (url) => {
    const path = new URL(url).pathname.replace(/^\/api\/v1\//, '')

    return path in routes
      ? { ok: true, status: 200, json: async () => ({ data: routes[path] }) }
      : { ok: false, status: 404, json: async () => ({}) }
  }
}

function servePages(pages, settings = {}) {
  serve({ pages, settings })
}

function backendDown() {
  globalThis.fetch = async () => {
    throw new TypeError('fetch failed')
  }
}

const pages = [
  {
    slug: 'house-clearance',
    route_path: '/houseClearance',
    title: 'House Clearance',
    seo: { meta_title: 'House clearance | MR. TEE', meta_description: 'Fast, tidy house clearance.', is_indexable: true },
  },
  {
    slug: 'checkout',
    route_path: '/checkout',
    title: 'Checkout',
    seo: { meta_title: 'Checkout | MR. TEE', meta_description: null, is_indexable: false },
  },
]

describe('metadataFor', () => {
  test('uses the SEO fields set in the admin', async () => {
    servePages(pages)

    const metadata = await metadataFor('/houseClearance', { title: 'Fallback' })

    assert.equal(metadata.title, 'House clearance | MR. TEE')
    assert.equal(metadata.description, 'Fast, tidy house clearance.')
    assert.equal(metadata.alternates.canonical, '/houseClearance')
    assert.equal(metadata.openGraph.title, 'House clearance | MR. TEE')
    assert.equal(metadata.robots, undefined)
  })

  test('marks a page the admin hid from search as noindex', async () => {
    servePages(pages)

    const metadata = await metadataFor('/checkout')

    assert.deepEqual(metadata.robots, { index: false, follow: false })
  })

  test('uses the share card, canonical and nofollow set in the admin', async () => {
    servePages([
      {
        slug: 'house-clearance',
        route_path: '/houseClearance',
        title: 'House Clearance',
        seo: {
          meta_title: 'House clearance | MR. TEE',
          meta_description: 'Fast, tidy house clearance.',
          og_title: 'House clearance in Portsmouth',
          og_description: 'Cleared in a day.',
          og_image: { id: 4, url: 'https://cdn.example/share.jpg', alt: 'A cleared room' },
          canonical_url: '/clearance',
          is_indexable: true,
          is_followable: false,
        },
      },
    ], { seo: { twitter_handle: 'https://x.com/mrtee' } })

    const metadata = await metadataFor('/houseClearance')

    assert.equal(metadata.title, 'House clearance | MR. TEE')
    assert.equal(metadata.openGraph.title, 'House clearance in Portsmouth')
    assert.equal(metadata.openGraph.description, 'Cleared in a day.')
    assert.deepEqual(metadata.openGraph.images, [{ url: 'https://cdn.example/share.jpg', alt: 'A cleared room' }])
    assert.equal(metadata.alternates.canonical, '/clearance')
    assert.equal(metadata.twitter.site, '@mrtee')
    assert.equal(metadata.twitter.card, 'summary_large_image')
    assert.deepEqual(metadata.robots, { index: true, follow: false })
  })

  test('shares the default image when the page has none', async () => {
    servePages(pages, { seo: { default_share_image_url: 'https://cdn.example/default.jpg' } })

    const metadata = await metadataFor('/houseClearance')

    assert.equal(metadata.openGraph.title, 'House clearance | MR. TEE')
    assert.equal(metadata.openGraph.images[0].url, 'https://cdn.example/default.jpg')
    assert.equal(metadata.twitter.site, undefined)
  })

  test('falls back to the built-in title when the backend is down', async () => {
    backendDown()

    const metadata = await metadataFor('/houseClearance', { title: 'House Clearance | MR. TEE Removals' })

    assert.equal(metadata.title, 'House Clearance | MR. TEE Removals')
    assert.ok(metadata.description.length > 20, 'a default description is always present')
  })

  test('keeps checkout out of search even when the backend is down', async () => {
    backendDown()

    const metadata = await metadataFor('/checkout', { title: 'Checkout', indexable: false })

    assert.deepEqual(metadata.robots, { index: false, follow: false })
  })

  test('uses the page title when the admin left the meta title blank', async () => {
    servePages([{ slug: 'faq', route_path: '/faq', title: 'Frequently Asked Questions', seo: { meta_title: null, meta_description: null, is_indexable: true } }])

    const metadata = await metadataFor('/faq')

    assert.equal(metadata.title, 'Frequently Asked Questions')
  })
})

describe('getPublishedPages', () => {
  test('returns an empty list rather than failing when the backend is down', async () => {
    backendDown()
    assert.deepEqual(await getPublishedPages(), [])
  })
})

describe('siteMetadata', () => {
  test('adds the verification codes set in the admin', async () => {
    serve({ settings: { seo: { google_site_verification: ' abc123 ', bing_site_verification: 'BING42' } } })

    const metadata = await siteMetadata()

    assert.deepEqual(metadata.verification, { google: 'abc123', other: { 'msvalidate.01': 'BING42' } })
    assert.ok(metadata.metadataBase instanceof URL)
  })

  test('is the plain defaults without codes or a backend', async () => {
    backendDown()
    assert.equal(await siteMetadata(), siteMetadataDefaults)
  })
})

describe('twitterHandle', () => {
  test('accepts a name, a handle or a profile URL', () => {
    assert.equal(twitterHandle('mrtee'), '@mrtee')
    assert.equal(twitterHandle('@mrtee'), '@mrtee')
    assert.equal(twitterHandle('https://twitter.com/mrtee?lang=en'), '@mrtee')
    assert.equal(twitterHandle(''), null)
    assert.equal(twitterHandle('not a handle'), null)
  })
})

describe('localBusinessJsonLd', () => {
  test('describes the business from the settings and coverage', () => {
    const data = localBusinessJsonLd(
      {
        general: { site_name: 'MR. TEE Removals' },
        contact: { phone: '020 8226 6477', email: 'info@wasteservices.com' },
        business: { locality: 'Portsmouth', region: 'Hampshire', postal_code: '', country_code: 'GB', opening_hours_spec: 'Mo-Sa 07:00-19:00', price_range: '££' },
        social: { facebook_url: 'https://facebook.com/mrtee', x_url: null },
      },
      [
        { name: 'Portsmouth', areas: [{ name: 'Southsea' }, { name: 'Cosham' }] },
        { name: 'Fareham & Gosport', areas: [{ name: 'Gosport' }, { name: 'Southsea' }] },
      ],
    )

    assert.equal(data['@type'], 'LocalBusiness')
    assert.equal(data.telephone, '020 8226 6477')
    assert.equal(data.openingHours, 'Mo-Sa 07:00-19:00')
    assert.deepEqual(data.address, { '@type': 'PostalAddress', addressLocality: 'Portsmouth', addressRegion: 'Hampshire', addressCountry: 'GB' })
    assert.deepEqual(data.areaServed.map((place) => place.name), ['Southsea', 'Cosham', 'Gosport'])
    assert.deepEqual(data.sameAs, ['https://facebook.com/mrtee'])
  })

  test('still describes a Portsmouth business with no settings at all', () => {
    const data = localBusinessJsonLd()

    assert.equal(data.name, 'MR. TEE Removals')
    assert.equal(data.address.addressLocality, 'Portsmouth')
    assert.equal(data.areaServed, undefined)
  })

  test('cannot be broken out of its script tag', () => {
    const json = jsonLdString({ name: '</script><script>alert(1)</script>' })

    assert.doesNotMatch(json, /</)
    assert.equal(JSON.parse(json).name, '</script><script>alert(1)</script>')
  })
})

describe('site defaults', () => {
  test('replace the create-next-app placeholder description', () => {
    assert.doesNotMatch(siteMetadataDefaults.description, /create next app/i)
    assert.ok(siteMetadataDefaults.metadataBase instanceof URL)
  })
})
