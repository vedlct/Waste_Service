import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, Recycle } from 'lucide-react';

const categories = [
  {
    number: '01',
    title: 'Furniture & living spaces',
    summary: 'Everyday furniture, storage and bulky pieces from bedrooms, lounges and dining areas.',
    items: [
      'Armchairs', 'Chests of Drawers', 'Lamps', 'Sofas', 'Coffee Tables',
      'Mattresses', 'Tables', 'Console Tables', 'Office Chairs', 'Bed Frames',
      'Bedside Tables', 'Wardrobes', 'Cushions', 'Book Cases', 'Desks',
      'Dining Room Chairs', 'Dining Tables', 'Kitchen Tables', 'Shelves', 'Chairs',
      'Conservatory Furniture', 'Cupboards', 'Gaming Chairs', 'Cabinets', 'Rugs', 'Carpets',
    ],
  },
  {
    number: '02',
    title: 'Kitchen & appliances',
    summary: 'Large and small household appliances alongside fitted and freestanding kitchen items.',
    items: [
      'Extractor Fans', 'Freezers', 'Microwaves', 'Fridges', 'Cookers',
      'Dishwashers', 'Ovens', 'Washing Machines', 'Kitchen Cupboards',
      'Kitchen Lighting', 'Kitchen Sinks',
    ],
  },
  {
    number: '03',
    title: 'Bathroom & renovation waste',
    summary: 'Fixtures, fittings and material left behind by refurbishment, stripping or improvement work.',
    items: [
      'Bath Frames', 'Floor Tiles', 'Bathroom Sinks', 'Bathroom Stripping',
      'Wall Tiles', 'Office Stripping', 'Wallpaper', 'Window Frames', 'Windows',
      'Kitchen Stripping', 'Skirting', 'Laminate Flooring', 'Radiators',
      'Builders Waste', 'Blinds', 'Curtain Doors',
    ],
  },
  {
    number: '04',
    title: 'Technology & household extras',
    summary: 'Home electronics, entertainment equipment and miscellaneous items that are difficult to move alone.',
    items: [
      'Sound Systems', 'TVs', 'Games Consoles', 'PCs', 'Printers',
      'Desktop Monitors', 'Bikes', 'Mirrors', 'Garages',
    ],
  },
];

const highlightImages = [
  { src: '/images/highlight1.jpg', alt: 'Household waste prepared for collection' },
  { src: '/images/highlight2.jpg', alt: 'Household items ready for responsible removal' },
  { src: '/images/highlight3.jpg', alt: 'Clearance materials organised for collection' },
  { src: '/images/highlight4.jpg', alt: 'Waste clearance team handling a collection' },
];

