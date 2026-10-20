import Image from 'next/image'
import Link from 'next/link'
import { ArrowRight, Check } from 'lucide-react'
import SectionHeader from '@/components/ui/SectionHeader'

const services = [
  { id: 'man-van', image: '/images/load3.jpg', title: 'Man & Van', eyebrow: 'Flexible loads' },
  { id: 'sofas', image: '/images/Sofas.jpg', title: 'Sofas', eyebrow: 'Bulky furniture' },
  { id: 'mattress-bed', image: '/images/mattress.jpg', title: 'Mattress & Bed', eyebrow: 'Bedroom items' },
  { id: 'furniture', image: '/images/junkRubbish2.jpg', title: 'Furniture', eyebrow: 'Home clearance' },
  { id: 'kitchen-appliances', image: '/images/Kitchen.jpg', title: 'Kitchen Appliances', eyebrow: 'Large appliances' },
  { id: 'fridge-freezer', image: '/images/Fridge.jpg', title: 'Fridge & Freezer', eyebrow: 'Responsible disposal' },
  { id: 'electrical-it', image: '/images/junkRubbish3.jpg', title: 'Electrical & IT', eyebrow: 'Electrical waste' },
  { id: 'garden-items', image: '/images/Garden.jpg', title: 'Garden Items', eyebrow: 'Outdoor clearance' },
  { id: 'hazardous-waste', image: '/images/haz4.jpg', title: 'Hazardous Waste', eyebrow: 'Specialist handling' },
  { id: 'office-items', image: '/images/officeRubbish.jpg', title: 'Office Items', eyebrow: 'Workplace clearance' },
  { id: 'commercial-items', image: '/images/rubbish3.jpg', title: 'Commercial Items', eyebrow: 'Business waste' },
  { id: 'bins-wheelie-bins', image: '/images/wheele1.jpg', title: 'Bins', eyebrow: 'Everyday waste' },
]

export default function Order() {
  return (
    <section id='prices' className='scroll-mt-24 bg-mist py-20 sm:py-24'>
      <div className='mx-auto max-w-7xl px-4 sm:px-6 lg:px-8'>
        <div className='flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between'>
          <SectionHeader
            eyebrow='Prices & booking'
            title='Tell us what needs to go,'
            accent='see your price'
            intro='Choose a waste type to see clear prices and build your collection online. From one bulky item to a complete clearance, there is an option for every job.'
          />
          <ul className='flex flex-wrap gap-x-5 gap-y-2 text-sm font-semibold text-ink lg:shrink-0 lg:flex-col'>
            {['Clear, upfront pricing', 'Book online in minutes', 'Fully insured team'].map((point) => (
              <li key={point} className='inline-flex items-center gap-2'>
                <Check className='size-4 text-brand-600' strokeWidth={3} aria-hidden='true' />
                {point}
              </li>
            ))}
          </ul>
        </div>

        <ul className='mt-12 grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-3 lg:grid-cols-4'>
          {services.map((service) => (
            <li key={service.id}>
              <Link
                href={`/prices?service=${service.id}`}
                className='group flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-white transition-all duration-300 hover:-translate-y-1 hover:border-brand-300 hover:shadow-[0_20px_40px_-20px_rgba(31,95,217,0.45)]'
              >
                <div className='relative aspect-[4/3] overflow-hidden'>
                  <Image
                    src={service.image}
                    alt=''
                    fill
                    sizes='(min-width: 1024px) 25vw, (min-width: 768px) 33vw, 50vw'
                    className='object-cover transition-transform duration-700 group-hover:scale-105'
                  />
                </div>
                <div className='flex flex-1 items-center justify-between gap-3 p-4 sm:p-5'>
                  <div className='min-w-0'>
                    <p className='text-[11px] font-bold uppercase tracking-[0.16em] text-brand-600'>{service.eyebrow}</p>
                    <h3 className='mt-1 font-display text-xl font-bold uppercase leading-tight text-ink sm:text-2xl'>{service.title}</h3>
                  </div>
                  <span className='hidden size-10 shrink-0 place-items-center rounded-full bg-brand-50 text-brand-700 transition-colors group-hover:bg-brand-600 group-hover:text-white sm:grid'>
                    <ArrowRight className='size-4' aria-hidden='true' />
                  </span>
                </div>
              </Link>
            </li>
          ))}
        </ul>

        <div className='mt-8 flex flex-col items-start gap-4 rounded-2xl border border-line bg-white p-6 sm:flex-row sm:items-center sm:justify-between sm:px-8'>
          <div>
            <p className='font-display text-2xl font-bold uppercase text-ink'>Not sure which option to choose?</p>
            <p className='mt-1 text-sm text-slate-600'>Browse every waste type, or speak to our friendly team.</p>
          </div>
          <div className='flex flex-wrap gap-3'>
            <Link href='/prices?service=show-all' className='rounded-lg bg-brand-600 px-5 py-3 text-sm font-bold text-white transition-colors hover:bg-brand-700'>
              Browse all items
            </Link>
            <Link href='/contactUs' className='rounded-lg border border-line px-5 py-3 text-sm font-bold text-ink transition-colors hover:border-brand-300 hover:bg-brand-50'>
              Ask our team
            </Link>
          </div>
        </div>
      </div>
    </section>
  )
}
