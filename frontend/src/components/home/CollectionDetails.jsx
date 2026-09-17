'use client'

import { CalendarDays, ClipboardList, Clock3, CreditCard, TriangleAlert } from 'lucide-react'

const fieldClass = 'mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3.5 text-sm text-[#11224D] outline-none transition duration-200 placeholder:text-slate-400 hover:border-sky-300 focus:border-[#0497E2] focus:bg-white focus:ring-4 focus:ring-[#0497E2]/10'

export default function CollectionDetails({ onSubmit, saturdayCollection, setSaturdayCollection, collectionDate, setCollectionDate, paymentOption, setPaymentOption, notice, setNotice, accessConfirmed, setAccessConfirmed, restrictedAccess, setRestrictedAccess }) {
  const today = new Date()
  const minimumDate = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`

  const selectSaturday = (checked) => {
    setSaturdayCollection(checked)
    if (!checked) return
    const saturday = new Date()
    const daysUntilSaturday = (6 - saturday.getDay() + 7) % 7
    saturday.setDate(saturday.getDate() + daysUntilSaturday)
    setCollectionDate(`${saturday.getFullYear()}-${String(saturday.getMonth() + 1).padStart(2, '0')}-${String(saturday.getDate()).padStart(2, '0')}`)
  }

  return (
    <form id='collection-details' onSubmit={onSubmit} className='mt-10 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-[#F8FBFD] shadow-[0_20px_60px_rgba(17,34,77,0.08)] sm:rounded-[2rem]'>
      <div className='relative overflow-hidden border-b border-slate-200 bg-white px-5 py-6 sm:px-8 sm:py-8'>
        <div aria-hidden='true' className='absolute inset-y-0 left-0 w-1.5 bg-linear-to-b from-[#0497E2] to-[#F4B942]' />
        <div aria-hidden='true' className='absolute -right-16 -top-20 size-52 rounded-full bg-[#0497E2]/6' />
        <p className='relative text-[11px] font-black uppercase tracking-[0.22em] text-[#0497E2]'>One last step</p>
        <h3 className='relative mt-2 text-2xl font-black tracking-[-0.025em] text-[#11224D] sm:text-3xl'>Collection details</h3>
        <p className='relative mt-2 max-w-3xl text-sm leading-6 text-slate-600'>Tell us how to plan your collection. Required fields are marked with an asterisk.</p>
      </div>
      <div className='grid gap-4 p-4 sm:gap-5 sm:p-6 lg:grid-cols-2 lg:p-8'>
        <label className='rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5 lg:col-span-2'>
          <span className='flex items-center gap-2 text-sm font-bold text-slate-800'><ClipboardList className='size-4 text-[#1687BE]' />Additional large items</span>
          <textarea name='largeItems' rows={3} placeholder='List any large items not included above' className={fieldClass} />
          <span className='mt-1.5 block text-xs text-slate-500'>Some items may require an extra charge. We will contact you first if this applies.</span>
        </label>

        <fieldset className={`rounded-2xl border bg-white p-4 shadow-sm transition duration-200 sm:p-5 ${accessConfirmed ? 'border-[#0497E2] ring-4 ring-[#0497E2]/8' : 'border-slate-200 hover:border-sky-300'}`}>
          <legend className='px-1 text-base font-extrabold text-slate-800'>Property access surcharge *</legend>
          <label className='mt-3 flex cursor-pointer items-start gap-3 rounded-xl bg-slate-50 p-3.5 transition hover:bg-[#F0F8FD]'>
            <input name='accessConfirmed' type='checkbox' checked={accessConfirmed} onChange={(event) => { setAccessConfirmed(event.target.checked); if (!event.target.checked) setRestrictedAccess('') }} className='mt-0.5 size-5 shrink-0 accent-[#249BD3]' />
            <span><span className='block text-sm font-bold text-slate-800'>I understand difficult access may cost extra</span><span className='mt-1 block text-xs leading-relaxed text-slate-500'>This can include upper-floor flats without lifts, restricted parking or long carrying distances.</span></span>
          </label>
        </fieldset>

        {accessConfirmed && (
          <fieldset className='rounded-2xl border border-amber-200 bg-amber-50/50 p-4 shadow-sm sm:p-5'>
            <legend className='flex items-center gap-2 px-1 text-base font-extrabold text-slate-800'><TriangleAlert className='size-4 text-[#1687BE]' />Restricted access details *</legend>
            <p className='mt-2 text-sm text-slate-600'>Are there access problems such as no lift or parking restrictions?</p>
            <div className='mt-3 grid grid-cols-2 gap-3'>
              {['yes', 'no'].map((answer) => <label key={answer} className={`flex cursor-pointer items-center gap-2 rounded-xl border px-3 py-3 text-sm font-bold capitalize transition ${restrictedAccess === answer ? 'border-[#0497E2] bg-white text-[#11224D] ring-2 ring-[#0497E2]/10' : 'border-slate-200 bg-white/70 text-slate-600 hover:border-sky-300'}`}><input type='radio' name='restrictedAccess' value={answer} checked={restrictedAccess === answer} onChange={() => setRestrictedAccess(answer)} required className='size-5 accent-[#0497E2]' />{answer}</label>)}
            </div>
            {restrictedAccess === 'yes' && <label className='mt-4 block'><span className='text-sm font-bold text-slate-700'>Please list your property restrictions *</span><textarea name='accessRestrictions' rows={4} required placeholder='For example: no lift, limited parking or a long carrying distance' className={fieldClass} /></label>}
          </fieldset>
        )}

        <fieldset className={`rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5 ${accessConfirmed ? 'lg:col-span-2' : ''}`}>
          <legend className='px-1 text-base font-extrabold text-slate-800'>Collection date *</legend>
          <div className='mt-3 grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(14rem,0.85fr)] sm:items-end'>
            <label className='block'>
              <span className='flex items-center gap-2 text-sm font-bold text-slate-600'><CalendarDays className='size-4 text-[#1687BE]' />Preferred date</span>
              <input name='collectionDate' type='date' min={minimumDate} value={collectionDate} onChange={(event) => { setCollectionDate(event.target.value); setSaturdayCollection(false) }} required className={`${fieldClass} min-h-11 py-2`} />
            </label>
            <label className={`flex min-h-12 cursor-pointer items-center justify-between gap-3 rounded-2xl border p-3.5 transition ${saturdayCollection ? 'border-[#0497E2] bg-[#EAF6FD] ring-2 ring-[#0497E2]/10' : 'border-slate-200 bg-slate-50 hover:border-sky-300'}`}>
              <span className='flex items-center gap-2 text-sm font-bold text-slate-800'><input name='saturdayCollection' type='checkbox' checked={saturdayCollection} onChange={(event) => selectSaturday(event.target.checked)} className='size-5 accent-[#249BD3]' />Saturday collection</span>
              <span className='shrink-0 text-sm font-extrabold text-[#1687BE]'>+ £50.00</span>
            </label>
          </div>
        </fieldset>

        <fieldset className='rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5 lg:col-span-2'>
          <legend className='flex items-center gap-2 text-base font-extrabold text-slate-800'><CreditCard className='size-4 text-[#1687BE]' />Payment option *</legend>
          <div className='mt-3 grid gap-3 sm:grid-cols-2'>
            {[{ id: 'now', title: 'Pay now', text: 'Pay the full amount securely online.' }, { id: 'arrival', title: 'Pay on arrival', text: 'Pay the £25 callout fee now and the balance after collection.' }].map((option) => (
              <label key={option.id} className={`cursor-pointer rounded-2xl border p-4 transition duration-200 sm:p-5 ${paymentOption === option.id ? 'border-[#0497E2] bg-[#F0F8FD] shadow-sm ring-2 ring-[#0497E2]/10' : 'border-slate-200 bg-slate-50/60 hover:border-sky-300 hover:bg-white'}`}>
                <span className='flex items-start gap-3'><input type='radio' name='paymentOption' value={option.id} checked={paymentOption === option.id} onChange={() => setPaymentOption(option.id)} className='mt-0.5 size-5 accent-[#249BD3]' /><span><span className='block text-sm font-extrabold text-slate-800'>{option.title}</span><span className='mt-1 block text-xs leading-relaxed text-slate-500'>{option.text}</span></span></span>
              </label>
            ))}
          </div>
        </fieldset>

        <fieldset className='rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5 lg:col-span-2'>
          <legend className='flex items-center gap-2 text-base font-extrabold text-slate-800'><Clock3 className='size-4 text-[#1687BE]' />Collection notice *</legend>
          <p className='mt-1 text-xs leading-relaxed text-slate-500'>We cannot offer a specific arrival time. The team will call before arriving.</p>
          <div className='mt-3 grid grid-cols-2 gap-3 sm:flex'>
            {['30', '60'].map((minutes) => <label key={minutes} className={`flex cursor-pointer items-center justify-center gap-2 rounded-xl border px-4 py-3 text-sm font-bold transition sm:justify-start ${notice === minutes ? 'border-[#0497E2] bg-[#EAF6FD] text-[#1479A9] ring-2 ring-[#0497E2]/10' : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-sky-300'}`}><input type='radio' name='notice' value={minutes} checked={notice === minutes} onChange={() => setNotice(minutes)} className='size-4 accent-[#249BD3]' />{minutes} minutes</label>)}
          </div>
        </fieldset>

        <label className='rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5 lg:col-span-2'><span className='text-sm font-bold text-slate-800'>Collection notes</span><textarea name='collectionNotes' rows={3} placeholder='For example: gate code, floor number, parking or access details' className={fieldClass} /></label>
      </div>
    </form>
  )
}