export default function HouseRubbishItems() {
  return (
    <section className="overflow-hidden bg-[#f6f3eb]">
      <div className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-10 lg:grid-cols-[0.72fr_1.28fr] lg:gap-16">
          <div className="lg:sticky lg:top-28 lg:self-start">
            <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-[#b56808] sm:text-sm">
              <span className="h-0.5 w-9 bg-[#f0aa26]" aria-hidden="true" />
              What we take
            </div>

            <h2 className="mt-5 text-3xl font-bold leading-[1.04] tracking-tight text-[#102a4c] sm:text-4xl lg:text-4xl">
              One collection.
              <span className="block text-[#d67d0b]">More space to use.</span>
            </h2>

            <p className="mt-6 max-w-lg text-base leading-8 text-slate-600 sm:text-lg">
              From furniture and appliances to renovation materials, we clear a wide range of household items throughout Chingford.
            </p>

            <div className="mt-8 border-l-2 border-[#f0aa26] pl-5">
              <p className="text-sm font-bold leading-6 text-[#102a4c] sm:text-base">
                Cannot see your item listed?
              </p>
              <p className="mt-2 text-sm leading-6 text-slate-600">
                This directory is only a guide. Tell our team what you have and we will confirm whether it can be collected.
              </p>
            </div>

            <Link
              href="/contactUs"
              className="group mt-8 inline-flex min-h-12 items-center justify-center gap-3 rounded-full bg-[#102a4c] px-6 py-3 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-[#f0aa26] hover:text-[#102a4c] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#102a4c]"
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
                    className={`group/image absolute overflow-hidden border-4 border-[#f6f3eb] bg-[#102a4c] shadow-[0_20px_45px_-28px_rgba(16,42,76,0.75)] transition duration-500 hover:z-20 hover:-translate-y-1 hover:shadow-[0_28px_55px_-25px_rgba(16,42,76,0.65)] ${positions[index]}`}
                  >
                    <Image
                      src={src}
                      alt={alt}
                      fill
                      sizes="(max-width: 1024px) 65vw, 360px"
                      className="object-cover transition duration-700 ease-out group-hover/image:scale-110"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-[#071d3b]/35 via-transparent to-transparent" />
                    <span className="absolute bottom-3 left-3 grid size-9 place-items-center rounded-full bg-[#ffd126] text-[#102a4c] shadow-md transition duration-500 group-hover/image:rotate-12">
                      <Recycle className="size-4" aria-hidden="true" />
                    </span>
                  </div>
                );
              })}
            </div>
          </div>

          <div className="border-t border-[#102a4c]/20">
            {categories.map(({ number, title, summary, items }) => (
              <article key={number} className="group/category border-b border-[#102a4c]/20 py-8 sm:py-10">
                <div className="grid gap-5 sm:grid-cols-[4rem_1fr] sm:gap-6">
                  <span className="text-sm font-black tracking-[0.16em] text-[#d67d0b]">{number}</span>

                  <div>
                    <div className="flex items-start justify-between gap-4">
                      <div>
                        <h3 className="text-2xl font-bold leading-tight text-[#102a4c] sm:text-3xl">{title}</h3>
                        <p className="mt-3 max-w-2xl text-sm leading-7 text-slate-600 sm:text-base">{summary}</p>
                      </div>
                      <span className="hidden size-12 shrink-0 place-items-center rounded-full border border-[#102a4c]/15 text-[#102a4c] transition duration-500 group-hover/category:rotate-12 group-hover/category:border-[#f0aa26] group-hover/category:bg-[#f0aa26] sm:grid">
                        <Recycle className="size-5" aria-hidden="true" />
                      </span>
                    </div>

                    <ul className="mt-7 grid grid-cols-1 gap-x-7 sm:grid-cols-2 xl:grid-cols-3" aria-label={`${title} items`}>
                      {items.map((item) => (
                        <li
                          key={item}
                          className="group/item flex min-h-11 items-center gap-3 border-t border-[#102a4c]/10 py-2.5 text-sm font-semibold text-[#38536b] transition duration-300 hover:translate-x-1 hover:border-[#f0aa26] hover:text-[#102a4c] sm:text-[0.95rem]"
                        >
                          <Check className="size-4 shrink-0 text-[#d67d0b] transition duration-300 group-hover/item:scale-110" strokeWidth={3} aria-hidden="true" />
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

        <div className="mt-12 flex flex-col gap-5 border-t border-[#102a4c]/20 pt-8 sm:flex-row sm:items-center sm:justify-between">
          <div className="flex items-start gap-3">
            <Recycle className="mt-1 size-6 shrink-0 text-[#d67d0b]" aria-hidden="true" />
            <p className="max-w-2xl text-sm leading-7 text-slate-600 sm:text-base">
              We separate reusable and recyclable material wherever possible, helping household clearances create space more responsibly.
            </p>
          </div>
          <Link href="/#prices" className="group inline-flex shrink-0 items-center gap-2 text-sm font-extrabold text-[#102a4c] underline decoration-[#f0aa26] decoration-2 underline-offset-4 transition hover:text-[#d67d0b] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#102a4c] sm:text-base">
            Check collection prices
            <ArrowRight className="size-4 transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true" />
          </Link>
        </div>
      </div>
    </section>
  );
}
