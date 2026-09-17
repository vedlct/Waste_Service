import Image from 'next/image'
import Link from 'next/link'
import { ArrowRight, CheckCircle2, Recycle } from 'lucide-react'

const services = [
  {
    image: '/images/rubbish1.jpg',
    title: 'Rubbish Clearance Services',
    eyebrow: 'Homes & properties',
    description: 'Fast, dependable clearance for household junk, unwanted belongings and general waste. Our team handles the lifting, loading and responsible disposal from start to finish.',
    href: '/houseClearance',
  },
  {
    image: '/images/rubbish2.jpg',
    title: 'Rubbish Removal Prices',
    eyebrow: 'Clear online pricing',
    description: 'Choose from flexible collection options for jobs of every size. Review transparent prices, select what needs collecting and build your booking online.',
    href: '/#prices',
  },
  {
    image: '/images/rubbish3.jpg',
    title: 'Commercial Waste Clearance',
    eyebrow: 'Business collections',
    description: 'Professional waste collections for offices, shops, restaurants and commercial sites, planned to keep disruption to your day-to-day operations to a minimum.',
    href: '/buildersWasteRemoval',
  },
  {
    image: '/images/rubbish4.jpg',
    title: 'Fly Tipping Clearance',
    eyebrow: 'Safe site recovery',
    description: 'Efficient removal of illegally dumped rubbish from private or commercial land, with careful handling and responsible transfer to licensed waste facilities.',
    href: '/flyTippingClearance',
  },
  {
    image: '/images/rubbish5.jpg',
    title: 'Furniture Removal & Disposal',
    eyebrow: 'Bulky item removal',
    description: 'Convenient collection of sofas, cabinets, desks, beds and other bulky furniture. We take care of awkward lifting and recycle items wherever possible.',
    href: '/#prices',
  },
  {
    image: '/images/img5.jpg',
    title: 'Local Areas Covered',
    eyebrow: 'Across the Home Counties',
    description: 'Reliable rubbish collection delivered by local clearance teams throughout the Home Counties, with flexible availability for homes and businesses.',
    href: '/area',
  },
]

