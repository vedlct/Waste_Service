import Image from 'next/image'
import Link from 'next/link'
import { ArrowRight, Check, Recycle, ShieldCheck, Truck, Users } from 'lucide-react'

const benefits = [
  'Trusted rubbish waste removal & skips',
  "Reliable local 'Man & Van' rubbish clearance",
  'Fully insured with Waste Carriers Licence',
  '95% of all waste successfully recycled',
  'Clearance teams working 6 days a week',
  'Covering the Home Counties',
  'Wait & Load branded vehicles',
  'Friendly & reliable collection team',
  'Best local rubbish removal & skip prices',
]

export default function RubbishClearance() {
  return (
    <section id='rubbish-clearance' className='relative overflow-hidden bg-[#FBF8F1] py-16 sm:py-20 lg:py-28'>
      <div aria-hidden='true' className='absolute -left-28 top-1/4 size-72 rounded-full border-[54px] border-[#F4B942]/8' />
      <div aria-hidden='true' className='absolute -right-24 top-12 size-72 rounded-full bg-[#0497E2]/8 blur-3xl' />

      <div className='relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8'>
        <div className='grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-end lg:gap-16'>
          <div>
            <span className='inline-flex rounded-full border border-[#F4B942]/40 bg-white/80 px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-[#11224D] shadow-sm'>
              Clearance with care
            </span>
            <h2 className='mt-6 max-w-5xl text-3xl font-black leading-[1.03] tracking-[-0.045em] text-[#11224D] sm:text-4xl lg:text-4xl xl:text-4xl'>
              We clear the clutter.
              <span className='block bg-linear-to-r from-[#0497E2] to-[#F4B942] bg-clip-text text-transparent'>You get your space back.</span>
            </h2>
          </div>

          <p className='border-l-2 border-[#F4B942] pl-5 text-base leading-7 text-slate-600 sm:text-lg'>
            Waste Services Ltd provides trusted domestic and commercial rubbish clearance throughout the Home Counties, with experienced teams who manage every collection safely and efficiently.
          </p>
        </div>

        <div className='mt-12 grid gap-5 lg:mt-16 lg:grid-cols-12 lg:grid-rows-[18rem_18rem]'>
          <article className='group relative min-h-[28rem] overflow-hidden rounded-[2.25rem] bg-[#0492E8] shadow-2xl shadow-[#0492E8]/15 lg:col-span-7 lg:row-span-2 lg:min-h-0'>
            <Image
              src='/images/service2.jpg'
              alt='Clearance team removing waste from a local property'
              fill
              sizes='(max-width: 1023px) 100vw, 58vw'
              className='object-cover object-center transition-transform duration-1000 group-hover:scale-105'
            />
            <div className='absolute inset-0 bg-linear-to-t from-[#0492E8] via-[#0492E8]/20 to-transparent' />
            <div className='absolute inset-x-0 bottom-0 p-6 text-[#102a4c] sm:p-8 lg:p-10'>
              <span className='text-xs font-black uppercase tracking-[0.2em] text-[#8FD3F4]'>01 Â· Local expertise</span>
              <h3 className='mt-3 max-w-xl text-3xl font-black leading-tight sm:text-4xl'>Experienced people, careful work.</h3>
              <p className='mt-4 max-w-2xl text-sm leading-6 text-[#102a4c]/75 sm:text-base'>
                Our staff bring years of practical experience to every job and complete all work in line with Health and Safety guidelines. We do the lifting, loading and final sweep so your space is left ready to use.
              </p>
            </div>
            <span className='absolute right-6 top-6 flex size-12 items-center justify-center rounded-full border border-white/20 bg-white/10 text-[#102a4c] backdrop-blur transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 group-hover:bg-[#0497E2]'>
              <Users aria-hidden='true' className='size-5' />
            </span>
          </article>

          <article className='group grid overflow-hidden rounded-[2.25rem] border border-[#0492E8]/10 bg-white shadow-sm transition-all duration-500 hover:-translate-y-1 hover:shadow-xl lg:col-span-5 sm:grid-cols-[0.9fr_1.1fr] lg:grid-cols-[0.92fr_1.08fr]'>
            <div className='relative min-h-56 overflow-hidden sm:min-h-0'>
              <Image
                src='/images/service1.jpg'
                alt='Recyclable cardboard prepared for processing'
                fill
                sizes='(max-width: 639px) 100vw, (max-width: 1023px) 45vw, 19vw'
                className='object-cover transition-transform duration-700 group-hover:scale-110'
              />
            </div>
            <div className='flex flex-col justify-center p-6'>
              <span className='text-xs font-black uppercase tracking-[0.18em] text-[#0497E2]'>02 Â· Responsible</span>
              <h3 className='mt-3 text-2xl font-black leading-tight text-[#11224D]'>Waste handled the right way.</h3>
              <p className='mt-3 text-sm leading-6 text-slate-600'>
                As an approved waste carrier, we aim to recycle upwards of 95% of everything collected and reduce unnecessary landfill.
              </p>
            </div>
          </article>

          <article className='group relative min-h-80 overflow-hidden rounded-[2.25rem] bg-[#0492E8] shadow-sm transition-all duration-500 hover:-translate-y-1 hover:shadow-xl lg:col-span-5 lg:min-h-0'>
            <Image
              src='/images/service3.jpg'
              alt='Rubbish loaded safely into a collection vehicle'
              fill
              sizes='(max-width: 1023px) 100vw, 42vw'
              className='object-cover object-[50%_70%] transition-transform duration-700 group-hover:scale-105'
            />
            <div className='absolute inset-0 bg-linear-to-r from-[#0492E8]/90 via-[#0492E8]/45 to-transparent' />
            <div className='absolute inset-y-0 left-0 flex max-w-sm flex-col justify-center p-6 text-[#102a4c] sm:p-8'>
              <span className='text-xs font-black uppercase tracking-[0.18em] text-[#F4B942]'>03 Â· Dependable</span>
              <h3 className='mt-3 text-2xl font-black leading-tight sm:text-3xl'>Ready when your collection is.</h3>
              <p className='mt-3 text-sm leading-6 text-[#102a4c]/75'>
                Branded vehicles and friendly teams work six days a week across the Home Counties.
              </p>
            </div>
            <span className='absolute bottom-6 right-6 flex size-11 items-center justify-center rounded-full bg-white text-[#11224D] transition-all duration-500 group-hover:-translate-y-1 group-hover:bg-[#F4B942]'>
              <Truck aria-hidden='true' className='size-5' />
            </span>
          </article>
        </div>
{/* 
        <div className='mt-14 grid gap-8 lg:mt-20 lg:grid-cols-[0.72fr_1.28fr] lg:gap-14'>
          <div>
            <span className='text-xs font-black uppercase tracking-[0.2em] text-[#0497E2]'>Why choose us</span>
            <h3 className='mt-4 text-3xl font-black leading-tight tracking-[-0.03em] text-[#11224D] sm:text-4xl lg:text-4xl'>A clearance service built around reliability.</h3>
            <p className='mt-5 text-base leading-7 text-slate-600'>
              We are fully insured, licensed and committed to safe, economical rubbish removal for homes and businesses. Our team manages the hard work from arrival through to responsible disposal.
            </p>

            <a href='tel:02082266477' className='group mt-7 inline-flex items-center gap-3 rounded-full bg-[#0492E8] px-5 py-3.5 text-sm font-black text-[#102a4c] shadow-lg transition-all duration-300 hover:-translate-y-1 hover:bg-[#0497E2] hover:shadow-xl focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#0492E8]'>
              Call 020 8226 6477
              <ArrowRight aria-hidden='true' className='size-4 transition-transform duration-300 group-hover:translate-x-1' />
            </a>
          </div>

          <div className='grid grid-cols-1 gap-3 sm:grid-cols-2'>
            {benefits.map((benefit, index) => (
              <div key={benefit} className='group flex min-h-24 items-center gap-4 rounded-2xl border border-[#0492E8]/10 bg-white p-4 shadow-sm transition-all duration-400 hover:-translate-y-1 hover:border-[#0497E2]/30 hover:shadow-lg sm:p-5'>
                <span className='flex size-10 shrink-0 items-center justify-center rounded-xl bg-[#0497E2]/10 text-[#0497E2] transition-all duration-400 group-hover:rotate-6 group-hover:scale-110 group-hover:bg-[#0497E2] group-hover:text-white'>
                  <Check aria-hidden='true' className='size-5' strokeWidth={3} />
                </span>
                <div className='min-w-0'>
                  <span className='text-[10px] font-black tracking-[0.18em] text-[#11224D]/35'>{String(index + 1).padStart(2, '0')}</span>
                  <p className='mt-1 text-sm font-bold leading-snug text-[#11224D] transition-colors duration-300 group-hover:text-[#0497E2] sm:text-base'>{benefit}</p>
                </div>
              </div>
            ))}
          </div>
        </div> */}

        <div className='mt-12 flex flex-col gap-5 rounded-[2rem] bg-[#E5F2F8] p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8'>
          <div className='flex items-start gap-4'>
            <span className='flex size-12 shrink-0 items-center justify-center rounded-2xl bg-[#0492E8] text-[#102a4c]'>
              <ShieldCheck aria-hidden='true' className='size-6' />
            </span>
            <div>
              <p className='text-xl font-black text-[#11224D]'>Fully insured and licensed to carry waste.</p>
              <p className='mt-1 text-sm leading-6 text-slate-600'>Clear service, responsible recycling and a team you can trust.</p>
            </div>
          </div>
          <Link href='/prices?service=man-van' className='group inline-flex shrink-0 items-center justify-center gap-3 rounded-full bg-white px-5 py-3 text-sm font-black text-[#11224D] shadow-sm transition-all duration-300 hover:-translate-y-1 hover:bg-[#F4B942] hover:shadow-lg focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#0492E8]'>
            View collection prices
            <ArrowRight aria-hidden='true' className='size-4 transition-transform duration-300 group-hover:translate-x-1' />
          </Link>
        </div>
      </div>
    </section>
  )
}
