import { BadgeCheck, Clock3, Recycle, ShieldCheck, Users } from 'lucide-react'

const points = [
  { icon: Clock3, label: 'Same-day clearance', text: 'Often available' },
  { icon: ShieldCheck, label: 'Fully insured', text: 'Peace of mind' },
  { icon: BadgeCheck, label: 'Licensed carrier', text: 'Waste handled legally' },
  { icon: Recycle, label: 'Up to 95% recycled', text: 'Kept out of landfill' },
  { icon: Users, label: 'Friendly local team', text: 'Based in Portsmouth' },
]

// The trust strip, a white card overlapping the bottom of the hero.
export default function TrustBand() {
  return (
    <section aria-label='Why customers choose us' className='relative z-10 bg-mist px-4 sm:px-6 lg:-mt-14 lg:bg-transparent lg:px-8'>
      <ul className='mx-auto grid max-w-7xl grid-cols-2 gap-px overflow-hidden rounded-2xl border border-line bg-line shadow-[0_24px_60px_-30px_rgba(15,37,87,0.35)] sm:grid-cols-3 lg:grid-cols-5'>
        {points.map(({ icon: Icon, label, text }, index) => (
          <li key={label} className={`group flex items-center gap-3 bg-white px-3.5 py-4 sm:px-5 sm:py-5 transition-colors hover:bg-brand-50 ${index === points.length - 1 ? 'col-span-2 sm:col-span-1' : ''}`}>
            <span className='grid size-9 shrink-0 place-items-center rounded-lg sm:size-11 sm:rounded-xl bg-linear-to-br from-brand-500 to-brand-700 text-white shadow-md shadow-brand-600/25 transition-transform group-hover:-translate-y-0.5'>
              <Icon className='size-4 sm:size-5' aria-hidden='true' />
            </span>
            <span className='min-w-0'>
              <span className='block font-display text-[15px] font-bold uppercase leading-tight text-ink sm:text-lg'>{label}</span>
              <span className='hidden text-xs text-slate-500 sm:block'>{text}</span>
            </span>
          </li>
        ))}
      </ul>
    </section>
  )
}
