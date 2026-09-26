import Link from 'next/link';
import { ArrowRight, Check, ChevronRight, Phone } from 'lucide-react';
import PhotoHero from '@/components/ui/PhotoHero';
import { heroContent } from '@/lib/services';
import { FALLBACK_CONTACT, telHref } from '@/lib/site';

// The hero shared by the service pages: a clear, untinted photo with the text on a white card.
// The headline, summary and image come from the service in the admin when it is available;
// the props are the built-in fallback.
export default function ServiceHero({ service, phone = FALLBACK_CONTACT.phone, image, imageAlt, eyebrow, title, description, imagePosition = 'object-center', points = ['Flexible appointment times', 'Professional local service'] }) {
  const hero = heroContent(service, { title, description, image, imageAlt });
  const name = service?.name || eyebrow;

  return (
    <PhotoHero image={hero.image} alt={hero.imageAlt} imagePosition={imagePosition} unoptimized={!hero.image.startsWith('/')}>
      <nav aria-label='Breadcrumb' className='flex items-center gap-1.5 text-sm text-slate-500'>
        <Link href='/' className='hover:text-brand-700'>Home</Link>
        <ChevronRight className='size-3.5' aria-hidden='true' />
        <span className='font-semibold text-ink'>{name}</span>
      </nav>

      {eyebrow && <p className='mt-5 text-sm font-bold uppercase tracking-[0.18em] text-brand-600'>{eyebrow}</p>}

      <h1 className='mt-3 text-4xl font-extrabold uppercase leading-[0.92] text-ink sm:text-5xl'>{hero.title}</h1>

      <p className='mt-5 text-base leading-7 text-slate-600 sm:text-lg'>{hero.description}</p>

      <ul className='mt-5 flex flex-wrap gap-x-5 gap-y-2'>
        {points.map((point) => (
          <li key={point} className='flex items-center gap-2 text-sm font-semibold text-ink'>
            <Check className='size-5 text-brand-600' strokeWidth={3} aria-hidden='true' />
            {point}
          </li>
        ))}
      </ul>

      <div className='mt-8 flex flex-col gap-3 sm:flex-row'>
        <Link href='/prices' className='group inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-6 py-3.5 font-bold text-white transition-colors hover:bg-brand-700'>
          Get a price &amp; book
          <ArrowRight className='size-5 transition-transform group-hover:translate-x-1' aria-hidden='true' />
        </Link>
        <a href={telHref(phone)} aria-label={`Call ${phone}`} className='inline-flex items-center justify-center gap-2 rounded-lg border border-line px-6 py-3.5 font-bold text-ink transition-colors hover:border-brand-300 hover:bg-brand-50'>
          <Phone className='size-5 text-brand-600' aria-hidden='true' />
          {phone}
        </a>
      </div>
    </PhotoHero>
  );
}
