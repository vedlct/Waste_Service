import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, Phone } from 'lucide-react';

export default function ServiceHero({ image, imageAlt, eyebrow, title, description, imagePosition = 'object-center', points = ['Flexible appointment times', 'Professional local service'] }) {
  return (
    <section className="relative isolate min-h-[620px] overflow-hidden bg-sky-50 sm:min-h-[660px] lg:min-h-[700px]">
      <Image src={image} alt={imageAlt} fill priority sizes="100vw" className={`-z-30 object-cover ${imagePosition}`} />

      {/* <div className="absolute inset-0 -z-20 bg-gradient-to-t from-[#071d3b]/80 via-[#071d3b]/15 to-[#071d3b]/15 lg:bg-[linear-gradient(90deg,rgba(7,29,59,0.08)_20%,rgba(7,29,59,0.2)_48%,rgba(7,29,59,0.72)_72%,rgba(7,29,59,0.92)_100%)]" /> */}

      <div className="mx-auto flex min-h-[620px] w-full max-w-7xl items-end px-4 py-8 sm:min-h-[660px] sm:px-6 sm:py-10 lg:min-h-[700px] lg:items-center lg:justify-end lg:px-8 lg:py-16">
        <div className="group relative w-full overflow-hidden rounded-[1.75rem] border border-sky-200 bg-white/70 p-6 text-[#102a4c] shadow-[0_28px_70px_-30px_rgba(2,14,33,0.9)] backdrop-blur-md transition duration-500 hover:-translate-y-1 hover:border-white/35 sm:p-8 lg:w-[min(36rem,48%)] lg:p-10">
          <div className="absolute left-0 top-0 h-full w-1.5 bg-[#ffd126]" />

          <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-[#ffd126]/50 sm:text-sm">
            <span className="h-px w-8 bg-[#ffd126]" />
            {eyebrow}
          </div>

          <h1 className="mt-5 text-3xl font-bold leading-[1.08] tracking-tight sm:text-4xl lg:text-4xl">{title}</h1>

          <p className="mt-5 text-sm leading-7 text-[#102a4c]/78 sm:text-base">{description}</p>

          <div className="mt-6 grid gap-2 sm:grid-cols-2">
            {points.map((point) => (
              <div key={point} className="flex items-center gap-2 text-xs font-semibold text-[#102a4c]/90 sm:text-sm">
                <span className="grid size-6 shrink-0 place-items-center rounded-full bg-[#ffd126] text-[#102a4c]">
                  <Check className="size-3.5" strokeWidth={3} />
                </span>
                {point}
              </div>
            ))}
          </div>

          <div className="mt-8 flex flex-col gap-3 sm:flex-row">
            <Link
              href="/#prices"
              className="group/cta inline-flex min-h-12 flex-1 items-center justify-center gap-2 rounded-full bg-[#ffd126] px-5 py-3 text-sm font-extrabold text-[#102a4c] transition duration-300 hover:-translate-y-0.5 hover:bg-white"
            >
              Check prices &amp; book
              <ArrowRight className="size-4 transition group-hover/cta:translate-x-1" />
            </Link>

            <a
              href="tel:02082266477"
              className="inline-flex min-h-12 items-center justify-center gap-2 rounded-full border border-white/30 px-5 py-3 text-sm font-bold transition duration-300 hover:border-white hover:bg-white hover:text-[#102a4c]"
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