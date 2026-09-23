// Client for the Laravel backend's versioned public API.
//
// NEXT_PUBLIC_API_URL is inlined at build time, so it must be referenced literally here
// rather than through a variable (see node_modules/next/dist/docs, environment-variables).
const API_BASE = (process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000').replace(/\/+$/, '')

// Content changes rarely and every page keeps a hardcoded fallback, so a short
// revalidation window is enough to pick up admin edits without hitting Laravel on
// every request.
const DEFAULT_REVALIDATE_SECONDS = 300

// Server-only (no NEXT_PUBLIC_ prefix), so it is never inlined into a browser bundle. It lets
// the backend tell this server's requests apart from visitors sharing its rate limit.
function serverHeaders() {
  const key = typeof window === 'undefined' ? process.env.FRONTEND_API_KEY : undefined

  return key ? { Accept: 'application/json', 'X-Frontend-Key': key } : { Accept: 'application/json' }
}

export function apiUrl(path) {
  return `${API_BASE}/api/v1/${path.replace(/^\/+/, '')}`
}

/**
 * Server-side read. Returns the `data` payload, or `null` when the backend is
 * unreachable or errors, so a page can fall back to its built-in content instead of
 * failing the render or the build.
 */
export async function apiGet(path, { revalidate = DEFAULT_REVALIDATE_SECONDS } = {}) {
  try {
    const response = await fetch(apiUrl(path), {
      headers: serverHeaders(),
      next: { revalidate },
    })

    if (!response.ok) return null

    const body = await response.json()
    return body?.data ?? null
  } catch {
    return null
  }
}

/**
 * Browser-side submit. Always resolves to `{ ok, status, data, message, errors }` so
 * callers never have to catch.
 */
export async function apiPost(path, payload) {
  let response

  try {
    response = await fetch(apiUrl(path), {
      method: 'POST',
      headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
  } catch {
    return {
      ok: false,
      status: 0,
      data: null,
      message: 'We could not reach our servers. Please check your connection or call us instead.',
      errors: {},
    }
  }

  let data = null

  try {
    data = await response.json()
  } catch {}

  if (response.ok) {
    return { ok: true, status: response.status, data, message: data?.message ?? null, errors: {} }
  }

  return {
    ok: false,
    status: response.status,
    data,
    message: errorMessage(response.status, data),
    errors: data?.errors ?? {},
  }
}

function errorMessage(status, data) {
  if (status === 429) return 'Too many attempts. Please wait a minute and try again.'
  if (status === 413) return 'Your submission is too large. Please shorten it and try again.'

  // Laravel's 422 carries the first validation message in `message`; prefer the
  // specific field message when there is one.
  const firstFieldError = data?.errors ? Object.values(data.errors).flat()[0] : null

  return firstFieldError || data?.message || 'Something went wrong. Please try again or call us.'
}

/**
 * "3 months ago" style label for API timestamps.
 */
export function timeAgo(isoDate) {
  if (!isoDate) return ''

  const seconds = Math.max(0, Math.round((Date.now() - new Date(isoDate).getTime()) / 1000))
  const units = [
    ['year', 31536000],
    ['month', 2592000],
    ['week', 604800],
    ['day', 86400],
    ['hour', 3600],
    ['minute', 60],
  ]

  for (const [unit, size] of units) {
    const value = Math.floor(seconds / size)
    if (value >= 1) return `${value} ${unit}${value === 1 ? '' : 's'} ago`
  }

  return 'Just now'
}
