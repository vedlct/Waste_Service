import Image from 'next/image'
import Link from 'next/link'
import { ArrowRight, CalendarCheck2, Check, Recycle, Truck } from 'lucide-react'

const steps = [
  {
    number: '01',
    title: 'Book online or call',
    text: 'Tell us what needs to go and choose a collection date. You will see the price before you book, or our local team can talk it through with you.',
    image: '/images/how-it-works-booking.png',
    alt: 'A customer confirming a clearance booking with an MR. TEE team member',
    icon: CalendarCheck2,
  },
  {
    number: '02',
    title: 'We arrive and load',
    text: 'Our uniformed team arrives in the agreed window, does all the lifting and carrying, and loads everything safely into the van.',
    image: '/images/how-it-works-loading.png',
    alt: 'The MR. TEE Removals team loading a chest of drawers into their branded van',
    icon: Truck,
  },
  {
    number: '03',
    title: 'Cleared and recycled',
    text: 'We sweep up before we leave and take the waste to licensed facilities, where reusable items and recyclable materials are separated responsibly.',
    image: '/images/mr-tee-recycling-step.png',
    alt: 'The MR. TEE Removals team sorting cardboard and reusable furniture at a recycling facility',
    icon: Recycle,
  },
]

const promises = ['See the price before you book', 'Fully insured, uniformed team', 'Licensed, responsible disposal']

// Heading on the left, kept in view on large screens, with the steps as a numbered timeline
// on the right.
export default function HowItWorks() {
  return (
    <section id='how-it-works' className='bg-white py-20 sm:py-28'>
      <div className='mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:gap-16 lg:px-8'>
        <header className='lg:sticky lg:top-32 lg:self-start'>
          <p className='text-xs font-extrabold uppercase tracking-[0.22em] text-brand-600'>How it works</p>
          <h2 className='mt-4 font-display text-4xl font-extrabold uppercase leading-[0.92] text-ink sm:text-5xl lg:text-6xl'>
            <span className='block'>Cleared in</span>
            <span className='mt-1 block text-brand-600'>three simple steps</span>
          </h2>
          <p className='mt-6 max-w-md text-base leading-7 text-slate-600 sm:text-lg'>
            From your first message to the final sweep-up, our local team keeps the whole clearance simple.
          </p>

          <ul className='mt-8 space-y-3 border-t border-line pt-8'>
            {promises.map((promise) => (
              <li key={promise} className='flex items-center gap-3 font-semibold text-ink'>
                <span className='grid size-6 shrink-0 place-items-center rounded-full bg-brand-50 text-brand-600'>
                  <Check className='size-3.5' strokeWidth={3} aria-hidden='true' />
                </span>
                {promise}
              </li>
            ))}
          </ul>

          <Link href='/prices' className='group mt-9 inline-flex items-center gap-2 rounded-lg bg-brand-600 px-6 py-3.5 font-bold text-white shadow-lg shadow-brand-600/20 transition-colors hover:bg-brand-700'>
            Start your booking
            <ArrowRight className='size-5 transition-transform group-hover:translate-x-1' aria-hidden='true' />
          </Link>
        </header>

        <ol className='relative space-y-8'>
          <span aria-hidden='true' className='absolute bottom-8 left-6 top-8 w-0.5 bg-brand-100 sm:left-7' />

          {steps.map((step) => {
            const Icon = step.icon

            return (
              <li key={step.title} className='relative flex gap-4 sm:gap-6'>
                <span className='relative z-10 grid size-12 shrink-0 place-items-center rounded-full bg-brand-600 font-display text-xl font-extrabold text-white ring-8 ring-white sm:size-14 sm:text-2xl'>
                  {step.number}
                </span>

                <article className='group flex-1 overflow-hidden rounded-2xl border border-line bg-white shadow-[0_18px_45px_-34px_rgba(15,37,87,0.45)] transition-shadow hover:shadow-[0_24px_55px_-30px_rgba(15,37,87,0.5)] sm:flex'>
                  <div className='relative aspect-[3/2] overflow-hidden bg-slate-100 sm:aspect-auto sm:w-56 sm:shrink-0 lg:w-64'>
                    <Image
                      src={step.image}
                      alt={step.alt}
                      fill
                      sizes='(min-width: 1024px) 256px, (min-width: 640px) 224px, 100vw'
                      className='object-cover transition-transform duration-700 group-hover:scale-[1.03]'
                    />
                  </div>

                  <div className='p-6 sm:p-7'>
                    <p className='flex items-center gap-2 text-xs font-extrabold uppercase tracking-[0.18em] text-brand-600'>
                      <Icon className='size-4' aria-hidden='true' />
                      Step {step.number}
                    </p>
                    <h3 className='mt-3 font-display text-2xl font-extrabold uppercase leading-none text-ink sm:text-3xl'>
                      {step.title}
                    </h3>
                    <p className='mt-3 leading-7 text-slate-600'>{step.text}</p>
                  </div>
                </article>
              </li>
            )
          })}
        </ol>
      </div>
    </section>
  )
}
