import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, Phone } from 'lucide-react';

const images = [
  ['lawnRubbish1.jpg', 'Professional lawn mowing'],
  ['lawnRubbish2.jpg', 'Carefully maintained grass'],
  ['lawnRubbish3.jpg', 'Freshly cut lawn'],
];

export default function LawnMowingServices() {
  return (
    <section className="bg-mist">
      <div className="mx-auto grid w-full max-w-7xl gap-12 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[.9fr_1.1fr] lg:items-center lg:gap-16 lg:px-8 lg:py-28">
        <div className="relative h-[360px] sm:h-[600px]">
          {images.map(([name, alt], i) => {
            const pos = [
              'left-0 top-0 h-[62%] w-[76%]',
              'right-0 top-[18%] h-[42%] w-[42%]',
              'bottom-0 left-[12%] h-[42%] w-[62%]',
            ];
            return (
              <div
                key={name}
                className={`group absolute overflow-hidden rounded-[2rem] border-6 border-mist shadow-xl transition hover:z-20 hover:-translate-y-1 ${pos[i]}`}
              >
                <Image
                  src={`/images/${name}`}
                  alt={alt}
                  fill
                  sizes="(max-width:1024px) 70vw,35vw"
                  className="object-cover transition duration-700 group-hover:scale-110"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-brand-950/35 to-transparent" />
              </div>
            );
          })}
        </div>

        <div>
          <p className="text-xs font-extrabold uppercase tracking-[.18em] text-brand-700">
            Reliable lawn care
          </p>

          <h2 className="mt-5 text-4xl font-bold leading-[0.95] text-ink sm:text-5xl lg:text-5xl uppercase">
            A consistent cut.
            <span className="block text-brand-700">More time for you.</span>
          </h2>

          <p className="mt-6 text-base leading-8 text-slate-600 sm:text-lg">
            Choose weekly or fortnightly visits on a reliable schedule. With access arranged, you do not need to be home when our team attends.
          </p>

          <ul className="mt-7 space-y-3">
            {[
              'Regular weekly or fortnightly visits',
              'Grass cuttings removed and recycled',
              'Domestic lawns and larger grounds',
            ].map((p) => (
              <li key={p} className="flex gap-3 font-semibold text-slate-600">
                <span className="grid size-7 place-items-center rounded-full bg-brand-100 text-brand-700">
                  <Check className="size-4" strokeWidth={3} />
                </span>
                {p}
              </li>
            ))}
          </ul>

          <div className="mt-8 flex flex-col gap-3 sm:flex-row">
            <Link
              href="/#prices"
              className="group inline-flex min-h-12 items-center justify-center gap-2 rounded-full bg-brand-900 px-6 py-3 text-sm font-extrabold text-white transition hover:bg-brand-700"
            >
              Check prices
              <ArrowRight className="size-4 transition group-hover:translate-x-1" />
            </Link>

            <a
              href="tel:07862138033"
              className="inline-flex min-h-12 items-center justify-center gap-2 rounded-full border border-ink/20 px-6 py-3 text-sm font-bold text-ink transition hover:bg-white"
            >
              <Phone className="size-4" />
              Call us
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}