export default function ServiceList() {
  return (
    <section className='relative w-full overflow-hidden bg-[#F8F5EE] py-16 sm:py-20 lg:py-24'>
      <div aria-hidden='true' className='absolute -left-28 top-20 size-72 rounded-full bg-[#F4B942]/10 blur-3xl' />
      <div aria-hidden='true' className='absolute -right-28 bottom-20 size-80 rounded-full border-[58px] border-[#0497E2]/5' />

      <div className='relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8'>
        <div className='mx-auto max-w-4xl text-center'>
          <span className='inline-flex items-center gap-2 rounded-full border border-[#F4B942]/35 bg-white/80 px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-[#11224D] shadow-sm'>
            <Recycle aria-hidden='true' className='size-4 text-[#0497E2]' />
            Our services
          </span>

          <h2 className='mt-6 text-3xl font-black leading-[1.05] tracking-[-0.04em] text-[#11224D] sm:text-4xl lg:text-4xl'>
            The right clearance service
            <span className='block bg-linear-to-r from-[#0497E2] to-[#F4B942] bg-clip-text text-transparent'>for every kind of space.</span>
          </h2>

          <p className='mx-auto mt-5 max-w-3xl text-base leading-7 text-slate-600 sm:text-lg'>
            From one unwanted item to a complete property or commercial clearance, our experienced team provides practical options, responsible disposal and reliable local support.
          </p>

          <div className='mt-6 flex flex-wrap items-center justify-center gap-x-6 gap-y-3 text-sm font-bold text-[#11224D]'>
            <span className='inline-flex items-center gap-2'><CheckCircle2 className='size-4 text-[#0497E2]' /> Fully insured</span>
            <span className='inline-flex items-center gap-2'><CheckCircle2 className='size-4 text-[#0497E2]' /> Licensed waste carrier</span>
            <span className='inline-flex items-center gap-2'><CheckCircle2 className='size-4 text-[#0497E2]' /> 95% recycled</span>
          </div>
        </div>

        <div className='mt-12 grid grid-cols-1 gap-5 sm:mt-14 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6'>
          {services.map((service, index) => (
            <article key={service.title} className='group flex min-w-0 flex-col overflow-hidden rounded-[1.75rem] border border-[#0492E8]/10 bg-white shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-[#0497E2]/30 hover:shadow-2xl hover:shadow-[#0492E8]/10'>
              <div className='relative m-2 mb-0 aspect-[16/10] overflow-hidden rounded-[1.3rem] bg-[#DDEAF1]'>
                <Image
                  src={service.image}
                  alt={service.title}
                  fill
                  sizes='(max-width: 639px) 100vw, (max-width: 1023px) 50vw, 33vw'
                  className='object-cover transition-transform duration-700 ease-out group-hover:scale-110'
                />
                <div className='absolute inset-0 bg-linear-to-t from-[#0492E8]/45 via-transparent to-transparent opacity-70 transition-opacity duration-500 group-hover:opacity-40' />

                <span className='absolute left-4 top-4 flex size-10 items-center justify-center rounded-xl border border-white/50 bg-white/90 text-xs font-black text-[#11224D] shadow-md backdrop-blur'>
                  {String(index + 1).padStart(2, '0')}
                </span>

                <span className='absolute bottom-4 left-4 rounded-full bg-[#0492E8]/80 px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.16em] text-[#102a4c] backdrop-blur transition-colors duration-300 group-hover:bg-[#0497E2]'>
                  {service.eyebrow}
                </span>
              </div>

              <div className='flex flex-1 flex-col p-5 sm:p-6'>
                <h3 className='text-2xl font-black leading-tight tracking-[-0.02em] text-[#11224D] transition-colors duration-300 group-hover:text-[#0497E2]'>
                  {service.title}
                </h3>
                <p className='mt-3 flex-1 text-sm leading-6 text-slate-600 sm:text-base sm:leading-7'>
                  {service.description}
                </p>

                <Link href={service.href} className='group/link mt-6 flex items-center justify-between gap-4 border-t border-[#0492E8]/10 pt-4 text-sm font-black text-[#11224D] transition-colors duration-300 hover:text-[#0497E2] focus-visible:outline-none focus-visible:text-[#0497E2]'>
                  Explore this service
                  <span className='flex size-10 shrink-0 items-center justify-center rounded-full bg-[#0492E8] text-white font-bold transition-all duration-300 group-hover/link:translate-x-1 group-hover/link:bg-[#0497E2] group-focus-visible/link:translate-x-1 group-focus-visible/link:bg-[#0497E2]'>
                    <ArrowRight aria-hidden='true' className='size-5' />
                  </span>
                </Link>
              </div>

              <span className='mx-6 h-1 origin-left scale-x-0 rounded-full bg-linear-to-r from-[#0497E2] to-[#F4B942] transition-transform duration-500 group-hover:scale-x-100' />
            </article>
          ))}
        </div>

        <div className='mt-10 flex flex-col gap-5 rounded-[2rem] bg-[#0492E8] p-6 text-[#102a4c] shadow-xl sm:flex-row sm:items-center sm:justify-between sm:p-8 lg:px-10'>
          <div>
            <p className='text-2xl font-black text-white'>Unsure which service fits your clearance?</p>
            <p className='mt-2 max-w-2xl text-sm leading-6 text-white/65 sm:text-base'>Tell us what needs to go and our team will help you choose the most practical and cost-effective collection.</p>
          </div>
          <Link href='/contactUs' className='group inline-flex shrink-0 items-center justify-center gap-3 self-start rounded-full bg-white px-5 py-3 text-sm font-black text-[#11224D] transition-all duration-300 hover:-translate-y-1 hover:bg-[#F4B942] hover:shadow-lg focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white sm:self-auto'>
            Talk to our team
            <ArrowRight aria-hidden='true' className='size-4 transition-transform duration-300 group-hover:translate-x-1' />
          </Link>
        </div>
      </div>
    </section>
  )
}
