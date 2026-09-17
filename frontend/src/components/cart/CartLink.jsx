'use client'

import Link from 'next/link'
import { ShoppingBasket } from 'lucide-react'
import { useCart } from './CartContext'

export default function CartLink({ mobileMenu = false }) {
  const { count } = useCart()

  if (mobileMenu) {
    return (
      <Link href='/checkout' aria-label={`Checkout basket with ${count} items`} className='flex w-full items-center gap-3 py-3 font-semibold text-[#11224D] transition-colors hover:text-[#1A68A3]'>
        <span className='relative grid size-9 shrink-0 place-items-center rounded-full border border-[#11224D]/15 bg-[#EAF2FB]'>
          <ShoppingBasket className='size-4' />
          {count > 0 && <span className='absolute -right-1.5 -top-1.5 grid min-w-5 place-items-center rounded-full border-2 border-white bg-[#F4B942] px-1 text-[10px] font-black leading-4 text-[#11224D]'>{count > 99 ? '99+' : count}</span>}
        </span>
        <span>View cart</span>
        {count > 0 && <span className='ml-auto text-xs text-[#11224D]/60'>{count} {count === 1 ? 'item' : 'items'}</span>}
      </Link>
    )
  }

  return (
    <Link href='/checkout' aria-label={`Checkout basket with ${count} items`} className='relative grid size-10 shrink-0 place-items-center rounded-full border border-[#11224D]/15 text-[#11224D] transition hover:-translate-y-0.5 hover:border-[#0497E2] hover:bg-[#EAF2FB] hover:text-[#0497E2]'>
      <ShoppingBasket className='size-5' />
      {count > 0 && <span className='absolute -right-1 -top-1 grid min-w-5 place-items-center rounded-full bg-[#F4B942] px-1 text-[10px] font-black leading-5 text-[#11224D]'>{count > 99 ? '99+' : count}</span>}
    </Link>
  )
}