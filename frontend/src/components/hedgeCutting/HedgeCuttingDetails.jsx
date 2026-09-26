import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, ShieldCheck } from 'lucide-react';

const services = [
  'Hedge trimming and shaping',
  'Height reduction for large or small hedges',
  'Detailed hedge shaping',
  'Topiary maintenance',
  'Complete hedge removal',
  'Hedge-stump removal',
];

export default function HedgeCuttingDetails() {
  return (
    <section className="bg-white">
      <div className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-12 lg:grid-cols-[.75fr_1.25fr] lg:gap-16">
          <div>
            <p className="text-xs font-extrabold uppercase tracking-[.18em] text-brand-700">
              Complete hedge service
            </p>

            <h2 className="mt-5 text-4xl font-bold leading-[0.95] text-ink sm:text-5xl uppercase">
              Cutting shaped around your requirements.
            </h2>

            <p className="mt-6 text-base leading-8 text-slate-600">
              Well-maintained hedges and shrubs improve the whole surrounding area. Our team provides cutting and pruning throughout the year.
            </p>

            <Link
              href="/contactUs"
              className="group mt-8 inline-flex items-center gap-2 font-bold text-ink underline decoration-brand-700 decoration-2 underline-offset-4 hover:text-brand-700"
            >
              Discuss your hedges
              <ArrowRight className="size-4 transition group-hover:translate-x-1" />
            </Link>
          </div>

          <ul className="grid gap-x-8 sm:grid-cols-2">
            {services.map((s) => (
              <li
                key={s}
                className="flex min-h-16 items-center gap-3 border-t border-ink/12 py-3 text-sm font-semibold text-slate-600 transition hover:translate-x-1 hover:border-brand-700"
              >
                <Check className="size-4 text-brand-700" strokeWidth={3} />
                {s}
              </li>
            ))}
          </ul>
        </div>

        <div className="mt-14 grid gap-4 lg:grid-cols-[1.25fr_.75fr]">
          <div className="group relative min-h-[400px] overflow-hidden rounded-[2rem] sm:min-h-[360px]">
            <Image
              src="/images/hedgeRubbish3.jpg"
              alt="Freshly shaped garden hedge"
              fill
              sizes="(max-width:1024px) 100vw,58vw"
              className="object-cover transition duration-700 group-hover:scale-105"
            />
          </div>

          <div className="grid gap-4">
            <div className="group relative min-h-[300px] overflow-hidden rounded-[2rem]">
              <Image
                src="/images/hedgeRubbish4.jpg"
                alt="Professional hedge cutting result"
                fill
                sizes="(max-width:1024px) 100vw,34vw"
                className="object-cover transition duration-700 group-hover:scale-110"
              />
            </div>

            <div className="rounded-[2rem] border border-sky-200 bg-sky-50 p-7 text-ink">
              <ShieldCheck className="size-7 text-brand-300" />
              <h3 className="mt-4 text-2xl font-bold">Experienced local care</h3>
              <p className="mt-3 text-sm leading-7 text-ink/70">
                Professional equipment, close attention to detail and a satisfaction-led finish.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}