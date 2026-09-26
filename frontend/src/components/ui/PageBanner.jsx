import Link from 'next/link'
import { ChevronRight } from 'lucide-react'
import PhotoHero from './PhotoHero'

// The title band at the top of the non-service pages: a clear, untinted photo with the title on
// a white card.
export default function PageBanner({ eyebrow, title, accent, intro, image = '/images/rubbishRemoval.jpg', crumb, children }) {
  return (
    <PhotoHero image={image} minHeight='lg:min-h-[30rem]'>
      <nav aria-label='Breadcrumb' className='flex items-center gap-1.5 text-sm text-slate-500'>
        <Link href='/' className='hover:text-brand-700'>Home</Link>
        <ChevronRight className='size-3.5' aria-hidden='true' />
        <span className='font-semibold text-ink'>{crumb || title}</span>
      </nav>

      {eyebrow && <p className='mt-5 text-sm font-bold uppercase tracking-[0.18em] text-brand-600'>{eyebrow}</p>}
      <h1 className='mt-3 text-4xl font-extrabold uppercase leading-[0.92] text-ink sm:text-5xl'>
        {title}
        {accent && <span className='block text-brand-600'>{accent}</span>}
      </h1>
      {intro && <p className='mt-5 text-base leading-7 text-slate-600 sm:text-lg'>{intro}</p>}
      {children && <div className='mt-5'>{children}</div>}
    </PhotoHero>
  )
}
