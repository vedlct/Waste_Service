import Link from 'next/link'
import { ArrowUpRight, CalendarCheck2, Recycle, ShieldCheck, Sparkles } from 'lucide-react'

const promises = [
  {
    icon: CalendarCheck2,
    title: 'Flexible Booking Options',
    description: 'Choose next-day collection or select another available date that works around your schedule.',
    href: '/#prices',
    accent: 'bg-brand-600',
  },
  {
    icon: Recycle,
    title: '95% of All Waste Recycled',
    description: 'We prioritise responsible disposal and divert collected waste from landfill wherever possible.',
    href: '/#prices',
    accent: 'bg-[#38A169]',
  },
  {
    icon: ShieldCheck,
    title: 'Guaranteed Next Day Collection',
    description: 'Book an eligible slot online and our experienced team will arrive ready to clear your waste.',
    href: '/#prices',
    accent: 'bg-brand-500',
  },
  {
    icon: Sparkles,
    title: "Sit Back & Relax, We'll Do the Rest",
    description: 'From lifting and loading to a final tidy, our collection team handles the difficult work for you.',
    href: '/#prices',
    accent: 'bg-brand-400',
  },
]

export default function SecondaryNav() {
  return (
    <section className='relative overflow-hidden bg-white py-14 sm:py-16 lg:py-20'>

      <div className='relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8'>
        <div className='grid gap-5 lg:grid-cols-[0.7fr_1.3fr] lg:items-end lg:gap-12'>
          <div>
            <span className='text-xs font-black uppercase tracking-[0.22em] text-brand-600'>Why Waste Services</span>
            <h2 className='mt-3 text-4xl font-bold leading-[0.95] text-ink sm:text-5xl uppercase'>Clear promises.<br />Reliable collections.</h2>
          </div>
          <p className='max-w-3xl text-base leading-7 text-slate-600 sm:text-lg'>
            Every booking is supported by practical collection choices, responsible recycling and an experienced team that takes care of the work from start to finish.
          </p>
        </div>

        <div className='mt-9 grid grid-cols-1 gap-4 sm:mt-11 sm:grid-cols-2 lg:grid-cols-4 lg:gap-5'>
          {promises.map((promise, index) => {
            const Icon = promise.icon

            return (
              <Link
                key={promise.title}
                href={promise.href}
                className='group relative flex min-h-72 min-w-0 flex-col overflow-hidden rounded-[1.75rem] border border-brand-600/10 bg-mist p-5 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-brand-600/30 hover:bg-white hover:shadow-2xl hover:shadow-brand-600/10 focus-visible:-translate-y-1 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-600 sm:p-6'
              >
                <div className='flex items-start justify-between gap-4'>
                  <span className={`flex size-13 shrink-0 items-center justify-center rounded-2xl text-ink shadow-lg transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 ${promise.accent}`}>
                    <Icon aria-hidden='true' className='size-6' strokeWidth={1.9} />
                  </span>
                  <span className='text-3xl font-black tracking-[-0.06em] text-ink/8 transition-colors duration-300 group-hover:text-brand-600/15'>
                    {String(index + 1).padStart(2, '0')}
                  </span>
                </div>

                <div className='mt-10 flex flex-1 flex-col'>
                  <h3 className='text-xl font-black leading-tight text-ink transition-colors duration-300 group-hover:text-brand-600 sm:text-2xl'>
                    {promise.title}
                  </h3>
                  <p className='mt-3 flex-1 text-sm leading-6 text-slate-600 sm:text-base'>
                    {promise.description}
                  </p>

                  <div className='mt-6 flex items-center justify-between border-t border-brand-600/10 pt-4'>
                    <span className='text-xs font-black uppercase tracking-[0.16em] text-ink/55 transition-colors duration-300 group-hover:text-brand-600'>Learn more</span>
                    <span className='flex size-9 shrink-0 items-center justify-center rounded-full bg-brand-600 text-white transition-all duration-300 group-hover:rotate-45 group-hover:bg-brand-600'>
                      <ArrowUpRight aria-hidden='true' className='size-5' />
                    </span>
                  </div>
                </div>

                <span className={`absolute inset-x-6 bottom-0 h-1 origin-left scale-x-0 rounded-full transition-transform duration-500 group-hover:scale-x-100 ${promise.accent}`} />
              </Link>
            )
          })}
        </div>
      </div>
    </section>
  )
}
