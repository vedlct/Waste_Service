'use client'

import { useRouter } from 'next/navigation'
import { useState } from 'react'
import { ArrowRight, CircleCheck, Info, MapPin, Trash2 } from 'lucide-react'
import { coveredArea } from '@/lib/coverage'

const OPTIONS = [
  { id: 'man-van', label: 'A van load of rubbish' },
  { id: 'furniture', label: 'Furniture & sofas' },
  { id: 'garden-items', label: 'Garden waste' },
  { id: 'kitchen-appliances', label: 'Appliances & white goods' },
  { id: 'office-items', label: 'Office clearance' },
  { id: 'show-all', label: 'Something else' },
]

// The instant price box in the home hero, as on most UK clearance sites: pick what needs
// clearing, check the postcode is covered, then go to the prices page.
export default function QuickQuote({ coverage, phone, bare = false }) {
  const router = useRouter()
  const [service, setService] = useState(OPTIONS[0].id)
  const [postcode, setPostcode] = useState('')
  const [checked, setChecked] = useState(null)

  const checkPostcode = () => {
    if (!postcode.trim()) return null
    const result = { postcode: postcode.trim().toUpperCase(), area: coveredArea(coverage, postcode) }
    setChecked(result)
    return result
  }

  const submit = (event) => {
    event.preventDefault()
    checkPostcode()
    router.push(`/prices?service=${service}`)
  }

  return (
    <form onSubmit={submit} className={`min-w-0 ${bare ? 'text-ink' : 'rounded-2xl border border-line bg-white p-4 text-ink shadow-[0_24px_50px_-28px_rgba(15,37,87,0.45)] sm:p-5'}`}>
      <p className='font-display text-xl font-bold uppercase tracking-wide text-ink'>Get an instant price</p>
      <div className='mt-3 grid gap-3 sm:grid-cols-2'>
        <label className='relative block min-w-0'>
          <span className='sr-only'>What needs clearing?</span>
          <Trash2 className='pointer-events-none absolute left-3.5 top-1/2 size-5 -translate-y-1/2 text-brand-600' aria-hidden='true' />
          <select
            value={service}
            onChange={(event) => setService(event.target.value)}
            className='h-13 min-w-0 max-w-full w-full appearance-none rounded-lg border border-line bg-mist pl-11 pr-4 text-[15px] font-semibold outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-200'
          >
            {OPTIONS.map((option) => <option key={option.id} value={option.id}>{option.label}</option>)}
          </select>
        </label>
        <label className='relative block min-w-0'>
          <span className='sr-only'>Your postcode</span>
          <MapPin className='pointer-events-none absolute left-3.5 top-1/2 size-5 -translate-y-1/2 text-brand-600' aria-hidden='true' />
          <input
            value={postcode}
            onChange={(event) => { setPostcode(event.target.value); setChecked(null) }}
            onBlur={checkPostcode}
            placeholder='Your postcode'
            autoComplete='postal-code'
            maxLength={8}
            className='h-13 min-w-0 max-w-full w-full rounded-lg border border-line bg-mist pl-11 pr-4 text-[15px] font-semibold uppercase outline-none placeholder:normal-case placeholder:font-normal focus:border-brand-500 focus:ring-2 focus:ring-brand-200'
          />
        </label>
        <button type='submit' className='group inline-flex h-13 items-center justify-center gap-2 rounded-lg bg-brand-600 px-6 font-bold text-white transition-colors hover:bg-brand-700 sm:col-span-2'>
          See prices
          <ArrowRight className='size-5 transition-transform group-hover:translate-x-1' aria-hidden='true' />
        </button>
      </div>

      <p className='mt-3 flex min-h-5 items-center gap-2 text-sm' role='status'>
        {checked?.area && (
          <>
            <CircleCheck className='size-4 shrink-0 text-emerald-600' aria-hidden='true' />
            <span><strong>Good news:</strong> we collect in {checked.postcode} ({checked.area}).</span>
          </>
        )}
        {checked && !checked.area && (
          <>
            <Info className='size-4 shrink-0 text-brand-600' aria-hidden='true' />
            <span>{checked.postcode} is outside our usual area. Call {phone} and we&apos;ll see what we can do.</span>
          </>
        )}
        {!checked && <span className='text-slate-500'>Covering Portsmouth, Southsea, Havant, Fareham, Gosport and nearby.</span>}
      </p>
    </form>
  )
}
