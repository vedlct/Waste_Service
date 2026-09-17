import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, Phone } from 'lucide-react';

export default function GarageRubbishClearance() {
  return (
    <section className="bg-white">
      <div className="mx-auto grid w-full max-w-7xl gap-0 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-2 lg:px-8 lg:py-28">
        <div className="group relative min-h-[360px] overflow-hidden rounded-t-[2rem] bg-sky-50 lg:min-h-[620px] lg:rounded-l-[2rem] lg:rounded-tr-none">
          <Image
            src="/images/garageRubbish.jpg"
            alt="Garage ready for rubbish clearance"
            fill
            sizes="(max-width:1024px) 100vw,50vw"
            className="object-cover transition duration-700 group-hover:scale-105"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-[#071d3b]/75 via-transparent to-transparent" />
          <div className="absolute bottom-6 left-6 right-6 rounded-2xl border border-sky-200 bg-white/90 p-5 text-[#102a4c] backdrop-blur-md sm:bottom-8 sm:left-8 sm:right-auto sm:max-w-sm">
            <p className="text-xs font-extrabold uppercase tracking-[0.16em] text-[#ffd126]">Local collection team</p>
            <p className="mt-2 text-lg font-bold">Branded vehicles. Experienced crews. Heavy lifting included.</p>
          </div>
        </div>

        <div className="flex flex-col justify-center rounded-b-[2rem] border border-sky-200 bg-sky-50 p-7 text-[#102a4c] sm:p-10 lg:rounded-r-[2rem] lg:rounded-bl-none lg:p-12">
          <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-[#ffd126] sm:text-sm">
            <span className="h-0.5 w-9 bg-[#ffd126]" />
            Garage &amp; shed clearance
          </div>

          <h2 className="mt-5 text-3xl font-bold leading-tight sm:text-4xl lg:text-4xl">
            Turn an overcrowded Garage back into useful space.
          </h2>

          <p className="mt-6 text-base leading-8 text-[#102a4c]/72 sm:text-lg">
            Whether junk is blocking the entrance or the whole Garage needs clearing, our team arrives with the right equipment to load and remove it safely.
          </p>

          <ul className="mt-7 space-y-3">
            {[
              'Single bulky items or complete clear-outs',
              'Garage and shed collections across Greater London',
              'Same-day availability may be possible',
            ].map((item) => (
              <li key={item} className="flex items-center gap-3 text-sm font-semibold text-[#102a4c]/90 sm:text-base">
                <span className="grid size-7 place-items-center rounded-full bg-[#ffd126] text-[#102a4c]">
                  <Check className="size-4" strokeWidth={3} />
                </span>
                {item}
              </li>
            ))}
          </ul>

          <div className="mt-9 flex flex-col gap-3 sm:flex-row">
            <Link
              href="/#prices"
              className="group inline-flex min-h-12 flex-1 items-center justify-center gap-2 rounded-full bg-[#ffd126] px-5 py-3 text-sm font-extrabold text-[#102a4c] transition duration-300 hover:-translate-y-0.5 hover:bg-white"
            >
              View prices
              <ArrowRight className="size-4 transition group-hover:translate-x-1" />
            </Link>

            <a
              href="tel:02082266477"
              className="inline-flex min-h-12 items-center justify-center gap-2 rounded-full border border-white/30 px-5 py-3 text-sm font-bold transition duration-300 hover:border-white hover:bg-white hover:text-[#102a4c]"
            >
              <Phone className="size-4" />
              020 8226 6477
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}