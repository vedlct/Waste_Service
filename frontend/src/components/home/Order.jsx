import Image from 'next/image'
import Link from 'next/link'
import { ArrowUpRight, Check, Recycle, ShieldCheck } from 'lucide-react'

const services = [
  { id: 'man-van', image: '/images/option1.jpg', title: 'Man & Van', eyebrow: 'Flexible loads' },
  { id: 'sofas', image: '/images/option2.jpg', title: 'Sofas', eyebrow: 'Bulky furniture' },
  { id: 'mattress-bed', image: '/images/option3.jpg', title: 'Mattress & Bed', eyebrow: 'Bedroom items' },
  { id: 'furniture', image: '/images/option4.jpg', title: 'Furniture', eyebrow: 'Home clearance' },
  { id: 'kitchen-appliances', image: '/images/option5.jpg', title: 'Kitchen Appliances', eyebrow: 'Large appliances' },
  { id: 'fridge-freezer', image: '/images/option6.jpg', title: 'Fridge & Freezer', eyebrow: 'Responsible disposal' },
  { id: 'electrical-it', image: '/images/option7.jpg', title: 'Electrical & IT', eyebrow: 'Electrical waste' },
  { id: 'garden-items', image: '/images/option8.jpg', title: 'Garden Items', eyebrow: 'Outdoor clearance' },
  { id: 'hazardous-waste', image: '/images/option9.jpg', title: 'Hazardous Waste', eyebrow: 'Specialist handling' },
  { id: 'office-items', image: '/images/option10.jpg', title: 'Office Items', eyebrow: 'Workplace clearance' },
  { id: 'commercial-items', image: '/images/option11.jpg', title: 'Commercial Items', eyebrow: 'Business waste' },
  { id: 'bins-wheelie-bins', image: '/images/option12.jpg', title: 'Bins', eyebrow: 'Everyday waste' },
  { id: 'show-all', image: '/images/option13.jpg', title: 'All Waste Types', eyebrow: 'Browse everything' },
]

