import Link from 'next/link'
import { ArrowRight, MapPin } from 'lucide-react'
import SectionHeader from '@/components/ui/SectionHeader'

// Shown when the coverage list cannot be loaded.
const FALLBACK_AREAS = ['Southsea', 'Fratton', 'North End', 'Cosham', 'Havant', 'Waterlooville', 'Fareham', 'Gosport', 'Emsworth', 'Hayling Island']

export default function LocalAreas({ coverage }) {
  const areas = (coverage ?? []).flatMap((region) => (region.areas ?? []).map((area) => area.name))
  const shown = (areas.length > 0 ? areas : FALLBACK_AREAS).slice(0, 18)

  return (
    <section className='bg-brand-50 py-20 sm:py-24'>
      <div className='mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-[1fr_1.2fr] lg:items-center lg:px-8'>
        <div>
          <SectionHeader
            eyebrow='Areas we cover'
            title='Your local'
            accent='Portsmouth team'
            intro='We are based in Portsmouth and collect across the city and the surrounding towns, from Southsea to Fareham, Gosport and Havant.'
          />
          <Link href='/area' className='group mt-8 inline-flex items-center gap-2 rounded-lg bg-brand-600 px-6 py-3.5 font-bold text-white transition-colors hover:bg-brand-700'>
            See every area
            <ArrowRight className='size-4 transition-transform group-hover:translate-x-1' aria-hidden='true' />
          </Link>
        </div>

        <ul className='flex flex-wrap gap-2.5'>
          {shown.map((area) => (
            <li key={area} className='inline-flex items-center gap-2 rounded-lg border border-brand-100 bg-white px-4 py-2.5 text-sm font-semibold text-ink shadow-sm'>
              <MapPin className='size-4 text-brand-600' aria-hidden='true' />
              {area}
            </li>
          ))}
        </ul>
      </div>
    </section>
  )
}
