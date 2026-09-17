import Link from 'next/link'
import {
  Armchair,
  ArrowRight,
  Building2,
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
    <section style={{ backgroundColor: '#81D4FA' }} className='relative overflow-hidden py-12 text-[#102a4c] sm:py-14 lg:py-16'>
      <div aria-hidden='true' className='absolute -left-32 top-10 size-80 rounded-full border-[70px] border-white/[0.06]' />
      <div aria-hidden='true' className='absolute -right-24 bottom-0 size-72 rounded-full bg-[#BFE8FF]/20 blur-3xl' />

      <div className='relative mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[0.68fr_1.32fr] lg:gap-10 lg:px-8 xl:gap-12'>
        <div className='lg:sticky lg:top-28 lg:self-start'>
          <span className='inline-flex rounded-full border border-white/25 bg-[#037CC8]/30 px-3.5 py-1.5 text-[0.68rem] font-black uppercase tracking-[0.18em] text-white/90 backdrop-blur'>
            What we collect
          </span>

          <h2 className='mt-5 max-w-xl text-3xl font-black leading-[1.06] tracking-[-0.035em] sm:text-4xl lg:text-4xl'>
            Rubbish removal,
            <span className='block text-[#C8EEFF]'>made straightforward.</span>
          </h2>

          <p className='mt-5 max-w-lg text-sm leading-6 text-[#102a4c]/80 sm:text-base sm:leading-7'>
            From everyday household junk to bulky furniture and building waste, our Chingford collection team handles the lifting, loading and responsible disposal.
          </p>

          <div className='mt-6 flex items-center gap-3 border-l-2 border-[#F4B942] pl-4'>
            <p className='text-sm leading-6 text-[#102a4c]/80'>
              <strong className='block text-base text-[#102a4c]'>One team. One simple collection.</strong>
              Choose a service to view your options.
            </p>
          </div>

          <Link href='/prices?service=show-all' className='group mt-6 inline-flex items-center gap-2.5 rounded-full bg-white px-5 py-2.5 text-sm font-black text-[#11224D] transition-all duration-300 hover:-translate-y-1 hover:bg-[#F4B942] hover:shadow-xl hover:shadow-black/20 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white'>
            View all waste types
            <ArrowRight aria-hidden='true' className='size-4 transition-transform duration-300 group-hover:translate-x-1' />
          </Link>
        </div>

        <div className='grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3'>
          {services.map((service, index) => {
            const Icon = service.icon

            return (
              <Link
                key={service.title}
                href={service.href}
                className='group relative flex min-h-40 flex-col justify-between overflow-hidden rounded-[1.4rem] border border-white/20 bg-[#037CC8]/35 p-4 shadow-sm backdrop-blur-sm transition-all duration-500 hover:-translate-y-1.5 hover:border-white hover:bg-white hover:shadow-2xl hover:shadow-black/20 focus-visible:-translate-y-1 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#C8EEFF] sm:min-h-44 sm:p-5'
              >
                <div aria-hidden='true' className='absolute -right-10 -top-10 size-32 rounded-full bg-white/10 transition-transform duration-700 group-hover:scale-[2.2]' />

                <div className='relative flex items-start justify-between gap-4'>
                  <span className='flex size-10 shrink-0 items-center justify-center rounded-xl bg-[#C8EEFF] text-[#075A8C] shadow-lg shadow-black/10 transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 group-hover:bg-[#0492E8] group-hover:text-white'>
                    <Icon aria-hidden='true' className='size-5' strokeWidth={1.8} />
                  </span>
                  <span className='text-xs font-black tracking-[0.16em] text-[#102a4c]/55 transition-colors duration-300 group-hover:text-[#0497E2]'>
                    {String(index + 1).padStart(2, '0')}
                  </span>
                </div>

                <div className='relative mt-5'>
                  <h3 className='max-w-xs text-base font-black leading-snug text-[#102a4c] transition-colors duration-300 group-hover:text-[#11224D] sm:text-lg'>
                    {service.title}
                  </h3>

                  <div className='mt-3 flex items-center justify-between gap-3 border-t border-white/20 pt-3 transition-colors duration-300 group-hover:border-[#0492E8]/10'>
                    <span className='text-xs font-bold uppercase tracking-[0.14em] text-[#C8EEFF] transition-colors duration-300 group-hover:text-[#0497E2]'>
                      Explore service
                    </span>
                    <span className='flex size-8 shrink-0 items-center justify-center rounded-full border border-white/30 text-white/90 transition-all duration-300 group-hover:border-[#0492E8] group-hover:bg-[#0492E8] group-hover:text-white'>
                      <ArrowRight aria-hidden='true' className='size-4 transition-transform duration-300 group-hover:translate-x-1' />
                    </span>
                  </div>
                </div>

                <span className='absolute inset-x-6 bottom-0 h-1 origin-left scale-x-0 rounded-full bg-linear-to-r from-[#C8EEFF] to-[#F4B942] transition-transform duration-500 group-hover:scale-x-100' />
              </Link>
            )
          })}
        </div>
      </div>
    </section>
  )
}
