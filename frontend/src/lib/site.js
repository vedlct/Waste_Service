// Relative, not `@/lib/api`, so Node's test runner can load this file without the alias.
import { apiGet } from './api.js'

// Shown when the backend is unreachable. Kept in step with the seeded site settings.
export const FALLBACK_CONTACT = {
  siteName: 'MR. TEE Removals',
  phone: '020 8226 6477',
  email: 'info@wasteservices.com',
  location: 'Portsmouth, United Kingdom',
  openingHours: 'Mon–Sat, 7:00am–7:00pm',
  social: {},
}

/**
 * The contact details and site name managed in the admin's Site Settings, falling back to
 * the built-in values for any that are missing or when the backend is down.
 */
export async function getSiteContact() {
  const settings = (await apiGet('settings')) ?? {}
  const contact = settings.contact ?? {}
  const social = Object.fromEntries(
    Object.entries(settings.social ?? {}).filter(([, url]) => typeof url === 'string' && url !== ''),
  )

  return {
    siteName: settings.general?.site_name || FALLBACK_CONTACT.siteName,
    phone: contact.phone || FALLBACK_CONTACT.phone,
    email: contact.email || FALLBACK_CONTACT.email,
    location: contact.location || FALLBACK_CONTACT.location,
    openingHours: contact.opening_hours || FALLBACK_CONTACT.openingHours,
    social,
  }
}

/**
 * A `tel:` link target: digits and a leading plus only.
 */
export function telHref(phone) {
  return `tel:${String(phone ?? '').replace(/[^\d+]/g, '')}`
}
