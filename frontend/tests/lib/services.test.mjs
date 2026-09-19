import { afterEach, describe, test } from 'node:test'
import assert from 'node:assert/strict'
import { extraBlocks, faqJsonLd, getService, heroContent, paragraphs, safeHref } from '../../src/lib/services.js'

const realFetch = globalThis.fetch

afterEach(() => {
  globalThis.fetch = realFetch
})

const fallback = {
  title: 'Garden clearance in Portsmouth',
  description: 'Built-in intro.',
  image: '/images/GardenHero.jpg',
  imageAlt: 'Garden clearance',
}

describe('getService', () => {
  test('requests the service by slug', async () => {
    let requested
    globalThis.fetch = async (url) => {
      requested = String(url)
      return { ok: true, status: 200, json: async () => ({ data: { slug: 'garden-clearance' } }) }
    }

    assert.deepEqual(await getService('garden-clearance'), { slug: 'garden-clearance' })
    assert.match(requested, /\/api\/v1\/services\/garden-clearance$/)
  })

  test('is null when the backend is down', async () => {
    globalThis.fetch = async () => {
      throw new TypeError('fetch failed')
    }

    assert.equal(await getService('garden-clearance'), null)
  })
})

describe('heroContent', () => {
  test('uses the admin headline, summary and image', () => {
    const hero = heroContent(
      { headline: 'Garden waste gone today', summary: 'Admin intro.', hero_image: { url: 'https://api.example/storage/garden.jpg', alt: 'A tidy garden' } },
      fallback,
    )

    assert.deepEqual(hero, {
      title: 'Garden waste gone today',
      description: 'Admin intro.',
      image: 'https://api.example/storage/garden.jpg',
      imageAlt: 'A tidy garden',
    })
  })

  test('falls back field by field', () => {
    const hero = heroContent({ headline: '', summary: 'Admin intro.', hero_image: null }, fallback)

    assert.equal(hero.title, fallback.title)
    assert.equal(hero.description, 'Admin intro.')
    assert.equal(hero.image, fallback.image)
    assert.equal(hero.imageAlt, fallback.imageAlt)
  })

  test('keeps the built-in alt text for an admin image without one', () => {
    const hero = heroContent({ hero_image: { url: '/images/new.jpg', alt: null } }, fallback)

    assert.equal(hero.image, '/images/new.jpg')
    assert.equal(hero.imageAlt, fallback.imageAlt)
  })

  test('is the fallback when there is no service', () => {
    assert.deepEqual(heroContent(null, fallback), fallback)
  })
})

describe('extraBlocks', () => {
  test('skips the overview block the page design already shows', () => {
    const service = { blocks: [{ component: 'service_overview' }, { component: 'steps' }, { component: 'cta' }] }

    assert.deepEqual(extraBlocks(service).map((block) => block.component), ['steps', 'cta'])
    assert.deepEqual(extraBlocks(null), [])
  })
})

describe('safeHref', () => {
  test('allows site paths and web, phone and email links', () => {
    for (const url of ['/prices', '#quote', 'https://example.com', 'tel:+442392000000', 'mailto:info@example.com']) {
      assert.equal(safeHref(url), url)
    }
  })

  test('rejects script and protocol-relative links', () => {
    for (const url of ['javascript:alert(1)', ' JavaScript:alert(1)', '//evil.example', 'data:text/html,hi', '', null]) {
      assert.equal(safeHref(url), null)
    }
  })
})

describe('paragraphs', () => {
  test('splits on blank lines and drops empty ones', () => {
    assert.deepEqual(paragraphs('One.\n\nTwo\nlines.\r\n\r\n\n  '), ['One.', 'Two\nlines.'])
    assert.deepEqual(paragraphs(null), [])
  })
})

describe('faqJsonLd', () => {
  test('describes answered questions as an FAQPage', () => {
    const data = faqJsonLd([
      { question: 'Do you take fridges?', answer: 'Yes.' },
      { question: 'Unanswered?', answer: '' },
    ])

    assert.equal(data['@type'], 'FAQPage')
    assert.deepEqual(data.mainEntity, [
      { '@type': 'Question', name: 'Do you take fridges?', acceptedAnswer: { '@type': 'Answer', text: 'Yes.' } },
    ])
  })

  test('is null without FAQs', () => {
    assert.equal(faqJsonLd([]), null)
    assert.equal(faqJsonLd(undefined), null)
  })
})
