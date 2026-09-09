import Image from 'next/image'
import Link from 'next/link'
import { ArrowDown, ArrowRight, Check, Recycle, ShieldCheck, Truck } from 'lucide-react'

const assurances = [
  { icon: Recycle, value: '95%', label: 'recycled' },
  { icon: Truck, value: 'Next day', label: 'collection' },
  { icon: ShieldCheck, value: 'Fully', label: 'insured' },
]

export default function Hero() {
  return (
    <section className='relative flex min-h-svh w-full items-center overflow-hidden bg-sky-50 text-[#102a4c]'>
      <Image
        src='/images/Spinnaker_Tower.jpg'
        alt='Colour-coded waste and recycling bins in a green public space'
        fill
        sizes='100vw'
        className='object-cover object-center sm:object-center'
        priority
      />



      <div className='relative mx-auto w-full max-w-7xl px-4 pb-10 pt-28 sm:px-6 sm:pb-12 sm:pt-32 lg:px-8 lg:pb-14 lg:pt-36'>
        <div className='max-w-3xl rounded-[2rem] border border-sky-200 bg-white/80 p-6 shadow-2xl shadow-black/30 backdrop-blur-xl sm:p-8 lg:p-10'>
          <div className='inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-[#102a4c] shadow-lg backdrop-blur-md transition-all duration-300 hover:border-[#F4B942]/60 hover:bg-white/15'>
            <span className='size-2 rounded-full bg-[#F4B942] shadow-[0_0_0_5px_rgba(244,185,66,0.14)]' />
            Local waste collection specialists
          </div>

          <h1 className='mt-6 text-3xl font-black leading-[1.02] tracking-[-0.045em] sm:text-4xl'>
            Waste gone.
            <span className='block text-[#68C4EF]'>Space restored.</span>
          </h1>

          <p className='mt-6 max-w-2xl text-base font-semibold leading-7 text-[#102a4c]/90 sm:text-lg lg:text-xl'>
            Professional rubbish clearance for homes and businesses throughout London and the Home Countries.
          </p>
          <p className='mt-3 max-w-2xl text-sm leading-6 text-[#102a4c]/65 sm:text-base'>
            From garage junk and old furniture to complete property clearances, our experienced team handles the lifting, loading and responsible disposal.
          </p>

          <div className='mt-8 flex flex-col gap-3 sm:flex-row sm:items-center'>
            <Link href='/#prices' className='group inline-flex w-full items-center justify-center gap-3 rounded-full bg-[#F4B942] px-6 py-4 text-sm font-black text-[#11224D] shadow-xl shadow-black/15 transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-2xl focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white sm:w-auto sm:text-base'>
              Check prices &amp; book
              <ArrowRight aria-hidden='true' className='size-5 transition-transform duration-300 group-hover:translate-x-1' />
            </Link>

            <Link href='/contactUs' className='group inline-flex w-full items-center justify-center gap-3 rounded-full border border-white/30 bg-white/10 px-6 py-4 text-sm font-black text-[#102a4c] backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:border-white hover:bg-white hover:text-[#11224D] hover:shadow-xl focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white sm:w-auto sm:text-base'>
              Talk to our team
              <ArrowRight aria-hidden='true' className='size-5 transition-transform duration-300 group-hover:translate-x-1' />
            </Link>
          </div>

          <div className='mt-9 grid max-w-2xl grid-cols-1 gap-2.5 min-[420px]:grid-cols-3 sm:gap-3'>
            {assurances.map((item) => {
              const Icon = item.icon

              return (
                <div key={item.value} className='group flex items-center gap-3 rounded-2xl border border-white/15 bg-white/80 p-3 shadow-sm backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:border-white/35 hover:bg-white/95 hover:shadow-lg sm:p-4'>
                  <span className='flex size-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-[#68C4EF] transition-all duration-300 group-hover:rotate-6 group-hover:bg-white group-hover:text-[#11224D]'>
                    <Icon aria-hidden='true' className='size-5' />
                  </span>
                  <p className='min-w-0 leading-tight'>
                    <strong className='block text-sm font-black text-[#102a4c] sm:text-base'>{item.value}</strong>
                    <span className='text-xs text-[#102a4c]/55 sm:text-sm'>{item.label}</span>
                  </p>
                </div>
              )
            })}
          </div>
        </div>

        {/* <div className='mt-8 flex items-center justify-between gap-5 border-t border-white/15 pt-5 sm:mt-10'>
          <p className='inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.16em] text-[#102a4c]/55 sm:text-sm'>
            <Check aria-hidden='true' className='size-4 text-[#F4B942]' />
            Friendly team Â· Clear pricing Â· Responsible disposal
          </p>
          <Link href='/#prices' aria-label='Scroll to prices and booking' className='group hidden size-11 shrink-0 items-center justify-center rounded-full border border-white/25 bg-white/10 text-[#102a4c] backdrop-blur transition-all duration-300 hover:-translate-y-1 hover:border-white hover:bg-white hover:text-[#11224D] sm:flex'>
            <ArrowDown aria-hidden='true' className='size-5 transition-transform duration-300 group-hover:translate-y-1' />
          </Link>
        </div> */}
      </div>
    </section>
  )
}