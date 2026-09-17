'use client'

import { useState } from 'react'
import Link from 'next/link'
import { ArrowLeft, BadgeCheck, CreditCard, Info, LockKeyhole, ShieldCheck, Tag } from 'lucide-react'
import { useCart } from '../cart/CartContext'

const inputClass = 'mt-2 min-h-12 w-full rounded-md border-2 border-slate-200 bg-white px-4 text-sm text-slate-800 outline-none transition-all duration-200 placeholder:text-slate-400 hover:border-slate-300 focus:border-[#0497E2] focus:ring-4 focus:ring-[#0497E2]/15'

function Field({ label, name, type = 'text', placeholder, required = false, autoComplete, className = '' }) {
  return (
    <label className={className}>
      <span className='text-[0.8rem] font-bold uppercase tracking-wide text-slate-500'>
        {label}
        {required && <span className='text-rose-500'> *</span>}
      </span>
      <input className={inputClass} name={name} type={type} placeholder={placeholder} required={required} autoComplete={autoComplete} />
    </label>
  )
}

function AddressFields({ prefix = 'billing' }) {
  return (
    <div className='grid gap-4 sm:grid-cols-2'>
      <Field label='Street address' name={`${prefix}Address`} placeholder='House number and street name' required autoComplete='street-address' className='sm:col-span-2' />
      <Field label='Apartment, suite or unit' name={`${prefix}AddressTwo`} placeholder='Optional' className='sm:col-span-2' />
      <Field label='Town / City' name={`${prefix}City`} required autoComplete='address-level2' />
      <Field label='State / County' name={`${prefix}County`} placeholder='Optional' autoComplete='address-level1' />
      <Field label='Postcode / ZIP' name={`${prefix}Postcode`} required autoComplete='postal-code' />
      <label>
        <span className='text-[0.8rem] font-bold uppercase tracking-wide text-slate-500'>Country / Region</span>
        <select name={`${prefix}Country`} defaultValue='United Kingdom' className={inputClass}>
          <option>United Kingdom</option>
          <option>Ireland</option>
        </select>
      </label>
    </div>
  )
}

function SectionHeading({ icon: Icon, step, title, hint }) {
  return (
    <div className='mb-5 flex items-center gap-4'>
      <span className='relative grid size-11 shrink-0 place-items-center rounded-full text-white shadow-[0_6px_16px_-4px_rgba(1,151,234,0.5)]' style={{ backgroundColor: '#0197EA' }}>
        <Icon className='size-5' />
        <span className='absolute -right-1 -top-1 grid size-5 place-items-center rounded-full bg-[#F4B942] text-[0.6rem] font-black text-[#11224D]'>{step}</span>
      </span>
      <div>
        <h2 className='text-xl font-black tracking-tight text-slate-900'>{title}</h2>
        <p className='text-xs text-slate-500'>{hint}</p>
      </div>
    </div>
  )
}