export default function Order() {
  return (
    <section id='prices' className='relative scroll-mt-24 overflow-hidden bg-[#F3F6F7] py-16 sm:py-20 lg:py-24'>
      <div aria-hidden='true' className='absolute -left-40 top-16 size-96 rounded-full bg-[#0497E2]/8 blur-3xl' />
      <div aria-hidden='true' className='absolute -right-32 bottom-0 size-80 rounded-full bg-[#F4B942]/10 blur-3xl' />

      <div className='relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8'>
        <div className='grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-end lg:gap-16'>
          <div>
            <div className='inline-flex items-center gap-2 rounded-full border border-[#0492E8]/10 bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-[#11224D] shadow-sm'>
              <Recycle aria-hidden='true' className='size-4 text-[#0497E2]' />
              Prices &amp; booking
            </div>

            <h2 className='mt-6 max-w-4xl text-3xl font-black leading-[1.04] tracking-[-0.04em] text-[#11224D] sm:text-4xl lg:text-4xl'>
              Tell us what needs to go.
              <span className='block text-[#0497E2]'>We&apos;ll handle the rest.</span>
            </h2>
          </div>

          <div className='rounded-3xl border border-[#0492E8]/10 bg-white p-5 shadow-sm sm:p-6'>
            <p className='text-sm leading-6 text-slate-600 sm:text-base'>
              Choose a waste type to see clear prices and build your collection online. From one bulky item to a complete clearance, there is an option for every job.
            </p>
            <div className='mt-5 flex flex-wrap gap-x-5 gap-y-3 border-t border-[#0492E8]/10 pt-5 text-xs font-bold text-[#11224D] sm:text-sm'>
              <span className='inline-flex items-center gap-2'><Check className='size-4 text-[#0497E2]' /> Clear pricing</span>
              <span className='inline-flex items-center gap-2'><Check className='size-4 text-[#0497E2]' /> Easy booking</span>
              <span className='inline-flex items-center gap-2'><ShieldCheck className='size-4 text-[#0497E2]' /> Fully insured</span>
            </div>
          </div>
        </div>

        <div className='mt-10 flex items-center justify-between gap-4 border-b border-[#0492E8]/15 pb-4 sm:mt-14'>
          <h3 className='text-lg font-black text-[#11224D] sm:text-xl'>Choose your collection</h3>
          <p className='hidden text-sm text-slate-500 sm:block'>{services.length} ways to get started</p>
        </div>

        <div className='mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 xl:grid-cols-4'>
          {services.map((service, index) => (
            <Link
              key={service.id}
              href={`/prices?service=${service.id}`}
              className='group relative flex min-h-[19rem] min-w-0 flex-col overflow-hidden rounded-[1.75rem] border border-[#0492E8]/10 bg-white shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-[#0497E2]/35 hover:shadow-2xl hover:shadow-[#0492E8]/10 focus-visible:-translate-y-1 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#0497E2]'
            >
              <div className='relative m-2 mb-0 h-44 overflow-hidden rounded-[1.3rem] bg-white sm:h-48'>
                <span className='absolute left-3 top-3 z-10 rounded-full border border-white/60 bg-white/85 px-2.5 py-1 text-[10px] font-black tracking-[0.15em] text-[#11224D] shadow-sm backdrop-blur'>
                  {String(index + 1).padStart(2, '0')}
                </span>

                <Image
                  src={service.image}
                  alt={service.title}
                  fill
                  sizes='(max-width: 639px) 100vw, (max-width: 1023px) 50vw, (max-width: 1279px) 33vw, 25vw'
                  className='object-contain p-5 transition-transform duration-700 ease-out group-hover:scale-110 group-focus-visible:scale-110'
                />

                <div className='absolute inset-0 bg-linear-to-t from-[#0492E8]/10 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100' />
              </div>

              <div className='flex flex-1 items-end justify-between gap-4 p-5'>
                <div className='min-w-0'>
                  <p className='text-[10px] font-black uppercase tracking-[0.18em] text-[#0497E2]'>{service.eyebrow}</p>
                  <h4 className='mt-2 text-xl font-black leading-tight text-[#11224D] transition-colors duration-300 group-hover:text-[#0497E2]'>
                    {service.title}
                  </h4>
                  <p className='mt-2 text-sm text-slate-500'>View options &amp; prices</p>
                </div>

                <span className='flex size-11 shrink-0 items-center justify-center rounded-full bg-[#0492E8] text-white transition-all duration-500 group-hover:rotate-45 group-hover:bg-[#0497E2] group-hover:shadow-lg group-hover:shadow-[#0497E2]/25'>
                  <ArrowUpRight aria-hidden='true' className='size-5' />
                </span>
              </div>

              <span className='absolute inset-x-6 bottom-0 h-1 origin-left scale-x-0 rounded-full bg-linear-to-r from-[#0497E2] to-[#F4B942] transition-transform duration-500 group-hover:scale-x-100' />
            </Link>
          ))}
        </div>

        <div className='mt-8 flex flex-col gap-4 rounded-3xl bg-[#0492E8] px-5 py-6 text-[#102a4c] shadow-xl sm:flex-row sm:items-center sm:justify-between sm:px-7 lg:px-9'>
          <div>
            <p className='text-lg font-black text-white sm:text-xl'>Not sure which option to choose?</p>
            <p className='mt-1 text-sm text-white/65'>Browse every waste type or speak with our friendly collection team.</p>
          </div>
          <div className='flex flex-wrap gap-3'>
            <Link href='/prices?service=show-all' className='rounded-full bg-white px-5 py-2.5 text-sm font-bold text-[#11224D] transition-all duration-300 hover:-translate-y-0.5 hover:bg-[#F4B942] hover:shadow-lg focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white'>
              Browse all items
            </Link>
            <Link href='/contactUs' className='rounded-full border border-white/25 px-5 py-2.5 text-sm font-bold text-white transition-all duration-300 hover:-translate-y-0.5 hover:border-white hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white'>
              Ask our team
            </Link>
          </div>
        </div>
      </div>
    </section>
  )
}
