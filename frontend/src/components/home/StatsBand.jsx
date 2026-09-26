import { CalendarDays, Clock3, MapPin, Recycle } from 'lucide-react'

// One bold blue band in an otherwise white page, with the numbers customers ask about.
export default function StatsBand({ coverage }) {
  const areaCount = (coverage ?? []).reduce((total, region) => total + (region.areas?.length ?? 0), 0)

  const stats = [
    { icon: Recycle, value: '95%', label: 'of waste recycled or reused' },
    { icon: Clock3, value: 'Same day', label: 'clearance often available' },
    { icon: CalendarDays, value: 'Mon–Sat', label: 'collections, 7am to 7pm' },
    { icon: MapPin, value: areaCount > 0 ? `${areaCount} areas` : 'Portsmouth', label: 'covered across the city and beyond' },
  ]

  return (
    <section className='px-4 py-6 sm:px-6 lg:px-8'>
      <div className='mx-auto max-w-7xl rounded-2xl bg-brand-700 px-6 py-12 text-white sm:px-10 lg:py-14'>

        <dl className='grid grid-cols-2 gap-x-6 gap-y-10 lg:grid-cols-4'>
          {stats.map(({ icon: Icon, value, label }) => (
            <div key={label} className='flex flex-col items-start gap-3 lg:border-l lg:border-white/20 lg:pl-8 lg:first:border-l-0 lg:first:pl-0'>
              <span className='grid size-12 place-items-center rounded-xl bg-white/15 ring-1 ring-white/25'>
                <Icon className='size-6' aria-hidden='true' />
              </span>
              <dt className='sr-only'>{label}</dt>
              <dd className='font-display text-4xl font-extrabold uppercase leading-none sm:text-5xl'>{value}</dd>
              <dd className='-mt-1 text-sm text-white/80'>{label}</dd>
            </div>
          ))}
        </dl>
      </div>
    </section>
  )
}
