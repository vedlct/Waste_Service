'use client'

import { useLayoutEffect, useRef, useState } from 'react'
import { createPortal } from 'react-dom'
import Link from 'next/link'
import { Minus, Plus, ShoppingBag, Trash2, X } from 'lucide-react'
import { useCart } from '../cart/CartContext'

export default function StickyCart() {
  const { items, ready, count, total, updateQuantity, removeItem } = useCart()
  const [isOpen, setIsOpen] = useState(false)
  const [coords, setCoords] = useState(null)
  const triggerRef = useRef(null)

  // Measure the trigger button's on-screen position so the panel can be
  // portaled to document.body (escaping any ancestor's overflow-hidden,
  // which is what was clipping it) while still appearing anchored to it.
  useLayoutEffect(() => {
    if (!isOpen) return

    const updateCoords = () => {
      const rect = triggerRef.current?.getBoundingClientRect()
      if (!rect) return
      setCoords({
        bottom: window.innerHeight - rect.top + 12,
        right: window.innerWidth - rect.right,
      })
    }

    updateCoords()
    window.addEventListener('resize', updateCoords)
    window.addEventListener('scroll', updateCoords, true)
    return () => {
      window.removeEventListener('resize', updateCoords)
      window.removeEventListener('scroll', updateCoords, true)
    }
  }, [isOpen])

  if (!ready) return null

  const panel = isOpen && coords && typeof document !== 'undefined' && createPortal(
    <section
      id='sticky-cart-panel'
      aria-label='Shopping cart'
      style={{
        bottom: coords.bottom,
        right: coords.right,
        maxWidth: 'calc(100vw - 2rem)',
        maxHeight: 'min(30rem, calc(100dvh - 6rem))',
      }}
      className='fixed z-60 flex w-96 shrink-0 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_24px_60px_-16px_rgba(17,34,77,0.35)]'
    >
      <header className='flex shrink-0 items-center justify-between border-b border-slate-100 px-5 py-4'>
        <div>
          <p className='text-[15px] font-semibold text-[#11224D]'>Your cart</p>
          <p className='mt-0.5 text-xs text-slate-500'>{count} {count === 1 ? 'item' : 'items'}</p>
        </div>
        <button
          type='button'
          onClick={() => setIsOpen(false)}
          aria-label='Close cart'
          className='grid size-8 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-[#11224D] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0497E2]'
        >
          <X className='size-4.5' />
        </button>
      </header>

      {items.length === 0 ? (
        <div className='flex flex-1 flex-col items-center justify-center px-6 py-10 text-center'>
          <div className='grid size-12 place-items-center rounded-full bg-slate-50'>
            <ShoppingBag className='size-10 text-slate-400' />
          </div>
          <p className='mt-4 text-sm font-semibold text-[#11224D]'>Your cart is empty</p>
          <p className='mt-1 max-w-[16rem] text-sm text-slate-500'>Browse our collections and add something you like.</p>
          <Link
            href='/#prices'
            onClick={() => setIsOpen(false)}
            className='mt-5 rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-[#11224D] transition hover:border-[#0497E2] hover:text-[#0497E2]'
          >
            Continue browsing
          </Link>
        </div>
      ) : (
        <>
          <ul className='flex-1 space-y-1 overflow-y-auto px-3 py-3'>
            {items.map((item) => (
              <li key={item.id} className='flex items-start gap-3 rounded-xl px-2 py-3 transition hover:bg-slate-50'>
                <div className='min-w-0 flex-1'>
                  <p className='truncate text-sm font-medium text-[#11224D]'>{item.name}</p>
                  <p className='mt-0.5 truncate text-xs text-slate-500'>{item.detail}</p>

                  <div className='mt-2 flex items-center justify-between'>
                    <div className='flex items-center gap-2'>
                      <button
                        type='button'
                        onClick={() => updateQuantity(item.id, item.quantity - 1)}
                        aria-label={`Decrease ${item.name} quantity`}
                        className='grid size-6 place-items-center rounded-full border border-slate-200 text-[#11224D] transition hover:border-[#0497E2] hover:text-[#0497E2]'
                      >
                        <Minus className='size-3' />
                      </button>
                      <span className='w-4 text-center text-xs font-semibold tabular-nums text-[#11224D]'>{item.quantity}</span>
                      <button
                        type='button'
                        onClick={() => updateQuantity(item.id, item.quantity + 1)}
                        aria-label={`Increase ${item.name} quantity`}
                        className='grid size-6 place-items-center rounded-full border border-slate-200 text-[#11224D] transition hover:border-[#0497E2] hover:text-[#0497E2]'
                      >
                        <Plus className='size-3' />
                      </button>
                    </div>
                    <p className='text-sm font-semibold tabular-nums text-[#11224D]'>£{(item.unitPrice * item.quantity).toFixed(2)}</p>
                  </div>
                </div>

                <button
                  type='button'
                  onClick={() => removeItem(item.id)}
                  aria-label={`Remove ${item.name} from cart`}
                  className='grid size-7 shrink-0 place-items-center rounded-full text-slate-300 transition hover:bg-rose-50 hover:text-rose-500'
                >
                  <Trash2 className='size-3.5' />
                </button>
              </li>
            ))}
          </ul>

          <div className='shrink-0 border-t border-slate-100 px-5 pb-5 pt-4'>
            <div className='mb-4 flex items-baseline justify-between'>
              <span className='text-sm text-slate-500'>Total</span>
              <span className='text-lg font-semibold tabular-nums text-[#11224D]'>£{total.toFixed(2)}</span>
            </div>
            <Link
              href='/checkout'
              onClick={() => setIsOpen(false)}
              className='flex min-h-11 items-center justify-center rounded-full bg-[#11224D] px-5 text-sm font-semibold text-white transition hover:bg-[#1b3570] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0497E2]'
            >
              Review cart
            </Link>
          </div>
        </>
      )}
    </section>,
    document.body
  )

  return (
    <div className='block lg:hidden relative z-60'>
      {panel}

      <button
        ref={triggerRef}
        type='button'
        onClick={() => setIsOpen((open) => !open)}
        aria-expanded={isOpen}
        aria-controls='sticky-cart-panel'
        aria-label={`${isOpen ? 'Close' : 'Open'} cart with ${count} ${count === 1 ? 'item' : 'items'}`}
        className='relative grid size-14 shrink-0 place-items-center rounded-full bg-[#0497E2] text-white shadow-[0_10px_24px_-6px_rgba(4,151,226,0.55)] transition hover:-translate-y-0.5 hover:bg-[#0489cc] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#11224D]'
      >
        {isOpen ? <X className='size-5' /> : <ShoppingBag className='size-5' />}
        {count > 0 && (
          <span className='absolute -right-1 -top-1 grid min-h-5 min-w-5 place-items-center rounded-full bg-[#F4B942] px-1 text-[11px] font-bold text-[#11224D] ring-2 ring-white'>
            {count > 99 ? '99+' : count}
          </span>
        )}
      </button>
    </div>
  )
}
