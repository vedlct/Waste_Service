import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, Recycle } from 'lucide-react';

const categories = [
  {
    number: '01',
    title: 'Furniture & household items',
    summary: 'Furniture, furnishings and bulky household pieces from bedrooms, lounges, dining areas, offices and gardens.',
    items: [
      'Armchairs', 'Bath Frames', 'Bathroom Sinks', 'Bathroom Stripping', 'Bed Frames',
      'Bedside Tables', 'Bikes', 'Blinds', 'Book Cases', 'Cabinets', 'Carpets', 'Chairs',
      'Chests of Drawers', 'Coffee Tables', 'Conservatory Furniture', 'Console Tables',
      'Cookers', 'Cupboards', 'Curtains Doors', 'Cushions', 'Desks', 'Desktop Monitors',
      'Dining Room Chairs', 'Dining Tables', 'Dishwashers', 'Extractor Fans', 'Floor Tiles',
      'Freezers', 'Fridges', 'Games Consoles', 'Gaming Chairs', 'Kitchen Appliances',
      'Kitchen Cupboards', 'Kitchen Lighting', 'Kitchen Sinks', 'Kitchen Stripping',
      'Kitchen Tables', 'Laminate Flooring', 'Lamps', 'Mattresses', 'Microwaves', 'Mirrors',
      'Office Chairs', 'Office Stripping', 'Old cars or bikes', 'Ovens', 'PCs', 'Printers',
      'Radiators', 'Rugs', 'Shelves', 'Skirting', 'Sofas', 'Sound Systems', 'Tables', 'TVs',
      'Wall Tiles', 'Wallpaper', 'Wardrobes', 'Washing Machines', 'Window Frames', 'Windows',
    ],
  },
];

const highlightImages = [
  { src: '/images/junkRubbish1.jpg', alt: 'Household waste prepared for collection' },
  { src: '/images/junkRubbish2.jpg', alt: 'Household items ready for responsible removal' },
  { src: '/images/junkRubbish3.jpg', alt: 'Clearance materials organised for collection' },
  { src: '/images/junkRubbish4.jpg', alt: 'Waste clearance team handling a collection' },
];

