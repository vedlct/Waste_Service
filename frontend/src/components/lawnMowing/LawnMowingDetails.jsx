import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, ShieldCheck } from 'lucide-react';

const services = [
  'Small domestic lawns',
  'Large residential gardens',
  'Commercial grounds contracts',
  'Season-appropriate cutting heights',
  'Professional striped finish',
  'Grass-cutting collection',
  'Weekly scheduled mowing',
  'Fortnightly maintenance visits',
];

const images = [1, 2, 3, 4];

export default function LawnMowingDetails() {
  return (
    <section className="bg-white">
      <div className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-10 lg:grid-cols-[.72fr_1.28fr] lg:gap-16">
          <div>
            <p className="text-xs font-extrabold uppercase tracking-[.18em] text-[#1a68a3]">
              Professional grass cutting
            </p>

            <h2 className="mt-5 text-3xl font-bold leading-[1.04] text-[#102a4c] sm:text-4xl">
              The right finish for every lawn size.
            </h2>

            <p className="mt-6 text-base leading-8 text-slate-600">
              Specialist equipment supports five cutting heights and a clean striped finish suited to the lawn and season.
            </p>

            <Link
              href="/contactUs"
              className="group mt-8 inline-flex items-center gap-2 font-bold text-[#102a4c] underline decoration-[#1a68a3] decoration-2 underline-offset-4 hover:text-[#1a68a3]"
            >
              Discuss your lawn
              <ArrowRight className="size-4 transition group-hover:translate-x-1" />
            </Link>
          </div>

          <div>
            <ul className="grid gap-x-8 sm:grid-cols-2">
              {services.map((s) => (
                <li
                  key={s}
                  className="flex min-h-14 items-center gap-3 border-t border-[#102a4c]/12 py-3 text-sm font-semibold text-[#38536b] transition hover:translate-x-1 hover:border-[#1a68a3]"
                >
                  <Check className="size-4 text-[#1a68a3]" strokeWidth={3} />
                  {s}
                </li>
              ))}
            </ul>
          </div>
        </div>

        <div className="mt-14 grid auto-rows-[180px] grid-cols-2 gap-3 sm:auto-rows-[230px] lg:grid-cols-4">
          {images.map((n, i) => (
            <div
              key={n}
              className={`group relative overflow-hidden rounded-[1.5rem] ${
                i === 0 || i === 3 ? 'sm:row-span-2' : ''
              }`}
            >
              <Image
                src={`/images/lawn${n}.jpg`}
                alt={`Professional lawn care result ${i + 1}`}
                fill
                sizes="(max-width:640px) 50vw,25vw"
                className="object-cover transition duration-700 group-hover:scale-110"
              />
              <div className="absolute inset-0 bg-[#102a4c]/10 transition group-hover:bg-transparent" />
            </div>
          ))}
        </div>

        <div className="mt-12 rounded-[2rem] border border-sky-200 bg-sky-50 p-7 text-[#102a4c] sm:p-9">
          <div className="flex items-center gap-3">
            <ShieldCheck className="size-7 text-[#7dc6f2]" />
            <h3 className="text-2xl font-bold sm:text-3xl">Why choose our lawn team</h3>
          </div>

          <p className="mt-4 max-w-3xl leading-7 text-[#102a4c]/70">
            Experienced local staff, professional equipment, close attention to detail and dependable value for scheduled lawn maintenance.
          </p>
        </div>
      </div>
    </section>
  );
}