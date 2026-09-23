import Link from 'next/link'
import { ArrowLeft, ShieldCheck } from 'lucide-react'
import ManVanBooking from '@/components/home/ManVanBooking'
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

export default async function PricesPage({ searchParams }) {
  const [query, catalogue] = await Promise.all([searchParams, loadCatalogue()])

  const labels = catalogue
    ? {
        'man-van': SERVICE_LABELS['man-van'],
        ...Object.fromEntries(catalogue.categories.map((category) => [category.id, category.label])),
        'show-all': SERVICE_LABELS['show-all'],
      }
    : SERVICE_LABELS

  const requestedService = Array.isArray(query.service) ? query.service[0] : query.service
  const service = requestedService && Object.hasOwn(labels, requestedService)
    ? requestedService
    : 'man-van'
  const isManVan = service === 'man-van'

  return (
    <main className='min-h-screen bg-linear-to-b from-[#E9F4FC] via-white to-[#F5FAFE]'>
      <section className='container mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8 lg:py-24'>
        <Link
          href='/#prices'
          className='group inline-flex items-center gap-2 rounded-full border border-[#11224D]/10 bg-white px-4 py-2 text-sm font-bold text-[#11224D] shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-[#0497E2]/30 hover:text-[#0497E2] hover:shadow-md focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0497E2]'
        >
          <ArrowLeft aria-hidden='true' className='size-4 transition-transform duration-300 group-hover:-translate-x-1' />
          Back to services
        </Link>

        <div className='mx-auto mt-7 max-w-4xl text-center sm:mt-9'>
          <div className='inline-flex items-center gap-2 rounded-full border border-[#11224D]/10 bg-white/80 px-3 py-1.5 text-sm font-semibold text-[#11224D] shadow-sm'>
            <ShieldCheck aria-hidden='true' className='size-4 text-[#0497E2]' />
            Clear pricing and online booking
          </div>
          <h1 className='mt-4 text-3xl font-black leading-tight text-[#11224D] sm:text-4xl lg:text-4xl'>
            {labels[service]}
          </h1>
          <p className='mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-slate-600 sm:text-base'>
            {isManVan
              ? 'Choose the load size that suits your clearance, review the price and add it to your basket.'
              : 'Choose the items you need collected, adjust the quantities and review your running total.'}
          </p>
        </div>

        <div className='mt-8 sm:mt-10'>
          <ManVanBooking
            key={service}
            defaultMode={isManVan ? 'lorry' : 'individual'}
            initialCategoryId={isManVan ? null : service}
            catalogue={catalogue}
          />
        </div>
      </section>
    </main>
  )
}
