import Link from 'next/link'
import { ArrowLeft, ShieldCheck } from 'lucide-react'
import ManVanBooking from '@/components/home/ManVanBooking'

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

export const metadata = {
  title: 'Prices & Booking | Waste Services',
  description: 'Choose your waste collection service, view prices and build your collection online.',
}

export default async function PricesPage({ searchParams }) {
  const query = await searchParams
  const requestedService = Array.isArray(query.service) ? query.service[0] : query.service
  const service = requestedService && Object.hasOwn(SERVICE_LABELS, requestedService)
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
            {SERVICE_LABELS[service]}
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
          />
        </div>
      </section>
    </main>
  )
}