export default function JunkRubbishItem () {
  return (
    <section className="overflow-hidden bg-mist">
      <div className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-10 lg:grid-cols-[0.72fr_1.28fr] lg:gap-16">
          <div className="lg:sticky lg:top-28 lg:self-start">
            <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-brand-700 sm:text-sm">
              <span className="h-0.5 w-9 bg-brand-500" aria-hidden="true" />
              What we take
            </div>

            <h2 className="mt-5 text-4xl font-bold leading-[0.95] text-ink sm:text-5xl lg:text-5xl uppercase">
              One collection.
              <span className="block text-brand-700">More space to use.</span>
            </h2>

            <p className="mt-6 max-w-lg text-base leading-8 text-slate-600 sm:text-lg">
              From furniture and appliances to renovation materials, we clear a wide range of household items throughout Portsmouth.
            </p>

            <div className="mt-8 border-l-2 border-brand-500 pl-5">
              <p className="text-sm font-bold leading-6 text-ink sm:text-base">
                Cannot see your item listed?
              </p>
              <p className="mt-2 text-sm leading-6 text-slate-600">
                This directory is only a guide. Tell our team what you have and we will confirm whether it can be collected.
              </p>
            </div>

            <Link
              href="/contactUs"
              className="group mt-8 inline-flex min-h-12 items-center justify-center gap-3 rounded-full bg-brand-900 px-6 py-3 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-brand-500 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink"
            >
              Ask about an item
              <ArrowRight className="size-4 transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true" />
            </Link>

            <div className="relative mt-10 h-[390px] sm:h-[470px] lg:h-[520px]" aria-label="House clearance collection gallery">
              {highlightImages.map(({ src, alt }, index) => {
                const positions = [
                  'left-0 top-0 h-[47%] w-[72%] rounded-[1.75rem]',
                  'right-0 top-[9%] h-[35%] w-[38%] rounded-[1.5rem]',
                  'bottom-[5%] left-[4%] h-[42%] w-[42%] rounded-[1.5rem]',
                  'bottom-0 right-0 h-[48%] w-[55%] rounded-[1.75rem]',
                ];

                return (
                  <div
                    key={src}
                    className={`group/image absolute overflow-hidden border-4 border-mist bg-brand-900 shadow-[0_20px_45px_-28px_rgba(15,37,87,0.75)] transition duration-500 hover:z-20 hover:-translate-y-1 hover:shadow-[0_28px_55px_-25px_rgba(15,37,87,0.65)] ${positions[index]}`}
                  >
                    <Image
                      src={src}
                      alt={alt}
                      fill
                      sizes="(max-width: 1024px) 65vw, 360px"
                      className="object-cover transition duration-700 ease-out group-hover/image:scale-110"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-brand-950/35 via-transparent to-transparent" />
                    <span className="absolute bottom-3 left-3 grid size-9 place-items-center rounded-full bg-brand-500 text-white shadow-md transition duration-500 group-hover/image:rotate-12">
                      <Recycle className="size-4" aria-hidden="true" />
                    </span>
                  </div>
                );
              })}
            </div>
          </div>

          <div className="border-t border-ink/20">
            {categories.map(({ number, title, summary, items }) => (
              <article key={number} className="group/category border-b border-ink/20 py-8 sm:py-10">
                <div className="grid gap-5 sm:grid-cols-[4rem_1fr] sm:gap-6">
                  <span className="text-sm font-black tracking-[0.16em] text-brand-700">{number}</span>

                  <div>
                    <div className="flex items-start justify-between gap-4">
                      <div>
                        <h3 className="text-2xl font-bold leading-tight text-ink sm:text-3xl">{title}</h3>
                        <p className="mt-3 max-w-2xl text-sm leading-7 text-slate-600 sm:text-base">{summary}</p>
                      </div>
                      <span className="hidden size-12 shrink-0 place-items-center rounded-full border border-ink/15 text-ink transition duration-500 group-hover/category:rotate-12 group-hover/category:border-brand-500 group-hover/category:bg-brand-500 sm:grid">
                        <Recycle className="size-5" aria-hidden="true" />
                      </span>
                    </div>

                    <ul className="mt-7 grid grid-cols-1 gap-x-7 sm:grid-cols-2 xl:grid-cols-3" aria-label={`${title} items`}>
                      {items.map((item) => (
                        <li
                          key={item}
                          className="group/item flex min-h-11 items-center gap-3 border-t border-ink/10 py-2.5 text-sm font-semibold text-slate-600 transition duration-300 hover:translate-x-1 hover:border-brand-500 hover:text-ink sm:text-[0.95rem]"
                        >
                          <Check className="size-4 shrink-0 text-brand-700 transition duration-300 group-hover/item:scale-110" strokeWidth={3} aria-hidden="true" />
                          {item}
                        </li>
                      ))}
                    </ul>
                  </div>
                </div>
              </article>
            ))}
          </div>
        </div>

        <div className="mt-12 flex flex-col gap-5 border-t border-ink/20 pt-8 sm:flex-row sm:items-center sm:justify-between">
          <div className="flex items-start gap-3">
            <Recycle className="mt-1 size-6 shrink-0 text-brand-700" aria-hidden="true" />
            <p className="max-w-2xl text-sm leading-7 text-slate-600 sm:text-base">
              We separate reusable and recyclable material wherever possible, helping household clearances create space more responsibly.
            </p>
          </div>
          <Link href="/#prices" className="group inline-flex shrink-0 items-center gap-2 text-sm font-extrabold text-ink underline decoration-brand-500 decoration-2 underline-offset-4 transition hover:text-brand-700 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink sm:text-base">
            Check collection prices
            <ArrowRight className="size-4 transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true" />
          </Link>
        </div>
      </div>
    </section>
  );
}
