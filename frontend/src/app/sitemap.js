import { SITE_URL, getPublishedPages } from '@/lib/seo'

// Used only when the backend is unreachable, so the sitemap is never empty.
const FALLBACK_ROUTES = [
  '/', '/prices', '/faq', '/contactUs', '/area',
  '/houseClearance', '/gardenClearance', '/flatClearance', '/garageClearance', '/furnitureClearance',
  '/buildersWasteRemoval', '/junkCollection', '/waitLoad', '/officeWasteClearance', '/flyTippingClearance',
  '/warehouseClearance', '/hotelPubClearance', '/restaurantClearance', '/shopStripOutClearance',
  '/windowCleaning', '/communalAreaCleaning', '/groundMaintainance', '/lawnMowing', '/hedgeCutting',
]

// Rebuilt at most every 5 minutes, in step with the page data.
export const revalidate = 300

export default async function sitemap() {
  const pages = await getPublishedPages()

  // Checkout and payment are published but marked not indexable in the admin.
  const routes = pages.length > 0
    ? pages.filter((page) => page.seo?.is_indexable).map((page) => ({ route: page.route_path, updated: page.published_at }))
    : FALLBACK_ROUTES.map((route) => ({ route, updated: null }))

  return routes.map(({ route, updated }) => ({
    url: `${SITE_URL}${route === '/' ? '' : route}`,
    lastModified: updated ? new Date(updated) : undefined,
    changeFrequency: 'weekly',
    priority: route === '/' ? 1 : 0.7,
  }))
}
