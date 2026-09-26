import Image from 'next/image'
import Link from 'next/link'
import { ArrowRight, BadgePoundSterling, Clock3, House, ShieldCheck } from 'lucide-react'
import SectionHeader from '@/components/ui/SectionHeader'

const reasons = [
  { icon: House, title: 'Any size of job', text: 'From one bulky item to a full house, flat or office.' },
  { icon: Clock3, title: 'Same-day clearance', text: 'Often available across Portsmouth and nearby.' },
  { icon: ShieldCheck, title: 'Reliable local team', text: 'Fully insured, and we turn up when we say.' },
  { icon: BadgePoundSterling, title: 'Great value', text: 'Clear prices, shown before you book.' },
]

export default function WhyChoose() {
  return (
    <section className='bg-mist py-20 sm:py-28'>
      <div className='mx-auto grid max-w-[90rem] items-center gap-16 px-4 sm:px-6 lg:grid-cols-2 lg:gap-14 lg:px-8 xl:grid-cols-[1.15fr_0.85fr] xl:gap-16'>
        <div className='relative mx-auto w-full max-w-lg lg:max-w-none'>
          <div className='overflow-hidden rounded-2xl border border-line bg-white p-2 shadow-[0_30px_60px_-30px_rgba(15,37,87,0.45)] sm:p-3'>
            <Image
              src='/images/mr-tee-team-loading.png'
              alt='The MR. TEE Removals team loading a sofa into the branded van in Portsmouth'
              width={1792}
              height={896}
              sizes='(min-width: 1024px) 44vw, 100vw'
              className='h-auto w-full rounded-xl'
            />
            <div className='grid grid-cols-2 divide-x divide-line px-2 py-4 sm:px-4'>
              <p className='pr-4 text-center font-display text-lg font-bold uppercase leading-tight text-ink sm:text-xl'>We do the lifting</p>
              <p className='pl-4 text-center font-display text-lg font-bold uppercase leading-tight text-brand-700 sm:text-xl'>Swept &amp; left tidy</p>
            </div>
          </div>
          <p className='tag-slant absolute left-5 top-5 sm:left-7 sm:top-7'>Local to Portsmouth</p>
        </div>

        <div>
          <SectionHeader
            eyebrow='Why MR. TEE'
            title='Make your space clean,'
            accent='clear and ready again'
            intro='Rubbish piling up at home, in the office, the garden or on a property site? We clear it quickly and without the stress, then leave the space swept and tidy.'
          />

          <ul className='mt-10 grid gap-4 sm:grid-cols-2'>
            {reasons.map(({ icon: Icon, title, text }) => (
              <li key={title} className='group rounded-2xl border border-line bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-[0_20px_40px_-24px_rgba(31,95,217,0.5)]'>
                <span className='grid size-11 place-items-center rounded-xl bg-brand-50 text-brand-600 transition-colors group-hover:bg-brand-600 group-hover:text-white'>
                  <Icon className='size-5' aria-hidden='true' />
                </span>
                <h3 className='mt-4 font-display text-xl font-bold uppercase text-ink'>{title}</h3>
                <p className='mt-1 text-sm leading-6 text-slate-600'>{text}</p>
              </li>
            ))}
          </ul>

          <Link href='/prices' className='group mt-10 inline-flex items-center gap-2 rounded-lg bg-brand-600 px-7 py-4 font-bold text-white shadow-lg shadow-brand-600/25 transition-colors hover:bg-brand-700'>
            Book your clearance
            <ArrowRight className='size-4 transition-transform group-hover:translate-x-1' aria-hidden='true' />
          </Link>
        </div>
      </div>
    </section>
  )
}
