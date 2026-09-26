import Image from 'next/image'
import Link from 'next/link'
import { ArrowRight, CalendarCheck2, CalendarClock, Recycle, Sofa } from 'lucide-react'

const benefits = [
  {
    title: 'Flexible Booking',
    icon: CalendarCheck2,
    summary: 'Your collection can adapt when the job changes.',
    detail: "If you decide to add more items to your load, simply let our office or collection team know. Our vehicles carry card machines, making additional payments straightforward on the day.",
    note: 'Modern onboard weighing means larger collections are charged for the waste we actually remove.',
  },
  {
    title: '95% Recycled',
    icon: Recycle,
    summary: 'Responsible disposal is built into every collection.',
    detail: 'As an approved waste carrier, we dispose of collected items ethically wherever possible and work to reduce the amount of rubbish sent to landfill.',
    note: 'More than 95% of collected waste is recycled wherever facilities allow.',
  },
  {
    title: 'Next Day Collection',
    icon: CalendarClock,
    summary: 'Choose a collection time that works around you.',
    detail: 'Our online booking process makes it easy to select guaranteed next-day collection or arrange another available date that better suits your schedule.',
    note: 'Flexible dates and clear booking options help keep urgent clearances moving.',
  },
  {
    title: 'Sit & Relax',
    icon: Sofa,
    summary: 'We take care of the lifting, loading and final tidy.',
    detail: 'There is no need to move heavy or awkward waste yourself. Our experienced collection team handles the carrying and loads everything safely into the vehicle.',
    note: 'When needed, the team will tidy or sweep the cleared area before leaving.',
  },
]

export default function Benefits() {
  return (
    <section className='relative isolate min-h-[52rem] overflow-hidden bg-brand-600 py-16 text-white sm:py-20 lg:flex lg:min-h-[50rem] lg:items-center lg:py-24'>
      <Image
        src='/images/Place.webp'
        alt='A neighbourhood served by Waste Services'
        fill
        sizes='100vw'
        className='-z-20 object-cover object-center'
      />
      <div className='absolute inset-0 -z-10 bg-brand-600/72' />
      <div className='absolute inset-0 -z-10 bg-linear-to-r from-brand-600/50 via-brand-600/90 to-brand-600/10 lg:via-brand-600/18 lg:to-brand-600/10' />

      <div className='mx-auto grid w-full max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-[0.78fr_1.22fr] lg:items-center lg:gap-16 lg:px-8 xl:gap-24'>
        <div>
          <span className='inline-flex items-center gap-2 border-l-8 border-brand-500 pl-3 text-xs font-black uppercase tracking-[0.22em] text-brand-500'>
            Why customers choose us
          </span>

          <h2 className='mt-7 max-w-xl text-4xl font-bold leading-[0.95] sm:text-5xl lg:text-5xl xl:text-5xl uppercase'>
            Clearing waste
            <span className='block text-brand-300'>should feel simple.</span>
          </h2>

          <p className='mt-6 max-w-xl text-base leading-7 text-ink/70 sm:text-lg'>
            From the first booking to the final sweep, our service is designed to remove uncertainty as well as rubbish. You choose what needs to go; our team takes care of the difficult part.
          </p>

          <div className='mt-8 grid max-w-lg grid-cols-2 gap-3'>
            <div className='rounded-2xl border border-white/15 bg-white/[0.07] p-4 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:bg-white/[0.12]'>
              <p className='text-3xl font-black text-brand-500'>95%</p>
              <p className='mt-1 text-sm text-ink/65'>of waste recycled</p>
            </div>
            <div className='rounded-2xl border border-white/15 bg-white/[0.07] p-4 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:bg-white/[0.12]'>
              <p className='text-3xl font-black text-brand-300'>6 days</p>
              <p className='mt-1 text-sm text-ink/65'>a week collecting</p>
            </div>
          </div>

          <Link href='/prices?service=man-van' className='group mt-8 inline-flex items-center gap-3 rounded-full bg-white px-5 py-3.5 text-sm font-black text-ink shadow-xl transition-all duration-300 hover:-translate-y-1 hover:bg-brand-500 hover:shadow-2xl focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white'>
            Book your collection
            <ArrowRight aria-hidden='true' className='size-4 transition-transform duration-300 group-hover:translate-x-1' />
          </Link>
        </div>

        <div className='divide-y divide-white/15 border-y border-white/15'>
          {benefits.map((benefit, index) => {
            const Icon = benefit.icon

            return (
              <article key={benefit.title} className='group relative grid gap-4 py-6 transition-all duration-500 hover:bg-white/[0.07] sm:grid-cols-[3.5rem_1fr] sm:gap-5 sm:px-4 lg:grid-cols-[4rem_0.85fr_1.15fr] lg:items-start lg:gap-6 lg:py-7'>
                <div className='flex items-center gap-3 sm:block'>
                  <span className='flex size-11 shrink-0 items-center justify-center rounded-full bg-brand-500 text-sm font-black text-white shadow-lg transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 group-hover:bg-brand-300 sm:size-12'>
                    {String(index + 1).padStart(2, '0')}
                  </span>
                  <span className='flex size-9 items-center justify-center rounded-xl bg-white/10 text-brand-300 transition-all duration-300 group-hover:bg-white group-hover:text-ink sm:mt-3'>
                    <Icon aria-hidden='true' className='size-4' />
                  </span>
                </div>

                <div>
                  <h3 className='text-xl font-black text-ink transition-colors duration-300 group-hover:text-brand-500 sm:text-2xl'>{benefit.title}</h3>
                  <p className='mt-2 text-sm font-semibold leading-6 text-ink/80'>{benefit.summary}</p>
                </div>

                <div>
                  <p className='text-sm leading-6 text-ink/65 sm:text-base'>{benefit.detail}</p>
                  <p className='mt-3 border-l-2 border-brand-300 pl-3 text-xs font-semibold leading-5 text-ink/80 sm:text-sm'>{benefit.note}</p>
                </div>

                <span className='absolute inset-y-4 left-0 w-1 origin-bottom scale-y-0 rounded-full bg-linear-to-b from-brand-500 to-brand-600 transition-transform duration-500 group-hover:scale-y-100' />
              </article>
            )
          })}
        </div>
      </div>
    </section>
  )
}
