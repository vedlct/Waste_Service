'use client'

import Image from 'next/image'
import Link from 'next/link'
import { usePathname } from 'next/navigation'
import { useEffect, useRef, useState } from 'react'
import { ArrowRight, ChevronDown, Menu, Phone, X } from 'lucide-react'
import CartLink from '../cart/CartLink'
import { MAIN_LINKS, SERVICE_GROUPS } from '@/lib/navigation'
import { FALLBACK_CONTACT, telHref } from '@/lib/site'

export default function Navbar({ contact = FALLBACK_CONTACT }) {
  const pathname = usePathname()
  const [servicesOpen, setServicesOpen] = useState(false)
  const [menuOpen, setMenuOpen] = useState(false)
  const [openGroup, setOpenGroup] = useState(null)
  const [menuTop, setMenuTop] = useState(64)
  const navRef = useRef(null)
  const closeTimer = useRef(null)
  const hoverOpenedAt = useRef(0)

  const closeAll = () => {
    setServicesOpen(false)
    setMenuOpen(false)
    setOpenGroup(null)
  }

  // Close menus on outside clicks and Escape; links close them when followed.
  useEffect(() => {
    const onPointerDown = (event) => {
      if (navRef.current && !navRef.current.contains(event.target)) closeAll()
    }
    const onKeyDown = (event) => {
      if (event.key === 'Escape') closeAll()
    }

    document.addEventListener('pointerdown', onPointerDown)
    document.addEventListener('keydown', onKeyDown)

    return () => {
      document.removeEventListener('pointerdown', onPointerDown)
      document.removeEventListener('keydown', onKeyDown)
    }
  }, [])

  // Lock page scroll behind the open mobile menu.
  useEffect(() => {
    document.body.style.overflow = menuOpen ? 'hidden' : ''

    return () => {
      document.body.style.overflow = ''
    }
  }, [menuOpen])

  const toggleMobileMenu = () => {
    // The menu sits just under the header, wherever the header currently is.
    const header = navRef.current?.closest('header')
    if (header) setMenuTop(Math.round(header.getBoundingClientRect().bottom))
    setMenuOpen((open) => !open)
  }

  const openServices = () => {
    clearTimeout(closeTimer.current)
    if (!servicesOpen) hoverOpenedAt.current = Date.now()
    setServicesOpen(true)
  }

  // A click straight after hovering opened the menu would otherwise close it again.
  const toggleServices = () => {
    const justHovered = Date.now() - hoverOpenedAt.current < 500
    setServicesOpen((open) => (justHovered ? true : !open))
  }
  const closeServicesSoon = () => {
    closeTimer.current = setTimeout(() => setServicesOpen(false), 150)
  }

  const isActive = (href) => pathname === href
  const inServices = SERVICE_GROUPS.some((group) => group.links.some((link) => link.href === pathname))

  return (
    <nav ref={navRef} aria-label='Main navigation' className='order-first lg:order-none lg:flex-1'>
      {/* Desktop */}
      <ul className='hidden items-center justify-center gap-1 lg:flex'>
        <li onMouseEnter={openServices} onMouseLeave={closeServicesSoon}>
          <button
            type='button'
            aria-expanded={servicesOpen}
            aria-controls='services-menu'
            onClick={toggleServices}
            className={`inline-flex items-center gap-1 rounded-lg px-3.5 py-2 text-[15px] font-semibold transition-colors hover:bg-brand-50 hover:text-brand-700 ${servicesOpen || inServices ? 'text-brand-700' : 'text-ink'}`}
          >
            Services
            <ChevronDown className={`size-4 transition-transform ${servicesOpen ? 'rotate-180' : ''}`} aria-hidden='true' />
          </button>

          <div
            id='services-menu'
            className={`absolute inset-x-4 top-full pt-2 sm:inset-x-6 lg:inset-x-8 ${servicesOpen ? 'block' : 'hidden'}`}
          >
            <div className='grid grid-cols-[1fr_1fr_1fr_18rem] gap-8 rounded-2xl border border-line bg-white p-8 shadow-[0_30px_60px_-20px_rgba(15,37,87,0.3)]'>
              <ServiceColumn group={SERVICE_GROUPS[0]} pathname={pathname} onNavigate={closeAll} />
              <ServiceColumn group={SERVICE_GROUPS[1]} pathname={pathname} onNavigate={closeAll} />
              <div className='space-y-8'>
                <ServiceColumn group={SERVICE_GROUPS[2]} pathname={pathname} onNavigate={closeAll} />
                <ServiceColumn group={SERVICE_GROUPS[3]} pathname={pathname} onNavigate={closeAll} />
              </div>
              <Link href='/prices' onClick={closeAll} className='group relative isolate flex min-h-64 flex-col justify-end overflow-hidden rounded-xl p-5 text-white'>
                <Image src='/images/load3.jpg' alt='' fill sizes='18rem' className='-z-20 object-cover transition-transform duration-500 group-hover:scale-105' />
                <span className='absolute inset-0 -z-10 bg-linear-to-t from-brand-950 via-brand-900/50 to-transparent' aria-hidden='true' />
                <span className='tag-slant self-start text-xs'>Same-day</span>
                <span className='mt-3 font-display text-2xl font-bold uppercase leading-none'>Man &amp; van clearance</span>
                <span className='mt-2 text-sm text-white/80'>Pick your items, see the price and book online in minutes.</span>
                <span className='mt-4 inline-flex items-center gap-1.5 text-sm font-bold text-brand-300'>
                  See prices <ArrowRight className='size-4 transition-transform group-hover:translate-x-1' aria-hidden='true' />
                </span>
              </Link>
            </div>
          </div>
        </li>

        {MAIN_LINKS.map((link) => (
          <li key={link.href}>
            <Link
              href={link.href}
              onClick={closeAll}
              aria-current={isActive(link.href) ? 'page' : undefined}
              className={`rounded-lg px-3.5 py-2 text-[15px] font-semibold transition-colors hover:bg-brand-50 hover:text-brand-700 ${isActive(link.href) ? 'text-brand-700' : 'text-ink'}`}
            >
              {link.name}
            </Link>
          </li>
        ))}
      </ul>

      {/* Mobile */}
      <button
        type='button'
        aria-expanded={menuOpen}
        aria-controls='mobile-menu'
        aria-label={menuOpen ? 'Close menu' : 'Open menu'}
        onClick={toggleMobileMenu}
        className='grid size-10 place-items-center rounded-lg text-ink transition-colors hover:bg-brand-50 lg:hidden'
      >
        {menuOpen ? <X className='size-6' aria-hidden='true' /> : <Menu className='size-6' aria-hidden='true' />}
      </button>

      <div
        id='mobile-menu'
        style={{ top: menuTop }}
        className={`fixed inset-x-0 bottom-0 z-40 overflow-y-auto border-t border-line bg-white px-4 pb-10 pt-2 sm:px-6 lg:hidden ${menuOpen ? 'block' : 'hidden'}`}
      >
        <ul className='divide-y divide-line'>
          {SERVICE_GROUPS.map((group) => (
            <li key={group.title}>
              <button
                type='button'
                aria-expanded={openGroup === group.title}
                onClick={() => setOpenGroup(openGroup === group.title ? null : group.title)}
                className='flex w-full items-center justify-between py-4 text-left text-base font-bold text-ink'
              >
                {group.title}
                <ChevronDown className={`size-5 text-brand-600 transition-transform ${openGroup === group.title ? 'rotate-180' : ''}`} aria-hidden='true' />
              </button>
              {openGroup === group.title && (
                <ul className='grid grid-cols-1 gap-1 pb-4 sm:grid-cols-2'>
                  {group.links.map((link) => (
                    <li key={link.href}>
                      <Link
                        href={link.href}
                        onClick={closeAll}
                        className={`block rounded-lg px-3 py-2.5 text-[15px] ${isActive(link.href) ? 'bg-brand-50 font-semibold text-brand-700' : 'text-ink/80'}`}
                      >
                        {link.name}
                      </Link>
                    </li>
                  ))}
                </ul>
              )}
            </li>
          ))}
          {MAIN_LINKS.map((link) => (
            <li key={link.href}>
              <Link href={link.href} onClick={closeAll} className='block py-4 text-base font-bold text-ink'>
                {link.name}
              </Link>
            </li>
          ))}
          <li className='py-2'>
            <CartLink mobileMenu />
          </li>
        </ul>

        <div className='mt-6 grid gap-3'>
          {[contact.phone, contact.phoneSecondary].filter(Boolean).map((phone) => (
            <a key={phone} href={telHref(phone)} className='flex items-center justify-center gap-2 rounded-lg border border-line py-3.5 font-bold text-ink'>
              <Phone className='size-4 text-brand-600' aria-hidden='true' />
              Call {phone}
            </a>
          ))}
          <Link href='/prices' onClick={closeAll} className='flex items-center justify-center gap-2 rounded-lg bg-brand-600 py-3.5 font-bold text-white'>
            Get a price &amp; book
            <ArrowRight className='size-4' aria-hidden='true' />
          </Link>
        </div>
      </div>
    </nav>
  )
}

function ServiceColumn({ group, pathname, onNavigate }) {
  return (
    <div>
      <p className='font-display text-lg font-bold uppercase tracking-wide text-ink'>{group.title}</p>
      <ul className='mt-3 space-y-0.5'>
        {group.links.map((link) => (
          <li key={link.href}>
            <Link
              href={link.href}
              onClick={onNavigate}
              className={`-mx-2 flex items-center justify-between rounded-md px-2 py-1.5 text-[15px] transition-colors hover:bg-brand-50 hover:text-brand-700 ${pathname === link.href ? 'font-semibold text-brand-700' : 'text-ink/75'}`}
            >
              {link.name}
            </Link>
          </li>
        ))}
      </ul>
    </div>
  )
}
