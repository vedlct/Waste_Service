'use client'

import { useState } from 'react'
import Link from 'next/link'
import { ArrowLeft, BadgeCheck, CircleAlert, CircleCheck, Info, LoaderCircle, LockKeyhole, ShieldCheck, Wallet } from 'lucide-react'
import { apiPost } from '@/lib/api'
import { isBookable, useCart } from '../cart/CartContext'

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

function address(formData, prefix, contact) {
  return {
    ...contact,
    address_line_1: formData.get(`${prefix}Address`),
    address_line_2: formData.get(`${prefix}AddressTwo`) || null,
    city: formData.get(`${prefix}City`),
    county: formData.get(`${prefix}County`) || null,
    postcode: formData.get(`${prefix}Postcode`),
    country: formData.get(`${prefix}Country`) || 'United Kingdom',
  }
}

// Online card payment is not offered yet, so no card details are ever collected here.
// Pay-on-arrival jobs are paid to the crew; pay-now jobs are followed up by the office.
function paymentNote(paymentOption) {
  return paymentOption === 'arrival'
    ? 'You pay on arrival. The callout fee and the balance are paid to our team on the day of collection.'
    : 'No card details are taken online. Our team will contact you to arrange payment before your collection.'
}

function Confirmation({ booking }) {
  return (
    <div className='grid min-h-80 place-items-center p-8 text-center'>
      <div className='max-w-lg'>
        <span className='mx-auto grid size-16 place-items-center rounded-full bg-emerald-50 text-emerald-600'>
          <CircleCheck className='size-8' />
        </span>
        <h2 className='mt-5 text-2xl font-black text-slate-900'>Thank you, your booking is in</h2>
        <p className='mt-2 text-slate-600'>Your booking reference is</p>
        <p className='mt-1 text-3xl font-black tracking-wider text-[#11224D]'>{booking.reference}</p>
        <p className='mt-4 text-sm text-slate-600'>
          {booking.requires_payment
            ? 'Our team will be in touch shortly to take payment and confirm your collection.'
            : 'Our team will be in touch shortly to confirm your collection.'}
        </p>
        {booking.totals?.total_pence != null && (
          <p className='mt-4 text-sm font-bold text-slate-700'>
            Total: £{(booking.totals.total_pence / 100).toFixed(2)} <span className='font-normal text-slate-500'>(inc. VAT)</span>
          </p>
        )}
        <p className='mt-2 text-xs text-slate-500'>Please quote your reference if you contact us about this booking.</p>
        <Link href='/' className='mt-6 inline-flex rounded-full bg-[#11224D] px-6 py-3 font-bold text-white transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#0d1a3b]'>
          Back to home
        </Link>
      </div>
    </div>
  )
}

