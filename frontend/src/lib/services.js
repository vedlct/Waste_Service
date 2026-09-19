// Relative, not `@/lib/api`, so Node's test runner can load this file without the alias.
import { apiGet } from './api.js'

// Blocks whose content the bespoke page layout already shows, so they are not repeated
// below it.
const BUILT_IN_COMPONENTS = new Set(['service_overview'])

/**
 * The full service payload managed in the admin: headline, summary, hero image, content
 * blocks, related services and FAQs. `null` when the backend is down or the service is not
 * published, so the page falls back to its built-in content.
 */
export async function getService(slug) {
  return apiGet(`services/${encodeURIComponent(slug)}`)
}

/**
 * Hero copy and image from the service, falling back field by field to the page's
 * built-in values.
 */
export function heroContent(service, fallback) {
  const image = service?.hero_image?.url

  return {
    title: service?.headline || fallback.title,
    description: service?.summary || fallback.description,
    image: image || fallback.image,
    imageAlt: (image && service.hero_image.alt) || fallback.imageAlt,
  }
}

/**
 * Enabled blocks added in the admin that the bespoke page does not already show.
 */
export function extraBlocks(service) {
  return (service?.blocks ?? []).filter((block) => !BUILT_IN_COMPONENTS.has(block.component))
}

/**
 * A link target from admin input, allowed only when it stays on this site or goes to an
 * http(s), tel or mailto address.
 */
export function safeHref(url) {
  const value = String(url ?? '').trim()

  if (/^\/(?!\/)/.test(value) || /^#/.test(value)) return value
  if (/^(https?:|tel:|mailto:)/i.test(value)) return value

  return null
}

/**
 * Plain admin text split into paragraphs on blank lines.
 */
export function paragraphs(text) {
  return String(text ?? '')
    .split(/\r?\n\s*\r?\n/)
    .map((paragraph) => paragraph.trim())
    .filter(Boolean)
}

/**
 * schema.org FAQPage data for the service's FAQs, or `null` when there are none.
 */
export function faqJsonLd(faqs) {
  const entries = (faqs ?? []).filter((faq) => faq.question && faq.answer)

  if (entries.length === 0) return null

  return {
    '@context': 'https://schema.org',
    '@type': 'FAQPage',
    mainEntity: entries.map((faq) => ({
      '@type': 'Question',
      name: faq.question,
      acceptedAnswer: { '@type': 'Answer', text: faq.answer },
    })),
  }
}
