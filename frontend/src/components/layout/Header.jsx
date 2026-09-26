'use client'

import Image from 'next/image'
import Link from 'next/link'
import { useEffect, useState } from 'react'
import { ArrowRight, Phone, Star } from 'lucide-react'
import Navbar from './Navbar'
import CartLink from '../cart/CartLink'
import { FALLBACK_CONTACT, telHref } from '@/lib/site'

export default function Header({ contact = FALLBACK_CONTACT }) {
  const [isScrolled, setIsScrolled] = useState(false)

  useEffect(() => {
    const handleScroll = () => setIsScrolled(window.scrollY > 8)

    handleScroll()
    window.addEventListener('scroll', handleScroll, { passive: true })

    return () => window.removeEventListener('scroll', handleScroll)
  }, [])

  return (
    <>
      <div className='hidden bg-brand-700 text-white/90 md:block'>
        <div className='mx-auto flex h-10 max-w-7xl items-center justify-between gap-6 px-4 text-[13px] sm:px-6 lg:px-8'>
          <p className='flex items-center gap-2'>
            <span className='flex gap-0.5 text-amber-300' aria-hidden='true'>
              {Array.from({ length: 5 }).map((_, index) => <Star key={index} className='size-3.5 fill-current' />)}
            </span>
            <span>Reliable &amp; affordable rubbish clearance across Portsmouth</span>
          </p>
          <div className='flex items-center gap-5'>
            <PhoneLink phone={contact.phone} />
            {contact.phoneSecondary && <PhoneLink phone={contact.phoneSecondary} />}
          </div>
        </div>
      </div>

      <header className={`sticky top-0 z-50 border-b bg-white transition-shadow duration-300 ${isScrolled ? 'border-line shadow-[0_8px_30px_-12px_rgba(15,37,87,0.18)]' : 'border-transparent'}`}>
        <div className='relative mx-auto flex h-16 max-w-7xl items-center gap-4 px-4 sm:px-6 lg:h-[76px] lg:px-8'>
          <Link href='/' className='shrink-0' aria-label='MR. TEE Removals home'>
            <Image
              src='/images/brand/logo.png'
              alt='MR. TEE Removals'
              width={933}
              height={195}
              priority
              className='h-8 w-auto sm:h-9 lg:h-10'
            />
          </Link>

          <Navbar contact={contact} />

          <div className='ml-auto flex items-center gap-2 sm:gap-3'>
            <div className='hidden lg:block'>
              <CartLink />
            </div>
            <a
              href={telHref(contact.phone)}
              className='hidden items-center gap-2 rounded-lg px-3 py-2 text-sm font-bold text-ink transition-colors hover:bg-brand-50 hover:text-brand-700 xl:inline-flex'
            >
              <Phone className='size-4 text-brand-600' aria-hidden='true' />
              {contact.phone}
            </a>
            <Link
              href='/prices'
              aria-label='Get a price'
              className='group inline-flex shrink-0 items-center gap-2 rounded-lg bg-brand-600 px-3 py-2.5 text-sm font-bold text-white shadow-sm shadow-brand-600/30 transition-all hover:bg-brand-700 min-[430px]:px-3.5 sm:px-5'
            >
              <span className='hidden min-[430px]:inline'>Get a price</span>
              <ArrowRight className='size-4 transition-transform group-hover:translate-x-0.5' aria-hidden='true' />
            </Link>
          </div>
        </div>
      </header>
    </>
  )
}

function PhoneLink({ phone }) {
  return (
    <a href={telHref(phone)} className='inline-flex items-center gap-1.5 font-semibold text-white transition-colors hover:text-brand-100'>
      <Phone className='size-3.5 text-brand-200' aria-hidden='true' />
      {phone}
    </a>
  )
}
