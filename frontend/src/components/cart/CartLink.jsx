'use client'

import Link from 'next/link'
import { ShoppingBasket } from 'lucide-react'
import { useCart } from './CartContext'

export default function CartLink({ mobileMenu = false }) {
  const { count } = useCart()

  if (mobileMenu) {
    return (
      <Link href='/checkout' aria-label={`Checkout basket with ${count} items`} className='flex w-full items-center gap-3 py-3 font-semibold text-ink transition-colors hover:text-brand-700'>
        <span className='relative grid size-9 shrink-0 place-items-center rounded-full border border-ink/15 bg-brand-50'>
          <ShoppingBasket className='size-4' />
          {count > 0 && <span className='absolute -right-1.5 -top-1.5 grid min-w-5 place-items-center rounded-full border-2 border-white bg-brand-500 px-1 text-[10px] font-black leading-4 text-white'>{count > 99 ? '99+' : count}</span>}
        </span>
        <span>View cart</span>
        {count > 0 && <span className='ml-auto text-xs text-ink/60'>{count} {count === 1 ? 'item' : 'items'}</span>}
      </Link>
    )
  }

  return (
    <Link href='/checkout' aria-label={`Checkout basket with ${count} items`} className='relative grid size-10 shrink-0 place-items-center rounded-full border border-ink/15 text-ink transition hover:-translate-y-0.5 hover:border-brand-600 hover:bg-brand-50 hover:text-brand-600'>
      <ShoppingBasket className='size-5' />
      {count > 0 && <span className='absolute -right-1 -top-1 grid min-w-5 place-items-center rounded-full bg-brand-500 px-1 text-[10px] font-black leading-5 text-white'>{count > 99 ? '99+' : count}</span>}
    </Link>
  )
}