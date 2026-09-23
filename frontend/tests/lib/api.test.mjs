import { afterEach, describe, test } from 'node:test'
import assert from 'node:assert/strict'
import { apiGet, apiPost, apiUrl, timeAgo } from '../../src/lib/api.js'

const realFetch = globalThis.fetch

afterEach(() => {
  globalThis.fetch = realFetch
})

function respondWith(status, body, { json = true } = {}) {
  const calls = []
  globalThis.fetch = async (url, options) => {
    calls.push({ url, options })
    return {
      ok: status >= 200 && status < 300,
      status,
      json: async () => {
        if (!json) throw new SyntaxError('Unexpected token <')
        return body
      },
    }
  }
  return calls
}

describe('apiUrl', () => {
  test('builds a versioned URL without doubled slashes', () => {
    assert.match(apiUrl('/faqs'), /\/api\/v1\/faqs$/)
    assert.match(apiUrl('faqs'), /\/api\/v1\/faqs$/)
    assert.doesNotMatch(apiUrl('/faqs'), /v1\/\/faqs/)
  })
})

describe('apiGet', () => {
  test('returns the data payload and asks Next to revalidate', async () => {
    const calls = respondWith(200, { data: [{ id: 1 }] })

    assert.deepEqual(await apiGet('faqs'), [{ id: 1 }])
    assert.equal(calls[0].options.next.revalidate, 300)
    assert.equal(calls[0].options.headers.Accept, 'application/json')
  })

  test('passes a custom revalidate window', async () => {
    const calls = respondWith(200, { data: [] })
    await apiGet('faqs', { revalidate: 60 })
    assert.equal(calls[0].options.next.revalidate, 60)
  })

  test('returns null on an error status so the page can fall back', async () => {
    respondWith(500, { message: 'Server Error' })
    assert.equal(await apiGet('faqs'), null)
  })

  test('returns null when the backend is unreachable', async () => {
    globalThis.fetch = async () => {
      throw new TypeError('fetch failed')
    }
    assert.equal(await apiGet('faqs'), null)
  })

  test('returns null when the body has no data key', async () => {
    respondWith(200, { message: 'unexpected' })
    assert.equal(await apiGet('faqs'), null)
  })
})

describe('apiPost', () => {
  test('resolves ok with the server message on success', async () => {
    const calls = respondWith(201, { ok: true, message: 'Thanks', reference: 'MT-2609-0001' })

    const result = await apiPost('bookings', { items: [] })

    assert.equal(result.ok, true)
    assert.equal(result.status, 201)
    assert.equal(result.message, 'Thanks')
    assert.equal(result.data.reference, 'MT-2609-0001')
    assert.equal(calls[0].options.method, 'POST')
    assert.equal(calls[0].options.body, JSON.stringify({ items: [] }))
  })

  test('surfaces the first field error from a 422, not the summary', async () => {
    respondWith(422, {
      message: 'Please enter a valid name. (and 3 more errors)',
      errors: { name: ['Please enter a valid name.'], email: ['Please enter a valid email address.'] },
    })

    const result = await apiPost('enquiries', {})

    assert.equal(result.ok, false)
    assert.equal(result.status, 422)
    assert.equal(result.message, 'Please enter a valid name.')
    assert.deepEqual(Object.keys(result.errors), ['name', 'email'])
  })

  test('explains rate limiting', async () => {
    respondWith(429, { message: 'Too Many Attempts.' })
    assert.match((await apiPost('reviews', {})).message, /wait a minute/)
  })

  test('explains an oversized body', async () => {
    respondWith(413, { message: 'Your submission is too large to accept.' })
    assert.match((await apiPost('enquiries', {})).message, /too large/)
  })

  test('never throws when the network fails', async () => {
    globalThis.fetch = async () => {
      throw new TypeError('fetch failed')
    }

    const result = await apiPost('enquiries', {})

    assert.equal(result.ok, false)
    assert.equal(result.status, 0)
    assert.match(result.message, /could not reach/)
  })

  test('copes with an error page that is not JSON', async () => {
    respondWith(502, null, { json: false })

    const result = await apiPost('enquiries', {})

    assert.equal(result.ok, false)
    assert.match(result.message, /Something went wrong/)
  })
})

describe('timeAgo', () => {
  const ago = (seconds) => new Date(Date.now() - seconds * 1000).toISOString()

  test('handles missing dates', () => {
    assert.equal(timeAgo(null), '')
  })

  test('says just now for under a minute', () => {
    assert.equal(timeAgo(ago(20)), 'Just now')
  })

  test('uses singular and plural units', () => {
    assert.equal(timeAgo(ago(60)), '1 minute ago')
    assert.equal(timeAgo(ago(3 * 3600)), '3 hours ago')
    assert.equal(timeAgo(ago(40 * 86400)), '1 month ago')
    assert.equal(timeAgo(ago(800 * 86400)), '2 years ago')
  })

  test('never reports a future date as negative', () => {
    assert.equal(timeAgo(new Date(Date.now() + 3600 * 1000).toISOString()), 'Just now')
  })
})
