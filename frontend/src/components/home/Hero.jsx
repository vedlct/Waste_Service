import { Clock3, House, Phone, ShieldCheck, Star } from 'lucide-react'
import QuickQuote from './QuickQuote'
import PhotoHero from '@/components/ui/PhotoHero'
import { FALLBACK_CONTACT, telHref } from '@/lib/site'

const highlights = [
  { icon: House, label: 'Household waste' },
  { icon: Clock3, label: 'Same-day clearance' },
  { icon: ShieldCheck, label: 'Fully insured' },
]

// Full-width home hero: a clear, untinted photo of the branded van, with the text and the
// instant price box on a white card.
export default function Hero({ phone = FALLBACK_CONTACT.phone, coverage }) {
  return (
    <PhotoHero
      image='/images/mr-tee-van-hero-v2.png'
      alt='The white and blue MR. TEE Removals van on a Portsmouth street'
      imagePosition='object-center lg:object-right'
      imageFrameClassName='aspect-[2/1] lg:aspect-auto'
      cardClassName='xl:-ml-12'
      mobileOverlap={false}
      minHeight='lg:min-h-[44rem]'
    >
      <p className='flex items-center gap-2 text-sm font-semibold text-slate-600'>
        <span className='flex gap-0.5 text-amber-400' aria-hidden='true'>
          {Array.from({ length: 5 }).map((_, index) => <Star key={index} className='size-4 fill-current' />)}
        </span>
        Reliable &amp; affordable service
      </p>

      <h1 className='mt-4 break-words text-[2.15rem] font-extrabold uppercase leading-[0.92] text-ink min-[420px]:text-5xl sm:text-6xl'>
        Rubbish clearance
        <span className='block text-brand-600'>in Portsmouth</span>
      </h1>

      <p className='mt-5 text-base leading-7 text-slate-600 sm:text-lg'>
        Fast, affordable clearance for homes, gardens, offices and property sites. We do the lifting and
        loading, take it away and recycle as much as we can.
      </p>

      <ul className='mt-5 flex flex-wrap gap-x-5 gap-y-2'>
        {highlights.map(({ icon: Icon, label }) => (
          <li key={label} className='flex items-center gap-2 text-sm font-semibold text-ink'>
            <Icon className='size-5 text-brand-600' aria-hidden='true' />
            {label}
          </li>
        ))}
      </ul>

      <div className='mt-7 border-t border-line pt-6'>
        <QuickQuote coverage={coverage} phone={phone} bare />
        <p className='mt-3 text-sm text-slate-600'>
          Prefer to talk?{' '}
          <a href={telHref(phone)} className='inline-flex items-center gap-1.5 font-bold text-brand-700 underline-offset-4 hover:underline'>
            <Phone className='size-4' aria-hidden='true' />
            Call {phone}
          </a>
        </p>
      </div>
    </PhotoHero>
  )
}