export default function Payment() {
  const { items, ready, count, total, collection, clearCart } = useCart()
  const [differentAddress, setDifferentAddress] = useState(false)
  const [submission, setSubmission] = useState({ state: 'idle', message: '' })
  const [booking, setBooking] = useState(null)

  const handleSubmit = async (event) => {
    event.preventDefault()
    if (submission.state === 'sending') return

    // Lines added before the site was wired to the booking API carry no catalogue id.
    const staleLines = items.filter((item) => item.catalogueType !== 'surcharge' && !isBookable(item))
    if (staleLines.length > 0) {
      setSubmission({ state: 'error', message: 'Some items in your basket are out of date. Please remove them and add them again from the prices page.' })
      return
    }

    if (!collection?.collection_date) {
      setSubmission({ state: 'error', message: 'Your collection details are missing. Please choose a collection date on the prices page and add your items again.' })
      return
    }

    const formData = new FormData(event.currentTarget)
    const contact = {
      first_name: formData.get('firstName'),
      last_name: formData.get('lastName'),
      company: formData.get('company') || null,
      phone: formData.get('phone'),
      mobile: formData.get('mobile') || null,
      email: formData.get('email'),
    }

    setSubmission({ state: 'sending', message: '' })

    // Only catalogue ids and quantities are sent. Prices are always read from the
    // catalogue on the server, and surcharges are added there too.
    const result = await apiPost('bookings', {
      items: items.filter(isBookable).map((item) => ({
        type: item.catalogueType,
        id: item.catalogueId,
        quantity: item.quantity,
      })),
      collection,
      billing: address(formData, 'billing', contact),
      collection_address: differentAddress ? address(formData, 'collection', contact) : null,
      company_website: formData.get('company_website') || '',
    })

    if (!result.ok) {
      setSubmission({ state: 'error', message: result.message })
      return
    }

    // A honeypot hit comes back ok without a reference; treat it like any other success.
    setBooking(result.data?.reference ? result.data : { reference: 'Received', requires_payment: false })
    clearCart()
    setSubmission({ state: 'sent', message: '' })
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
              Secure checkout
            </p>
            <h1 className='relative mt-4 text-3xl font-black tracking-tight text-white sm:text-4xl'>Complete your order</h1>
            <p className='relative mx-auto mt-2 max-w-2xl text-sm text-white/85'>Enter your billing information and review your collection before placing the order.</p>
          </header>

          {booking ? (
            <Confirmation booking={booking} />
          ) : items.length === 0 ? (
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
              {/* Honeypot: hidden from people, filled by bots. */}
              <input type='text' name='company_website' tabIndex={-1} autoComplete='off' aria-hidden='true' className='hidden' />

              {/* The voucher box was removed: the backend has no voucher support, so a code
                  typed there silently did nothing. `bookings.discount_pence` is ready if
                  vouchers are built later. */}
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
                    <SectionHeading icon={Wallet} step={2} title='Payment' hint='How your collection will be paid for.' />
                    <div className='rounded-2xl border-l-2 border-slate-100 bg-gradient-to-br from-slate-50 to-white p-4 pl-5 ring-1 ring-slate-200 sm:p-5'>
                      <p className='text-sm font-bold text-slate-800'>
                        {collection?.payment_option === 'arrival' ? 'Pay on arrival' : 'Pay now'}
                      </p>
                      <p className='mt-2 text-sm text-slate-600'>{paymentNote(collection?.payment_option)}</p>
                      <p className='mt-4 flex items-center gap-2 text-xs font-medium text-slate-500'>
                        <ShieldCheck className='size-3.5 text-emerald-600' />
                        We never ask for card details on this page.
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
                      <p className='text-xs text-slate-500'>Prices and any surcharges are confirmed when you place the order.</p>
                    </div>

                    <label id='terms' className='flex cursor-pointer items-start gap-3 rounded-xl bg-slate-50 p-3 text-xs leading-relaxed text-slate-600 ring-1 ring-slate-200 transition-colors duration-200 hover:bg-slate-100'>
                      <input type='checkbox' name='terms' required className='mt-0.5 size-4 shrink-0 accent-[#11224D]' />
                      <span>
                        I have read and agree to the <Link href='#terms' className='font-bold text-[#0497E2] underline decoration-[#0497E2]/40 underline-offset-2 transition-colors duration-200 hover:text-[#11224D] hover:decoration-[#11224D]'>terms and conditions</Link> and acknowledge the privacy policy.
                      </span>
                    </label>

                    <button type='submit' disabled={submission.state === 'sending'} className='group mt-5 flex min-h-13 w-full items-center justify-center gap-2 overflow-hidden rounded-full border border-[#0497E2] bg-[#0497E2] px-5 text-sm font-black uppercase tracking-wide text-white shadow-[0_14px_28px_-10px_rgba(17,34,77,0.6)] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_20px_36px_-10px_rgba(17,34,77,0.7)] focus-visible:outline-2 focus-visible:outline-offset-3 focus-visible:outline-[#0497E2] active:translate-y-0 disabled:cursor-wait disabled:opacity-70'>
                      {submission.state === 'sending'
                        ? <LoaderCircle className='size-5 animate-spin' />
                        : <ShieldCheck className='size-5 transition-transform duration-200 group-hover:scale-110' />}
                      {submission.state === 'sending' ? 'Placing order...' : 'Place order'}
                    </button>

                    {submission.state === 'error' && (
                      <p role='alert' className='mt-3 flex items-start justify-center gap-2 text-center text-xs font-semibold text-rose-600'>
                        <CircleAlert className='mt-px size-4 shrink-0' />
                        {submission.message}
                      </p>
                    )}

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
