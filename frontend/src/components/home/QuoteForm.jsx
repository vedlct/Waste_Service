import Image from 'next/image'
import Link from 'next/link'
import { ArrowRight, Camera, Clock3, MessageCircle, Phone, ShieldCheck } from 'lucide-react'
import { FALLBACK_CONTACT, telHref } from '@/lib/site'

const steps = [
  { icon: MessageCircle, title: 'Tell us what needs clearing', text: 'Share the type of waste, your location and the collection date you have in mind.' },
  { icon: Camera, title: 'Add photos if helpful', text: 'Pictures help our team understand the volume and give clearer guidance before collection.' },
  { icon: Clock3, title: 'Get your next step', text: 'We review the details and explain the most practical collection option for your job.' },
]

export default function QuoteForm() {
  const phones = [FALLBACK_CONTACT.phone, FALLBACK_CONTACT.phoneSecondary]

  return (
    <section id='form' className='bg-white py-20 sm:py-24'>
      <div className='mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[1.1fr_1fr] lg:px-8'>
        <div className='relative isolate flex flex-col justify-end overflow-hidden rounded-3xl p-8 text-white sm:p-10 lg:min-h-[32rem]'>
          <Image src='/images/highlight2.jpeg' alt='' fill sizes='(min-width: 1024px) 50vw, 100vw' className='-z-20 object-cover' />
          <span className='absolute inset-0 -z-10 bg-linear-to-t from-brand-950 via-brand-900/80 to-brand-900/30' aria-hidden='true' />

          <p className='tag-slant self-start'>Free advice</p>
          <h2 className='mt-5 text-4xl font-bold uppercase leading-[0.95] sm:text-5xl'>
            Not sure what
            <span className='block text-brand-400'>you need?</span>
          </h2>
          <p className='mt-4 max-w-md text-white/80'>
            Tell our team about your clearance and we&apos;ll explain the options, the likely collection size and what happens next.
          </p>

          <div className='mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap'>
            <Link href='/contactUs' className='group inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-6 py-3.5 font-bold text-white transition-colors hover:bg-brand-500'>
              Send us the details
              <ArrowRight className='size-4 transition-transform group-hover:translate-x-1' aria-hidden='true' />
            </Link>
            {phones.map((phone) => (
              <a key={phone} href={telHref(phone)} className='inline-flex items-center justify-center gap-2 rounded-lg border border-white/30 bg-white/5 px-6 py-3.5 font-bold backdrop-blur-sm transition-colors hover:bg-white/15'>
                <Phone className='size-4' aria-hidden='true' />
                {phone}
              </a>
            ))}
          </div>
        </div>

        <div className='flex flex-col rounded-3xl bg-mist p-8 sm:p-10'>
          <p className='eyebrow'>How it works</p>
          <ol className='mt-6 flex-1 space-y-6'>
            {steps.map((step, index) => {
              const Icon = step.icon

              return (
                <li key={step.title} className='flex gap-4'>
                  <span className='relative grid size-12 shrink-0 place-items-center rounded-xl bg-white text-brand-600 shadow-sm ring-1 ring-line'>
                    <Icon className='size-5' aria-hidden='true' />
                    <span className='absolute -right-1.5 -top-1.5 grid size-5 place-items-center rounded-full bg-brand-600 text-[11px] font-bold text-white'>{index + 1}</span>
                  </span>
                  <div>
                    <h3 className='font-display text-xl font-bold uppercase text-ink'>{step.title}</h3>
                    <p className='mt-1 leading-7 text-slate-600'>{step.text}</p>
                  </div>
                </li>
              )
            })}
          </ol>
          <p className='mt-8 flex items-center gap-2 border-t border-line pt-6 text-sm text-slate-600'>
            <ShieldCheck className='size-5 shrink-0 text-brand-600' aria-hidden='true' />
            Your details are only used to reply to your enquiry. No pressure, no obligation.
          </p>
        </div>
      </div>
    </section>
  )
}
