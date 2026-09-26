import { Check } from 'lucide-react'

export default function CheckPointCard({ children, index }) {
  return (
    <li className='group relative flex min-h-20 min-w-0 cursor-pointer items-center gap-4 overflow-hidden rounded-2xl border border-ink/10 bg-white px-4 py-4 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-brand-600/30 hover:shadow-xl hover:shadow-ink/10 sm:px-5'>
      <div className='absolute left-0 top-0 h-full w-1 bg-brand-600 transition-all duration-500 group-hover:w-2' />

      <div className='flex size-12 shrink-0 items-center justify-center rounded-xl bg-brand-900/5 transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 group-hover:bg-brand-700'>
        <Check
          aria-hidden='true'
          className='size-6 text-ink transition-all duration-500 group-hover:scale-125 group-hover:text-white'
          strokeWidth={2.5}
        />
      </div>

      <div className='min-w-0 flex-1'>
        <div className='mb-1 flex items-center gap-2'>
          <span className='text-xs font-bold tracking-widest text-brand-600'>
            {String(index + 1).padStart(2, '0')}
          </span>
          <span className='h-px w-5 bg-brand-600/30 transition-all duration-500 group-hover:w-10' />
        </div>

        <p className='text-base font-bold leading-snug text-ink transition-colors duration-300 group-hover:text-brand-600 sm:text-lg'>
          {children}
        </p>
      </div>
    </li>
  )
}
