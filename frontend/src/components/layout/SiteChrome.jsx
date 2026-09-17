'use client'

import { usePathname } from 'next/navigation'
import Header from '@/components/layout/Header'
import Footer from '@/components/layout/Footer'
import ScrollToTop from '../buttons/ScrollToTop'
import StickyCart from '../buttons/StickyCart'
import { CartProvider } from '../cart/CartContext'

export default function SiteChrome({ children }) {
  const pathname = usePathname()
  const isAdminRoute = pathname === '/admin' || pathname.startsWith('/admin/')

  if (isAdminRoute) {
    return children
  }

  return (
    <CartProvider>
      <Header />
      <div className='fixed z-50 flex flex-col items-center gap-3' style={{ right: '1.5rem', bottom: '1.5rem' }}>
        <StickyCart />
        <ScrollToTop />
      </div>
      {children}
      <Footer />
    </CartProvider>
  )
}
