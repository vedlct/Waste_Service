import { Suspense } from 'react'
import PricesContent from './PricesContent'
import { apiGet } from '@/lib/api'
import { metadataFor } from '@/lib/seo'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/prices', { title: 'Prices & Booking | MR. TEE Removals' })
}

// Fallback labels, used when the backend API is unreachable.
const SERVICE_LABELS = {
  'man-van': 'Man & Van Collection',
  sofas: 'Sofas',
  'mattress-bed': 'Mattress & Bed',
  furniture: 'Furniture',
  'kitchen-appliances': 'Kitchen Appliances',
  'fridge-freezer': 'Fridge & Freezer',
  'electrical-it': 'Electrical & IT',
  'garden-items': 'Garden Items',
  'hazardous-waste': 'Hazardous Waste',
  'office-items': 'Office Items',
  'commercial-items': 'Commercial Items',
  'bins-wheelie-bins': 'Bins',
  'show-all': 'All Waste Types',
}

const toPounds = (money) => (money?.pence ?? 0) / 100

/**
 * Loads the bookable catalogue from the API and reshapes it into what the booking
 * component already renders. Returns null when the API is unreachable, so the component
 * falls back to its built-in catalogue.
 */
async function loadCatalogue() {
  const [categories, loadPackages, extraCharges] = await Promise.all([
    apiGet('price-categories?with_items=1'),
    apiGet('load-packages'),
    apiGet('extra-charges'),
  ])

  if (!categories?.length) return null

  const itemsByCategory = Object.fromEntries(categories.map((category) => {
    const categoryImage = category.image?.url ?? null

    return [category.slug, (category.items ?? []).map((item) => ({
      id: item.slug,
      catalogueId: item.id,
      name: item.name,
      description: item.description,
      price: toPounds(item.price),
      requiresQuote: item.requires_quote,
      // Seeded items all reuse their category's image, so only an image set specifically
      // on the item counts as its own; the component falls back from there.
      img: item.image?.url && item.image.url !== categoryImage ? item.image.url : null,
      categoryImg: categoryImage,
    }))]
  }))

  const saturday = extraCharges?.find((charge) => charge.slug === 'saturday-collection')

  return {
    categories: categories.map((category) => ({ id: category.slug, label: category.name })),
    itemsByCategory,
    loadSizes: (loadPackages ?? []).map((pkg) => ({
      id: pkg.slug,
      catalogueId: pkg.id,
      name: pkg.name,
      priceIncVat: toPounds(pkg.price),
      priceExVat: toPounds(pkg.price_ex_vat),
      maxWeight: pkg.capacity?.max_weight_kg ? `${pkg.capacity.max_weight_kg}KG` : 'N/A',
      volume: pkg.capacity?.volume_cubic_yards ? `${Number(pkg.capacity.volume_cubic_yards)} yds³` : 'N/A',
      sacks: pkg.capacity?.sack_equivalent ?? 0,
      time: pkg.capacity?.loading_time_minutes ? `${pkg.capacity.loading_time_minutes}mins` : 'N/A',
      popular: pkg.is_popular,
    })),
    saturdaySurcharge: saturday?.amount ? toPounds(saturday.amount) : undefined,
  }
}

export default async function PricesPage() {
  const catalogue = await loadCatalogue()
  const labels = catalogue
    ? {
        'man-van': SERVICE_LABELS['man-van'],
        ...Object.fromEntries(catalogue.categories.map((category) => [category.id, category.label])),
        'show-all': SERVICE_LABELS['show-all'],
      }
    : SERVICE_LABELS

  return (
    <Suspense fallback={<div className='min-h-screen bg-mist' />}>
      <PricesContent labels={labels} catalogue={catalogue} />
    </Suspense>
  )
}
