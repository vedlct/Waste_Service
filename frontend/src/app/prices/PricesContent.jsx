'use client'

import { useSearchParams } from 'next/navigation'
import { ShieldCheck } from 'lucide-react'
import PageBanner from '@/components/ui/PageBanner'
import ManVanBooking from '@/components/home/ManVanBooking'

export default function PricesContent({ labels, catalogue }) {
  const searchParams = useSearchParams()
  const requestedService = searchParams.get('service')
  const service = requestedService && Object.hasOwn(labels, requestedService)
    ? requestedService
    : 'man-van'
  const isManVan = service === 'man-van'

  return (
    <main className='min-h-screen bg-mist'>
      <PageBanner
        crumb='Prices'
        eyebrow='Prices & booking'
        title={labels[service]}
        intro={isManVan
          ? 'Choose the load size that suits your clearance, see the price and add it to your basket.'
          : 'Choose the items you need collected, adjust the quantities and watch your running total.'}
        image='/images/rubbish2.jpg'
      >
        <p className='inline-flex items-center gap-2 text-sm font-semibold text-slate-700'>
          <ShieldCheck aria-hidden='true' className='size-4 text-brand-600' />
          Clear pricing, fully insured team, book online in minutes
        </p>
      </PageBanner>

      <section className='container mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8'>
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
