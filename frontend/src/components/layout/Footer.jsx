import Image from 'next/image'
import Link from 'next/link'
import { ArrowRight, Clock3, Mail, MapPin, Phone, ShieldCheck, Star } from 'lucide-react'
import { MAIN_LINKS, SERVICE_GROUPS } from '@/lib/navigation'
import { FALLBACK_CONTACT, telHref } from '@/lib/site'

export default function Footer({ contact = FALLBACK_CONTACT }) {
  const phones = [contact.phone, contact.phoneSecondary].filter(Boolean)

  return (
    <footer className='relative bg-linear-to-b from-brand-900 to-brand-950 text-white'>
      {/* Call to action */}
      <div className='mx-auto max-w-7xl px-4 pt-16 sm:px-6 lg:px-8'>
        <div className='rounded-2xl bg-brand-600 px-6 py-10 sm:px-10 lg:flex lg:items-center lg:justify-between lg:gap-10 lg:px-14 lg:py-12'>
          <div className='max-w-xl'>
            <p className='font-display text-sm font-bold uppercase tracking-[0.2em] text-brand-200'>Rubbish cleared. Spaces restored.</p>
            <h2 className='mt-2 text-4xl font-bold uppercase leading-[0.95] sm:text-5xl'>Ready when you are</h2>
            <p className='mt-4 text-white/85'>Same-day clearance is often available across Portsmouth. Call the team or book online in minutes.</p>
          </div>
          <div className='mt-8 flex flex-col gap-3 sm:flex-row lg:mt-0 lg:shrink-0 lg:flex-col xl:flex-row'>
            <Link href='/prices' className='group inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-3.5 font-bold text-brand-700 shadow-lg transition-colors hover:bg-brand-50'>
              Get a price &amp; book
              <ArrowRight className='size-4 transition-transform group-hover:translate-x-0.5' aria-hidden='true' />
            </Link>
            <a href={telHref(contact.phone)} className='inline-flex items-center justify-center gap-2 rounded-lg border border-white/40 px-6 py-3.5 font-bold text-white transition-colors hover:bg-white/10'>
              <Phone className='size-4' aria-hidden='true' />
              {contact.phone}
            </a>
          </div>
        </div>
      </div>

      {/* Links */}
      <div className='mx-auto grid max-w-7xl grid-cols-2 gap-x-6 gap-y-10 px-4 py-14 sm:px-6 lg:grid-cols-[1.3fr_1fr_1fr_1.2fr] lg:px-8'>
        <div className='col-span-2 lg:col-span-1'>
          <Link href='/' aria-label='MR. TEE Removals home' className='inline-block'>
            <Image src='/images/brand/logo-white.png' alt='MR. TEE Removals' width={933} height={195} className='h-10 w-auto' />
          </Link>
          <p className='mt-5 max-w-sm text-sm leading-6 text-white/65'>
            Fast, affordable rubbish clearance for homes, gardens and businesses across Portsmouth and the surrounding area.
          </p>
          <ul className='mt-6 space-y-2 text-sm text-white/80'>
            {['Household waste removal', 'Same-day clearance available', 'Fully insured, licensed waste carrier'].map((point) => (
              <li key={point} className='flex items-center gap-2'>
                <ShieldCheck className='size-4 shrink-0 text-brand-400' aria-hidden='true' />
                {point}
              </li>
            ))}
          </ul>
        </div>

        <FooterLinks title='Rubbish removal' links={SERVICE_GROUPS[0].links} />
        <FooterLinks
          title='Commercial & more'
          links={[...SERVICE_GROUPS[1].links.slice(0, 4), ...SERVICE_GROUPS[2].links.slice(0, 1), ...MAIN_LINKS.slice(0, 2)]}
        />

        <div className='col-span-2 lg:col-span-1'>
          <h2 className='font-display text-lg font-bold uppercase tracking-wide leading-[0.95]'>Get in touch</h2>
          <ul className='mt-4 space-y-3 text-sm'>
            {phones.map((phone) => (
              <li key={phone}>
                <a href={telHref(phone)} className='group inline-flex items-center gap-3 font-display text-2xl font-bold tracking-wide transition-colors hover:text-brand-300'>
                  <span className='grid size-9 place-items-center rounded-lg bg-brand-600 transition-colors group-hover:bg-brand-500'>
                    <Phone className='size-4' aria-hidden='true' />
                  </span>
                  {phone}
                </a>
              </li>
            ))}
            <li>
              <a href={`mailto:${contact.email}`} className='inline-flex items-center gap-3 text-white/80 transition-colors hover:text-white'>
                <Mail className='size-4 shrink-0 text-brand-400' aria-hidden='true' />
                {contact.email}
              </a>
            </li>
            <li className='flex items-center gap-3 text-white/80'>
              <MapPin className='size-4 shrink-0 text-brand-400' aria-hidden='true' />
              {contact.location}
            </li>
            <li className='flex items-center gap-3 text-white/80'>
              <Clock3 className='size-4 shrink-0 text-brand-400' aria-hidden='true' />
              {contact.openingHours}
            </li>
          </ul>
        </div>
      </div>

      {/* Signature band */}
      <div className='border-t border-white/10 bg-brand-950'>
        <div className='mx-auto flex max-w-7xl flex-col items-center gap-3 px-4 py-5 text-sm sm:px-6 md:flex-row md:justify-between lg:px-8'>
          <p className='flex items-center gap-3 font-display text-lg font-bold italic tracking-wide'>
            <Stars />
            Reliable &amp; Affordable Service
            <Stars />
          </p>
          <div className='flex flex-wrap items-center justify-center gap-x-5 gap-y-1 text-white/50'>
            <span>© {new Date().getFullYear()} MR. TEE Removals</span>
            <Link href='/faq' className='hover:text-white'>FAQ</Link>
            <Link href='/contactUs' className='hover:text-white'>Contact</Link>
          </div>
        </div>
      </div>
    </footer>
  )
}

function Stars() {
  return (
    <span className='flex gap-0.5 text-amber-400' aria-hidden='true'>
      {Array.from({ length: 5 }).map((_, index) => <Star key={index} className='size-3.5 fill-current' />)}
    </span>
  )
}

function FooterLinks({ title, links }) {
  return (
    <nav aria-label={title}>
      <h2 className='font-display text-lg font-bold uppercase tracking-wide leading-[0.95]'>{title}</h2>
      <ul className='mt-4 space-y-2 text-sm'>
        {links.map(({ name, href }) => (
          <li key={href}>
            <Link href={href} className='text-white/65 transition-colors hover:text-white'>
              {name}
            </Link>
          </li>
        ))}
      </ul>
    </nav>
  )
}
