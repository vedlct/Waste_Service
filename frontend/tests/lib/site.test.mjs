import { afterEach, describe, test } from 'node:test'
import assert from 'node:assert/strict'
import { FALLBACK_CONTACT, getSiteContact, telHref } from '../../src/lib/site.js'

const realFetch = globalThis.fetch

afterEach(() => {
  globalThis.fetch = realFetch
})

function serveSettings(settings) {
  globalThis.fetch = async () => ({ ok: true, status: 200, json: async () => ({ data: settings }) })
}

describe('getSiteContact', () => {
  test('uses the contact details set in the admin', async () => {
    serveSettings({
      general: { site_name: 'MR. TEE' },
      contact: { phone: '023 9200 0000', email: 'hello@mrtee.test', location: 'Southsea, Portsmouth', opening_hours: 'Mon–Fri' },
      social: { facebook: 'https://facebook.com/mrtee', instagram: '' },
    })

    assert.deepEqual(await getSiteContact(), {
      siteName: 'MR. TEE',
      phone: '023 9200 0000',
      email: 'hello@mrtee.test',
      location: 'Southsea, Portsmouth',
      openingHours: 'Mon–Fri',
      social: { facebook: 'https://facebook.com/mrtee' },
    })
  })

  test('falls back per field for blank or missing settings', async () => {
    serveSettings({ contact: { phone: '023 9200 0000', email: '' } })

    const contact = await getSiteContact()

    assert.equal(contact.phone, '023 9200 0000')
    assert.equal(contact.email, FALLBACK_CONTACT.email)
    assert.equal(contact.location, 'Portsmouth, United Kingdom')
  })

  test('falls back entirely when the backend is down', async () => {
    globalThis.fetch = async () => {
      throw new TypeError('fetch failed')
    }

    assert.deepEqual(await getSiteContact(), FALLBACK_CONTACT)
  })
})

describe('telHref', () => {
  test('keeps digits and a leading plus only', () => {
    assert.equal(telHref('020 8226 6477'), 'tel:02082266477')
    assert.equal(telHref('+44 (0)23 9200-0000'), 'tel:+4402392000000')
    assert.equal(telHref(null), 'tel:')
  })
})
