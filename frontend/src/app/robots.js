import { SITE_URL } from '@/lib/seo'

export default function robots() {
  return {
    rules: {
      userAgent: '*',
      allow: '/',
      // The basket and checkout hold no content worth indexing.
      disallow: ['/checkout', '/payment'],
    },
    sitemap: `${SITE_URL}/sitemap.xml`,
  }
}
