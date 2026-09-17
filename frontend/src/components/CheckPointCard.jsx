import { Check } from 'lucide-react'

export default function CheckPointCard({ children, index }) {
  return (
    <li className='group relative flex min-h-20 min-w-0 cursor-pointer items-center gap-4 overflow-hidden rounded-2xl border border-[#11224D]/10 bg-white px-4 py-4 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-[#0497E2]/30 hover:shadow-xl hover:shadow-[#11224D]/10 sm:px-5'>
      <div className='absolute left-0 top-0 h-full w-1 bg-[#0497E2] transition-all duration-500 group-hover:w-2' />

      <div className='flex size-12 shrink-0 items-center justify-center rounded-xl bg-[#11224D]/5 transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 group-hover:bg-[#11224D]'>
        <Check
          aria-hidden='true'
          className='size-6 text-[#11224D] transition-all duration-500 group-hover:scale-125 group-hover:text-white'
          strokeWidth={2.5}
        />
      </div>

      <div className='min-w-0 flex-1'>
        <div className='mb-1 flex items-center gap-2'>
          <span className='text-xs font-bold tracking-widest text-[#0497E2]'>
            {String(index + 1).padStart(2, '0')}
          </span>
          <span className='h-px w-5 bg-[#0497E2]/30 transition-all duration-500 group-hover:w-10' />
        </div>

        <p className='text-base font-bold leading-snug text-[#11224D] transition-colors duration-300 group-hover:text-[#0497E2] sm:text-lg'>
          {children}
        </p>
      </div>
    </li>
  )
}
