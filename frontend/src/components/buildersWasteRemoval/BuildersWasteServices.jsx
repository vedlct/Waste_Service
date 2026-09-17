import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, HardHat } from 'lucide-react';

export default function BuildersWasteServices() {
  const points = [
    'Small rubble piles to complete site clearances',
    'Collections during or after your building project',
    'Times arranged around contractors and site access',
  ];

  return (
    <section className="bg-[#f7f8f4]">
      <div className="mx-auto grid w-full max-w-7xl gap-0 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[1.05fr_0.95fr] lg:px-8 lg:py-28">
        <div className="group relative min-h-[360px] overflow-hidden rounded-t-[2rem] lg:min-h-[650px] lg:rounded-l-[2rem] lg:rounded-tr-none">
          <Image
            src="/images/builderRubbish.jpg"
            alt="Builders waste prepared for professional removal"
            fill
            sizes="(max-width:1024px) 100vw,52vw"
            className="object-cover transition duration-700 group-hover:scale-105"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-[#071d3b]/75 via-transparent to-transparent" />
          <div className="absolute bottom-6 left-6 flex items-center gap-3 rounded-2xl bg-white/90 p-4 text-[#102a4c] backdrop-blur-md">
            <HardHat className="size-7 text-[#ffd126]" />
            <span className="font-bold">Site-ready collection teams</span>
          </div>
        </div>

        <div className="flex flex-col justify-center rounded-b-[2rem] bg-white p-7 shadow-xl sm:p-10 lg:rounded-r-[2rem] lg:rounded-bl-none lg:p-12">
          <p className="text-xs font-extrabold uppercase tracking-[.18em] text-[#b56808]">Builders waste removal</p>

          <h2 className="mt-5 text-3xl font-bold leading-[1.05] text-[#102a4c] sm:text-4xl">
            Keep the project moving.
            <span className="block text-[#d67d0b]">We clear what is left behind.</span>
          </h2>

          <p className="mt-6 text-base leading-8 text-slate-600 sm:text-lg">
            From bathroom refits to commercial works, we remove builders waste with minimal disruption to homeowners and trades.
          </p>

          <ul className="mt-7 space-y-3">
            {points.map((p) => (
              <li key={p} className="flex gap-3 text-sm font-semibold text-[#38536b] sm:text-base">
                <span className="grid size-7 shrink-0 place-items-center rounded-full bg-[#fff0cb] text-[#b56808]">
                  <Check className="size-4" strokeWidth={3} />
                </span>
                {p}
              </li>
            ))}
          </ul>

          <Link
            href="/#prices"
            className="group mt-9 inline-flex min-h-12 items-center justify-center gap-3 rounded-full bg-[#102a4c] px-6 py-3 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-[#f0aa26] hover:text-[#102a4c]"
          >
            Check removal prices
            <ArrowRight className="size-4 transition group-hover:translate-x-1" />
          </Link>
        </div>
      </div>
    </section>
  );
}