import Image from 'next/image'
import Link from 'next/link'
import { ArrowUpRight, Clock3, Recycle, ShieldCheck, Users } from 'lucide-react'

const impactStats = [
  { value: '10+', label: 'Years of hands-on experience' },
  { value: '6', label: 'Days a week collecting waste' },
  { value: '24/7', label: 'Flexible customer support' },
]

export default function Impact() {
  return (
    <section className='relative overflow-hidden bg-[#F7F5EE] py-16 sm:py-20 lg:py-28'>
      <div aria-hidden='true' className='absolute -left-36 top-1/3 size-72 rounded-full border-[52px] border-[#0497E2]/5 sm:size-96' />
      <div aria-hidden='true' className='absolute -right-20 -top-20 size-64 rounded-full bg-[#F4B942]/15 blur-3xl' />

      <div className='relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8'>
        <div className='grid gap-7 lg:grid-cols-[1.35fr_0.65fr] lg:items-end lg:gap-12'>
          <div>
            <span className='inline-flex rounded-full border border-[#F4B942]/40 bg-white/70 px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-[#11224D] shadow-sm'>
              Our impact
            </span>
            <h2 className='mt-6 max-w-4xl text-3xl font-black leading-[1.03] tracking-[-0.045em] text-[#11224D] sm:text-4xl lg:text-4xl xl:text-4xl'>
              Cleaner spaces today.
              <span className='block bg-linear-to-r from-[#0497E2] to-[#F4B942] bg-clip-text text-transparent'>
                A better tomorrow.
              </span>
            </h2>
          </div>

          <p className='border-l-2 border-[#F4B942] pl-5 text-base leading-7 text-slate-600 sm:text-lg lg:mb-2'>
            Every collection is handled by an experienced local team committed to dependable service, careful work and responsible recycling.
          </p>
        </div>

        <div className='mt-12 grid gap-8 lg:mt-16 lg:grid-cols-[0.88fr_1.12fr] lg:items-center lg:gap-14'>
          <div className='group relative mx-auto w-full max-w-xl lg:mx-0'>
            <div aria-hidden='true' className='absolute -inset-3 rounded-[2.5rem_2.5rem_7rem_2.5rem] border border-[#F4B942]/35 transition-transform duration-700 group-hover:translate-x-1 group-hover:-translate-y-1 sm:-inset-4' />

            <div className='relative aspect-[4/5] overflow-hidden rounded-[2rem_2rem_6rem_2rem] bg-[#DDEAF1] shadow-2xl shadow-[#0492E8]/15'>
              <Image
                src='/images/rubbishRemoval.jpg'
                alt='A clean residential neighbourhood served by Waste Services'
                fill
                sizes='(max-width: 1023px) 100vw, 42vw'
                className='object-cover object-[52%_70%] transition-transform duration-1000 group-hover:scale-105'
              />
              <div className='absolute inset-0 bg-linear-to-t from-[#0492E8]/65 via-transparent to-white/5' />

              <div className='absolute inset-x-5 bottom-5 rounded-2xl border border-white/20 bg-[#0492E8]/75 p-4 text-[#102a4c] shadow-xl backdrop-blur-md sm:inset-x-7 sm:bottom-7 sm:p-5'>
                <div className='flex items-center gap-3'>
                  <span className='flex size-10 shrink-0 items-center justify-center rounded-full bg-[#F4B942] text-[#11224D]'>
                    <Users aria-hidden='true' className='size-5' />
                  </span>
                  <div>
                    <p className='font-bold'>A local team you can rely on</p>
                    <p className='mt-0.5 text-sm text-[#102a4c]/70'>Friendly, experienced and fully insured.</p>
                  </div>
                </div>
              </div>
            </div>

            <div aria-hidden='true' className='absolute -bottom-5 -right-2 grid grid-cols-4 gap-2 sm:-right-7'>
              {Array.from({ length: 16 }).map((_, index) => (
                <span key={index} className='size-1.5 rounded-full bg-[#0497E2]/45' />
              ))}
            </div>
          </div>

          <div>
            <div className='group flex items-center gap-4 border-b border-[#0492E8]/15 pb-8 sm:gap-6'>
              <span className='flex size-14 shrink-0 items-center justify-center rounded-2xl bg-[#0497E2]/10 text-[#0497E2] transition-all duration-500 group-hover:rotate-6 group-hover:bg-[#0497E2] group-hover:text-white sm:size-16'>
                <Recycle aria-hidden='true' className='size-7 transition-transform duration-700 group-hover:rotate-180 sm:size-8' />
              </span>
              <div>
                <p className='text-3xl font-black tracking-[-0.05em] text-[#11224D] sm:text-4xl lg:text-4xl'>95%</p>
                <p className='mt-1 max-w-md text-sm leading-6 text-slate-600 sm:text-base'>
                  of the waste we collect is recycled wherever possible, helping reduce what goes to landfill.
                </p>
              </div>
            </div>

            <div className='grid divide-y divide-[#0492E8]/15 border-b border-[#0492E8]/15 sm:grid-cols-3 sm:divide-x sm:divide-y-0'>
              {impactStats.map((stat) => (
                <div key={stat.value} className='group px-1 py-6 first:pl-0 sm:px-6 sm:first:pl-0 sm:last:pr-0'>
                  <p className='text-3xl font-black tracking-tight text-[#11224D] transition-colors duration-300 group-hover:text-[#0497E2] sm:text-4xl'>
                    {stat.value}
                  </p>
                  <p className='mt-2 max-w-36 text-sm leading-5 text-slate-500'>{stat.label}</p>
                </div>
              ))}
            </div>

            <div className='mt-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between'>
              <div className='flex items-center gap-3 text-sm text-slate-600'>
                <span className='flex size-10 shrink-0 items-center justify-center rounded-full bg-[#0492E8] text-[#102a4c]'>
                  <ShieldCheck aria-hidden='true' className='size-5' />
                </span>
                <span>Fully insured. Responsible from collection to recycling.</span>
              </div>

              <Link href='/contactUs' className='group inline-flex shrink-0 items-center gap-3 self-start rounded-full bg-[#0492E8] px-5 py-3 text-sm font-bold text-[#102a4c] transition-all duration-300 hover:-translate-y-1 hover:bg-[#0497E2] hover:shadow-xl focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0492E8] sm:self-auto'>
                Meet our team
                <ArrowUpRight aria-hidden='true' className='size-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5' />
              </Link>
            </div>

            <div className='mt-5 flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-[#11224D]/55'>
              <Clock3 aria-hidden='true' className='size-4 text-[#F4B942]' />
              Flexible help whenever you need it
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
