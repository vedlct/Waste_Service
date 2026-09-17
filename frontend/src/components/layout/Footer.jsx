import Image from 'next/image'
import Link from 'next/link'
import { ArrowRight, ArrowUpRight, Clock3, Mail, MapPin, MessageCircle, Phone } from 'lucide-react'

const services = [
  { label: 'House Clearance', href: '/houseClearance' },
  { label: 'Garden Clearance', href: '/gardenClearance' },
  { label: 'Furniture Removal', href: '/flatClearance' },
  { label: 'Commercial Waste', href: '/officeWasteClearance' },
]

const companyLinks = [
  { label: 'Areas Covered', href: '/area' },
  { label: 'Prices & Book', href: '/prices' },
  { label: 'FAQ', href: '/faq' },
  { label: 'Contact Us', href: '/contactUs' },
]

export default function Footer() {
  return (
    <footer className='relative overflow-hidden border-t border-sky-100 bg-linear-to-b from-[#E7F6FD] to-white text-[#0A1B3D]'>
      <div aria-hidden='true' className='pointer-events-none absolute -right-28 -top-28 size-72 rounded-full bg-white/70 blur-3xl sm:size-96' />

      <div className='container relative mx-auto grid gap-x-8 gap-y-12 px-4 py-12 sm:px-6 sm:py-14 md:grid-cols-2 lg:grid-cols-[1.25fr_0.9fr_0.9fr_1.25fr] lg:px-8 lg:py-16'>
        <div className='md:col-span-2 lg:col-span-1'>
          <Link href='/' className='inline-flex rounded-lg transition duration-300 hover:scale-[1.03] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#8FC5EB]' aria-label='Waste Services home'>
            <Image src='/images/MainLogo.png' alt='Waste Services' width={80} height={54} className='h-auto w-16 sm:w-20' />
          </Link>

          <p className='mt-5 max-w-sm text-sm leading-6 text-[#0A1B3D]/70 sm:text-base'>
            Reliable rubbish collection for homes and businesses across the Home Counties.
          </p>

          <div className='mt-6 flex flex-wrap gap-3'>
            <Link href='/prices' className='group inline-flex items-center gap-2 rounded-full bg-[#0497E2] px-5 py-2.5 text-sm font-bold text-[#102a4c] shadow-lg shadow-black/10 transition-all duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-[#11224D] hover:shadow-xl focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0497E2]'>
              Prices &amp; Book
              <ArrowRight aria-hidden='true' className='size-4 transition-transform duration-300 group-hover:translate-x-1' />
            </Link>
            <Link href='/contactUs' className='group inline-flex items-center gap-2 rounded-full border border-sky-300 bg-white/70 px-5 py-2.5 text-sm font-semibold text-[#0A1B3D] transition-all duration-300 hover:-translate-y-0.5 hover:border-sky-500 hover:bg-sky-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0497E2]'>
              <MessageCircle aria-hidden='true' className='size-4 text-[#8FC5EB] transition-transform duration-300 group-hover:scale-110' />
              Contact us
            </Link>
          </div>
        </div>

        <FooterLinks title='Our services' links={services} />
        <FooterLinks title='Quick links' links={companyLinks} />

        <div>
          <h2 className='text-sm font-bold uppercase tracking-[0.18em] text-[#102A4C]'>Get in touch</h2>
          <div className='mt-5 space-y-2 text-sm text-[#0A1B3D]/75 sm:text-base'>
            <ContactLink href='tel:02082266477' icon={Phone}>020 8226 6477</ContactLink>
            <ContactLink href='mailto:info@wasteservices.com' icon={Mail}>info@wasteservices.com</ContactLink>
            <InfoRow icon={MapPin}>Serving the Home Counties</InfoRow>
            <InfoRow icon={Clock3}>Monâ€“Sat, 7:00amâ€“7:00pm</InfoRow>
          </div>
        </div>
      </div>

      <div className='border-t border-sky-200 bg-[#DDF2FC]'>
        <div className='container mx-auto flex flex-col gap-4 px-4 py-5 text-xs text-[#0A1B3D]/60 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8'>
          <p>Â© {new Date().getFullYear()} Waste Services. All rights reserved.</p>
          <div className='flex flex-wrap gap-x-5 gap-y-2'>
            <Link href='/contactUs' className='rounded-sm transition-colors duration-300 hover:text-[#0497E2] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#8FC5EB]'>Privacy enquiries</Link>
            <Link href='/contactUs' className='rounded-sm transition-colors duration-300 hover:text-[#0497E2] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#8FC5EB]'>Terms enquiries</Link>
          </div>
        </div>
      </div>
    </footer>
  )
}

function FooterLinks({ title, links }) {
  return (
    <nav aria-label={title}>
      <h2 className='text-sm font-bold uppercase tracking-[0.18em] text-[#102A4C]'>{title}</h2>
      <ul className='mt-5 space-y-1.5 text-sm text-[#0A1B3D]/70 sm:text-base'>
        {links.map(({ label, href }) => (
          <li key={href}>
            <Link href={href} className='group inline-flex min-h-9 items-center gap-2 rounded-lg px-2 py-1 transition-all duration-300 hover:translate-x-1 hover:bg-sky-100 hover:text-[#0497E2] focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-[#8FC5EB]'>
              <ArrowUpRight aria-hidden='true' className='size-4 shrink-0 text-[#102A4C] transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5' />
              {label}
            </Link>
          </li>
        ))}
      </ul>
    </nav>
  )
}

function ContactLink({ href, icon: Icon, children }) {
  return (
    <a href={href} className='group flex min-h-11 items-center gap-3 rounded-xl px-3 py-2.5 transition-all duration-300 hover:translate-x-1 hover:bg-sky-100 hover:text-[#0497E2] focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-[#8FC5EB]'>
      <Icon aria-hidden='true' className='size-5 shrink-0 text-[#49ADE5] transition-transform duration-300 group-hover:scale-110' />
      <span className='min-w-0 break-words'>{children}</span>
    </a>
  )
}

function InfoRow({ icon: Icon, children }) {
  return (
    <div className='flex items-start gap-3 rounded-xl px-3 py-2.5'>
      <Icon aria-hidden='true' className='mt-0.5 size-5 shrink-0 text-[#49ADE5]' />
      <span>{children}</span>
    </div>
  )
}
