import Link from 'next/link'
import {
  Armchair,
  ArrowRight,
  Building2,
  Check,
  HardHat,
  Hammer,
  Home,
  Refrigerator,
  Trash2,
  TreePine,
  Wrench,
} from 'lucide-react'

const services = [
  { title: 'House Rubbish Clearance', icon: Home, href: '/houseClearance' },
  { title: 'White Goods Appliances (inc. fridges/freezers)', icon: Refrigerator, href: '/prices?service=kitchen-appliances' },
  { title: 'Builders Waste & Rubble Removal', icon: HardHat, href: '/buildersWasteRemoval' },
  { title: 'Home Improvement Waste', icon: Hammer, href: '/buildersWasteRemoval' },
  { title: 'Plumbers Waste', icon: Wrench, href: '/buildersWasteRemoval' },
  { title: 'Garden Clearance (inc. sheds & garages)', icon: TreePine, href: '/gardenClearance' },
  { title: 'Old Furniture (inc. sofas & cabinets)', icon: Armchair, href: '/prices?service=furniture' },
  { title: 'Office Waste Clearance', icon: Building2, href: '/officeWasteClearance' },
  { title: 'General House Junk Removal', icon: Trash2, href: '/prices?service=show-all' },
]

export default function RubbishRemoval() {
  return (
    <section aria-labelledby='rubbish-removal-heading' className='bg-[#F4F8FD] py-14 text-[#11224D] sm:py-20 lg:py-24'>
      <div className='mx-auto max-w-7xl px-4 sm:px-6 lg:px-8'>
        <div className='grid min-w-0 gap-12 lg:grid-cols-[minmax(0,1.55fr)_minmax(0,1fr)] lg:items-end lg:gap-10 xl:gap-16'>
          <div>
            <span className='flex items-center gap-3 font-mono text-xs uppercase tracking-[0.08em] text-[#385B82] before:size-1.5 before:rounded-full before:bg-[#037CC8] p-2 bg-white max-w-fit border border-[#037CC8]/20 shadow shadow-[#037CC8]/20 rounded-full'>
              What we collect
            </span>

            <h2 id='rubbish-removal-heading' className='mt-6 min-w-0 text-[clamp(1.75rem,8.5vw,3.5rem)] font-black leading-[1.06] tracking-[-0.045em] [overflow-wrap:anywhere] sm:mt-7 sm:text-[3.5rem] lg:text-[3.5rem] xl:text-[4.5rem]'>
              <span className='block max-w-[8ch]'>Rubbish removal,</span>
              <span className='block text-[#037CC8]'>made straightforward.</span>
            </h2>

            <p className='mt-7 max-w-xl text-base leading-7 text-[#385B82] sm:mt-8 sm:text-lg'>
              From everyday household junk to bulky furniture and building waste, our Chingford collection team handles the lifting, loading and responsible disposal.
            </p>
          </div>

          <div className='group/callout relative min-w-0 border border-[#11224D]/30 bg-[#037CC8]/10 p-5 pt-10 transition-[transform,box-shadow,border-color] duration-500 ease-out hover:border-[#037CC8]/30 hover:shadow-[0_18px_45px_-20px_rgba(3,124,200,0.35)] focus-within:border-[#037CC8] focus-within:shadow-[0_18px_45px_-20px_rgba(3,124,200,0.35)] motion-safe:hover:-translate-y-1 sm:p-8 sm:pt-11 lg:p-6 lg:pt-11 xl:p-8 xl:pt-11 motion-reduce:transition-none rounded-xl'>
            <span aria-hidden='true' className='absolute -top-5 right-6 flex size-14 items-center justify-center rounded-full border border-[#037CC8] bg-[#037CC8] text-white transition-[transform,box-shadow] duration-500 group-hover/callout:shadow-[0_0_0_6px_rgba(3,124,200,0.1)] motion-safe:group-hover/callout:-rotate-12 motion-safe:group-hover/callout:scale-105 sm:right-7 motion-reduce:transition-none'>
              <Check className='size-6' strokeWidth={2} />
            </span>

            <p className='text-sm leading-6 text-[#385B82] sm:text-base sm:leading-7'>
              <strong className='mb-4 block border-b border-dashed border-[#BCD4EA] pb-4 text-xl font-bold leading-7 text-[#11224D]'>One team. One simple collection.</strong>
              Choose a service to view your options.
            </p>

            <Link href='/prices?service=show-all' className='group relative isolate mt-6 flex min-h-12 items-center justify-between gap-3 overflow-hidden bg-[#11224D] px-4 py-3.5 text-sm font-semibold text-white transition-[background-color,box-shadow,transform] duration-300 hover:bg-[#037CC8] hover:shadow-[0_8px_20px_-8px_rgba(3,124,200,0.5)] active:bg-[#075A8C] focus-visible:bg-[#037CC8] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#037CC8] motion-safe:active:scale-[0.98] sm:px-5 motion-reduce:transition-none rounded-xl'>
              View all waste types
              <ArrowRight aria-hidden='true' className='size-4 shrink-0 transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transform-none motion-reduce:transition-none' />
            </Link>
          </div>
        </div>

        <div className='mt-12 border-t border-[#11224D] pt-4 sm:mt-16 lg:mt-24'>
          {services.map((service, index) => {
            const Icon = service.icon

            return (
              <Link
                key={service.title}
                href={service.href}
                className='group relative grid min-w-0 grid-cols-[1rem_2.25rem_minmax(0,1fr)_2.75rem] items-center gap-x-2 gap-y-1 border-b border-[#CDDEEE] px-1 py-5 transition-[background-color,border-color,box-shadow] duration-300 ease-out before:pointer-events-none before:absolute before:inset-y-4 before:left-0 before:w-0.5 before:rounded-full before:bg-[#037CC8] before:opacity-0 before:transition-opacity before:duration-300 hover:border-[#91C9EF] hover:bg-white hover:shadow-[0_8px_28px_-16px_rgba(17,34,77,0.2)] hover:before:opacity-100 active:bg-[#E9F4FF] focus-visible:bg-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#037CC8] focus-visible:before:opacity-100 sm:gap-x-4 sm:px-3 md:min-h-24 md:grid-cols-[2rem_2.5rem_minmax(0,1fr)_auto_2.75rem] md:gap-x-5 md:py-6 lg:gap-x-7 motion-reduce:transition-none motion-reduce:before:transition-none'
              >
                <span className='row-span-2 font-mono text-[0.65rem] tabular-nums text-[#385B82] transition-colors duration-300 group-hover:text-[#037CC8] md:row-span-1 md:text-xs motion-reduce:transition-none'>
                  {String(index + 1).padStart(2, '0')}
                </span>

                <span className={`row-span-2 flex size-9 items-center justify-center rounded-full text-white transition-[transform,box-shadow] duration-300 ease-out group-hover:shadow-[0_0_0_5px_rgba(3,124,200,0.1)] group-focus-visible:shadow-[0_0_0_5px_rgba(3,124,200,0.1)] motion-safe:group-hover:-rotate-6 motion-safe:group-hover:scale-110 md:row-span-1 md:size-10 motion-reduce:transition-none ${index % 2 === 0 ? 'bg-[#11224D]' : 'bg-[#037CC8]'}`}>
                  <Icon aria-hidden='true' className='size-[18px] sm:size-5' strokeWidth={1.7} />
                </span>

                <h3 className='min-w-0 text-sm font-bold leading-6 tracking-[-0.015em] text-[#11224D] [overflow-wrap:anywhere] transition-colors duration-300 group-hover:text-[#037CC8] group-focus-visible:text-[#037CC8] sm:text-base lg:text-lg motion-reduce:transition-none'>
                  {service.title}
                </h3>

                <span className='col-start-3 row-start-2 font-mono text-[0.6rem] uppercase tracking-[0.06em] text-[#176EA6] underline-offset-4 group-hover:underline group-focus-visible:underline md:col-start-4 md:row-start-1 md:whitespace-nowrap md:text-[0.65rem] lg:mr-5'>
                  Explore service
                </span>

                <span className='col-start-4 row-span-2 row-start-1 flex size-11 items-center justify-center rounded-full border border-[#CDDEEE] bg-white text-[#385B82] transition-[background-color,border-color,color,transform,box-shadow] duration-300 ease-out group-hover:border-[#037CC8] group-hover:bg-[#037CC8] group-hover:text-white group-hover:shadow-[0_4px_12px_rgba(3,124,200,0.2)] group-focus-visible:border-[#037CC8] group-focus-visible:bg-[#037CC8] group-focus-visible:text-white motion-safe:group-hover:-rotate-45 md:col-start-5 md:row-span-1 motion-reduce:transition-none'>
                  <ArrowRight aria-hidden='true' className='size-4' strokeWidth={1.5} />
                </span>
              </Link>
            )
          })}
        </div>
      </div>
    </section>
  )
}
