import Image from 'next/image'
import Link from 'next/link'
import { ArrowRight, Building2, Car, Hammer, House, Leaf, Sofa, TreePine, Truck } from 'lucide-react'
import SectionHeader from '@/components/ui/SectionHeader'

const services = [
  { title: 'House Clearance', text: 'Single rooms to whole properties', image: '/images/HClearance.jpg', href: '/houseClearance', icon: House },
  { title: 'Furniture Removal', text: 'Sofas, beds, wardrobes and more', image: '/images/FR.jpg', href: '/furnitureClearance', icon: Sofa },
  { title: 'Garden Clearance', text: 'Green waste, sheds and soil', image: '/images/GClearance.jpg', href: '/gardenClearance', icon: Leaf },
  { title: 'Man & Van', text: 'We load, you point', image: '/images/service-man-van-branded.png', href: '/waitLoad', icon: Truck },
  { title: 'Garage Clearance', text: 'Clutter out, space back', image: '/images/GarageC.jpg', href: '/garageClearance', icon: Car },
  { title: 'Builders Waste', text: 'Rubble, timber and fittings', image: '/images/BW.jpg', href: '/buildersWasteRemoval', icon: Hammer },
  { title: 'Office & Commercial', text: 'Minimal disruption to your day', image: '/images/Office.jpg', href: '/officeWasteClearance', icon: Building2 },
  { title: 'Fly-Tipping', text: 'Dumped waste cleared fast', image: '/images/FTClearance.jpg', href: '/flyTippingClearance', icon: TreePine },
]

export default function ServicesShowcase() {
  return (
    <section className='relative bg-white pb-20 pt-24 sm:pb-24 sm:pt-28'>
      <div className='mx-auto max-w-7xl px-4 sm:px-6 lg:px-8'>
        <div className='flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between'>
          <SectionHeader
            eyebrow='What we clear'
            title='If it needs to go,'
            accent='we take it away'
            intro='Old furniture, cardboard, black bags, garden waste and general clutter. Tell us what you have and our team does the lifting, loading and responsible disposal.'
          />
          <Link href='/prices' className='group inline-flex shrink-0 items-center gap-2 rounded-lg border border-brand-200 bg-brand-50 px-5 py-3 font-bold text-brand-700 transition-colors hover:bg-brand-100'>
            See all prices
            <ArrowRight className='size-4 transition-transform group-hover:translate-x-1' aria-hidden='true' />
          </Link>
        </div>

        <ul className='mt-12 grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4'>
          {services.map(({ title, text, image, href, icon: Icon }) => (
            <li key={href}>
              <Link href={href} className='group flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-white shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:border-brand-200 hover:shadow-[0_28px_50px_-28px_rgba(31,95,217,0.55)]'>
                <div className='relative aspect-[4/3] overflow-hidden'>
                  <Image
                    src={image}
                    alt=''
                    fill
                    sizes='(min-width: 1024px) 25vw, 50vw'
                    className='object-cover transition-transform duration-700 group-hover:scale-105'
                  />
                </div>
                <div className='relative flex flex-1 flex-col px-4 pb-5 pt-8 sm:px-5'>
                  <span className='absolute -top-6 left-4 grid size-12 place-items-center rounded-xl border-4 border-white bg-linear-to-br from-brand-500 to-brand-700 text-white shadow-lg shadow-brand-600/30 sm:left-5'>
                    <Icon className='size-5' aria-hidden='true' />
                  </span>
                  <h3 className='font-display text-xl font-bold uppercase leading-tight text-ink sm:text-2xl'>{title}</h3>
                  <p className='mt-1 text-sm text-slate-600'>{text}</p>
                  <span className='mt-auto inline-flex items-center gap-1.5 pt-4 text-sm font-bold text-brand-700'>
                    Learn more
                    <ArrowRight className='size-4 transition-transform group-hover:translate-x-1' aria-hidden='true' />
                  </span>
                </div>
              </Link>
            </li>
          ))}
        </ul>
      </div>
    </section>
  )
}
