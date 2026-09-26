'use client'

const BASE_PATH = (process.env.NEXT_PUBLIC_BASE_PATH || '').replace(/\/+$/, '')

export default function imageLoader({ src, width, quality }) {
  if (/^(?:https?:)?\/\//.test(src) || src.startsWith('data:') || src.startsWith('blob:')) {
    return src
  }

  const path = src.startsWith('/') ? src : `/${src}`
  const prefixedPath = BASE_PATH && !path.startsWith(`${BASE_PATH}/`)
    ? `${BASE_PATH}${path}`
    : path

  return `${prefixedPath}?w=${width}&q=${quality || 75}`
}