export default function Payment() {
  const { items, ready, count, total } = useCart()
  const [showVoucher, setShowVoucher] = useState(false)
  const [differentAddress, setDifferentAddress] = useState(false)
  const [message, setMessage] = useState('')

  const handleSubmit = (event) => {
    event.preventDefault()
    setMessage('Your details are complete. Secure payment processing can now be connected to this form.')
  }

  if (!ready) return <div className='min-h-[60vh] bg-[#0d1424]' />

  return (
    <main className='min-h-screen bg-[radial-gradient(circle_at_top,_#132449_0%,_#0d1424_45%,_#f5f6f8_45%)] px-4 pb-20 pt-28 sm:px-6 lg:px-8 lg:pt-32'>
      <div className='mx-auto max-w-7xl'>
        <Link href='/checkout' className='group inline-flex items-center gap-2 text-sm font-bold text-white/80 transition-colors duration-200 hover:text-[#F4B942]'>
          <ArrowLeft className='size-4 transition-transform duration-200 group-hover:-translate-x-1' />
          Back to order review
        </Link>

        <div className='mt-6 overflow-hidden rounded-3xl border border-white/10 bg-white shadow-[0_40px_80px_-30px_rgba(13,20,36,0.55)]'>
          <header className='relative overflow-hidden px-5 py-10 text-center sm:px-8' style={{ backgroundColor: '#0197EA' }}>
            <div aria-hidden='true' className='pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(104,196,239,0.18),transparent_45%),radial-gradient(circle_at_85%_75%,rgba(244,185,66,0.16),transparent_45%)]' />
            <p className='relative inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-1.5 text-xs font-bold uppercase tracking-[.2em] text-white backdrop-blur-sm'>
              <LockKeyhole className='size-3.5' />
              Secure payment
            </p>
            <h1 className='relative mt-4 text-3xl font-black tracking-tight text-white sm:text-4xl'>Complete your order</h1>
            <p className='relative mx-auto mt-2 max-w-2xl text-sm text-white/85'>Enter your billing information and review your collection before placing the order.</p>
          </header>

          {items.length === 0 ? (
            <div className='grid min-h-80 place-items-center p-8 text-center'>
              <div>
                <h2 className='text-2xl font-black text-slate-900'>Your basket is empty</h2>
                <p className='mt-2 text-slate-500'>Add a collection before continuing to payment.</p>
                <Link href='/#prices' className='mt-5 inline-flex rounded-full bg-[#11224D] px-6 py-3 font-bold text-white shadow-[0_10px_24px_-8px_rgba(17,34,77,0.6)] transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#0d1a3b] hover:shadow-[0_16px_32px_-8px_rgba(17,34,77,0.7)]'>
                  Choose a collection
                </Link>
              </div>
            </div>
          ) : (
            <form onSubmit={handleSubmit} className='p-4 sm:p-7 lg:p-9'>
              <section className='rounded-2xl border-2 border-dashed border-[#F4B942]/50 bg-[#FFFBEF] p-4 transition-colors duration-200 hover:border-[#F4B942] sm:p-5'>
                <button type='button' onClick={() => setShowVoucher((value) => !value)} aria-expanded={showVoucher} className='group flex w-full items-center gap-3 text-left text-sm font-bold text-slate-700'>
                  <span className='grid size-9 place-items-center rounded-full bg-[#F4B942] text-[#11224D] shadow-sm transition-transform duration-200 group-hover:rotate-12'>
                    <Tag className='size-4' />
                  </span>
                  <span className='flex-1'>
                    Have a voucher? <span className='font-bold text-[#b5810f] underline decoration-[#F4B942] underline-offset-2'>Enter your code</span>
                  </span>
                </button>
                {showVoucher && (
                  <div className='mt-4 flex flex-col gap-2 sm:flex-row'>
                    <input name='voucher' placeholder='Voucher code' className={`${inputClass} mt-0 flex-1 border-[#F4B942]/60 focus:border-[#F4B942] focus:ring-[#F4B942]/20`} />
                    <button type='button' className='min-h-12 rounded-md bg-[#11224D] px-6 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#0d1a3b] hover:shadow-md active:translate-y-0'>
                      Apply voucher
                    </button>
                  </div>
                )}
              </section>

              <button type='button' className='mt-5 flex min-h-12 w-full items-center justify-center gap-2 rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 px-5 text-sm font-bold text-white shadow-[0_10px_24px_-8px_rgba(5,150,105,0.55)] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_16px_32px_-8px_rgba(5,150,105,0.65)] active:translate-y-0'>
                <LockKeyhole className='size-4' />
                Fast, secure checkout
              </button>

              <div className='my-7 flex items-center gap-4 text-xs font-bold uppercase tracking-[.16em] text-slate-400'>
                <span className='h-px flex-1 bg-gradient-to-r from-transparent via-slate-200 to-slate-200' />
                or pay by card
                <span className='h-px flex-1 bg-gradient-to-l from-transparent via-slate-200 to-slate-200' />
              </div>

              <div className='grid gap-8 lg:grid-cols-[minmax(0,1.35fr)_minmax(20rem,.65fr)] lg:items-start'>
                <div className='space-y-8'>
                  <section>
                    <SectionHeading icon={Info} step={1} title='Billing details' hint='Fields marked with * are required.' />
                    <div className='grid gap-4 border-l-2 border-slate-100 pl-5 sm:grid-cols-2'>
                      <Field label='First name' name='firstName' required autoComplete='given-name' />
                      <Field label='Last name' name='lastName' required autoComplete='family-name' />
                      <Field label='Company name' name='company' placeholder='Optional' autoComplete='organization' className='sm:col-span-2' />
                      <div className='sm:col-span-2'>
                        <AddressFields />
                      </div>
                      <Field label='Phone' name='phone' type='tel' required autoComplete='tel' />
                      <Field label='Mobile phone' name='mobile' type='tel' required autoComplete='tel-national' />
                      <Field label='Email address' name='email' type='email' required autoComplete='email' className='sm:col-span-2' />
                    </div>
                  </section>

                  <section className='rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200 transition-shadow duration-200 hover:ring-2 hover:ring-[#0497E2]/40 sm:p-5'>
                    <label className='flex cursor-pointer items-center gap-3 font-bold text-slate-800'>
                      <input type='checkbox' checked={differentAddress} onChange={(event) => setDifferentAddress(event.target.checked)} className='size-5 accent-[#11224D]' />
                      Waste is located at a different address
                    </label>
                    {differentAddress && (
                      <div className='mt-5 border-t border-slate-200 pt-5'>
                        <h3 className='mb-4 font-bold text-slate-800'>Collection address</h3>
                        <AddressFields prefix='collection' />
                      </div>
                    )}
                  </section>

                  <section>
                    <SectionHeading icon={CreditCard} step={2} title='Payment details' hint='Use the card details associated with your billing address.' />
                    <div className='rounded-2xl border-l-2 border-slate-100 bg-gradient-to-br from-slate-50 to-white p-4 pl-5 ring-1 ring-slate-200 sm:p-5'>
                      <Field label='Card number' name='cardNumber' placeholder='1234 1234 1234 1234' required autoComplete='cc-number' />
                      <div className='mt-4 grid gap-4 sm:grid-cols-2'>
                        <Field label='Expiry date' name='expiry' placeholder='MM / YY' required autoComplete='cc-exp' />
                        <Field label='Security code' name='securityCode' placeholder='CVC' required autoComplete='cc-csc' />
                      </div>
                      <p className='mt-4 flex items-center gap-2 text-xs font-medium text-slate-500'>
                        <ShieldCheck className='size-3.5 text-emerald-600' />
                        Your payment details are protected during checkout.
                      </p>
                    </div>
                  </section>
                </div>

                <aside className='overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_20px_50px_-24px_rgba(16,24,40,0.35)] transition-shadow duration-300 hover:shadow-[0_28px_60px_-20px_rgba(16,24,40,0.45)] lg:sticky lg:top-28'>
                  <div className='px-5 py-4' style={{ backgroundColor: '#0197EA' }}>
                    <h2 className='text-lg font-black text-white'>Your order</h2>
                  </div>

                  <div className='p-5'>
                    <div className='max-h-72 space-y-1 overflow-auto'>
                      {items.map((item) => (
                        <div key={item.id} className='group flex gap-3 rounded-xl p-2 text-sm transition-all duration-200 hover:-translate-x-0.5 hover:bg-[#eef4ff]'>
                          <span className='grid size-8 shrink-0 place-items-center rounded-full bg-[#11224D] text-xs font-black text-white transition-colors duration-200 group-hover:bg-[#F4B942] group-hover:text-[#11224D]'>{item.quantity}</span>
                          <div className='min-w-0 flex-1'>
                            <p className='font-bold text-slate-800'>{item.name}</p>
                            <p className='mt-0.5 truncate text-xs text-slate-400'>{item.detail}</p>
                          </div>
                          <span className='font-black text-slate-800'>£{(item.unitPrice * item.quantity).toFixed(2)}</span>
                        </div>
                      ))}
                    </div>

                    <div className='mt-4 space-y-3 border-t-2 border-dashed border-slate-200 py-5 text-sm'>
                      <div className='flex justify-between text-slate-500'>
                        <span>Items</span>
                        <span className='font-semibold text-slate-700'>{count}</span>
                      </div>
                      <div className='flex justify-between text-slate-500'>
                        <span>VAT</span>
                        <span className='font-semibold text-slate-700'>Included</span>
                      </div>
                      <div className='flex items-end justify-between rounded-xl bg-[#eef4ff] px-4 py-3'>
                        <span className='font-bold text-[#11224D]'>Total</span>
                        <span className='text-3xl font-black text-[#11224D]'>£{total.toFixed(2)}</span>
                      </div>
                    </div>

                    <label id='terms' className='flex cursor-pointer items-start gap-3 rounded-xl bg-slate-50 p-3 text-xs leading-relaxed text-slate-600 ring-1 ring-slate-200 transition-colors duration-200 hover:bg-slate-100'>
                      <input type='checkbox' name='terms' required className='mt-0.5 size-4 shrink-0 accent-[#11224D]' />
                      <span>
                        I have read and agree to the <Link href='#terms' className='font-bold text-[#0497E2] underline decoration-[#0497E2]/40 underline-offset-2 transition-colors duration-200 hover:text-[#11224D] hover:decoration-[#11224D]'>terms and conditions</Link> and acknowledge the privacy policy.
                      </span>
                    </label>

                    <button type='submit' className='group mt-5 flex min-h-13 w-full items-center justify-center gap-2 overflow-hidden rounded-full border border-[#0497E2] bg-[#0497E2] px-5 text-sm font-black uppercase tracking-wide text-white shadow-[0_14px_28px_-10px_rgba(17,34,77,0.6)] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_20px_36px_-10px_rgba(17,34,77,0.7)] focus-visible:outline-2 focus-visible:outline-offset-3 focus-visible:outline-[#0497E2] active:translate-y-0'>
                      <ShieldCheck className='size-5 transition-transform duration-200 group-hover:scale-110' />
                      Place order
                    </button>

                    <p aria-live='polite' className='mt-3 text-center text-xs font-semibold text-emerald-600'>{message}</p>

                    <div className='mt-4 flex items-center justify-center gap-2 text-xs font-medium text-slate-400'>
                      <BadgeCheck className='size-4 text-emerald-600' />
                      Secure order confirmation
                    </div>
                  </div>
                </aside>
              </div>
            </form>
          )}
        </div>
      </div>
    </main>
  )
}