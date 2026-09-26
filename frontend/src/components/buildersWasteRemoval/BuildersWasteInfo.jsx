import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, Clock3, Phone } from 'lucide-react';

const types = [
  'Bathroom Fixtures',
  'Kitchens & Appliances',
  'Timber and Wood',
  'Windows & Doors',
  'Garden Waste',
  'Plasterboard',
  'Rubble and Brickwork',
  'Flooring',
  'Conservatories',
  'and more',
];

export default function BuildersWasteInfo() {
  return (
    <section className="bg-mist">
      <div className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-10 lg:grid-cols-[.8fr_1.2fr] lg:gap-16">
          <div>
            <p className="text-xs font-extrabold uppercase tracking-[.18em] text-brand-700">What we collect</p>

            <h2 className="mt-5 text-4xl font-bold leading-[0.95] text-ink sm:text-5xl uppercase">
              Building Waste expertise for every project size.
            </h2>

            <p className="mt-6 text-base leading-8 text-slate-600">
              Keeping rubble and renovation Waste under control improves site safety and productivity. Our licensed teams support homeowners and trades across domestic and commercial work.
            </p>
          </div>

          <ul className="grid border-t border-ink/15 sm:grid-cols-2">
            {types.map((t) => (
              <li
                key={t}
                className="group flex min-h-14 items-center gap-3 border-b border-ink/15 p-3 text-sm font-semibold text-slate-600 transition hover:bg-white hover:pl-5 hover:text-ink"
              >
                <Check className="size-4 text-brand-700" strokeWidth={3} />
                {t}
              </li>
            ))}
          </ul>
        </div>

        <div className="group relative isolate mt-16 min-h-[360px] overflow-hidden rounded-[2rem] bg-sky-50">
          <Image
            src="/images/Waste4.jpg"
            alt="Builders Waste collection in progress"
            fill
            sizes="(max-width:1280px) 100vw,1280px"
            className="-z-20 object-cover transition duration-700 group-hover:scale-[1.03]"
          />

          <div className="absolute inset-0 -z-10 bg-[linear-gradient(90deg,rgba(15,37,87,.97),rgba(15,37,87,.78)_55%,rgba(15,37,87,.25))]" />

          <div className="flex min-h-[360px] items-center p-7 sm:p-10 lg:p-14">
            <div className="max-w-2xl text-white">
              <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[.18em] text-brand-500">
                <Clock3 className="size-5" />
                Fast collection availability
              </div>

              <h3 className="mt-5 text-3xl font-bold sm:text-4xl lg:text-4xl">
                Same-day Builders Waste collection may be available.
              </h3>

              <p className="mt-6 text-base leading-8 text-white/80 sm:text-lg">
                we understand that homeowners, Builders and fitters sometimes need a quick turnaround. Contact us early to confirm the best available collection window.
              </p>

              <div className="mt-8 flex flex-col gap-3 sm:flex-row">
                <a
                  href="tel:07862138033"
                  className="inline-flex min-h-12 items-center justify-center gap-2 rounded-full bg-brand-500 px-6 py-3 font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-white"
                >
                  <Phone className="size-4" />
                  078 6213 8033
                </a>

                <Link
                  href="/#prices"
                  className="group/link inline-flex min-h-12 items-center justify-center gap-2 rounded-full border border-white/50 px-6 py-3 font-bold text-white transition hover:bg-white hover:text-ink"
                >
                  View prices
                  <ArrowRight className="size-4 transition group-hover/link:translate-x-1" />
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}