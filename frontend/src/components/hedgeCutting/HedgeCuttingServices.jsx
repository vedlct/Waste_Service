import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, Phone } from 'lucide-react';

export default function HedgeCuttingServices() {
  return (
    <section className="bg-[#f3f7fb]">
      <div className="mx-auto grid w-full max-w-7xl gap-12 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[.92fr_1.08fr] lg:items-center lg:gap-16 lg:px-8 lg:py-28">
        <div className="relative h-[360px] sm:h-[620px]">
          <div className="group absolute inset-y-0 left-0 w-[78%] overflow-hidden rounded-[2rem] shadow-xl">
            <Image
              src="/images/hedgeRubbish1.jpg"
              alt="Professionally maintained hedge"
              fill
              sizes="(max-width:1024px) 75vw,38vw"
              className="object-cover transition duration-700 group-hover:scale-105"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-[#071d3b]/50 to-transparent" />
          </div>

          <div className="group absolute bottom-[8%] right-0 h-[48%] w-[44%] overflow-hidden rounded-[1.75rem] border-6 border-[#f3f7fb] shadow-2xl">
            <Image
              src="/images/hedgeRubbish2.jpg"
              alt="Detailed hedge trimming"
              fill
              sizes="(max-width:1024px) 42vw,20vw"
              className="object-cover object-center transition duration-700 group-hover:scale-110"
            />
          </div>
        </div>

        <div>
          <p className="text-xs font-extrabold uppercase tracking-[.18em] text-[#1a68a3]">
            Year-round hedge care
          </p>

          <h2 className="mt-5 text-3xl font-bold leading-[1.04] text-[#102a4c] sm:text-4xl lg:text-4xl">
            Neat growth.
            <span className="block text-[#1a68a3]">A cleaner finish.</span>
          </h2>

          <p className="mt-6 text-base leading-8 text-slate-600 sm:text-lg">
            We shape and maintain hedges around your requirements, clear the trimmings and leave surrounding pavements and drives tidy.
          </p>

          <ul className="mt-7 space-y-3">
            {[
              'Visits scheduled as often as needed',
              'Trimmings removed responsibly',
              'Surrounding areas swept clean',
            ].map((p) => (
              <li key={p} className="flex gap-3 font-semibold text-[#38536b]">
                <span className="grid size-7 shrink-0 place-items-center rounded-full bg-[#dcecf8] text-[#1a68a3]">
                  <Check className="size-4" strokeWidth={3} />
                </span>
                {p}
              </li>
            ))}
          </ul>

          <div className="mt-8 flex flex-col gap-3 sm:flex-row">
            <Link
              href="/#prices"
              className="group inline-flex min-h-12 items-center justify-center gap-2 rounded-full bg-[#102a4c] px-6 py-3 text-sm font-extrabold text-white transition hover:bg-[#1a68a3]"
            >
              Check prices
              <ArrowRight className="size-4 transition group-hover:translate-x-1" />
            </Link>

            <a
              href="tel:02082266477"
              className="inline-flex min-h-12 items-center justify-center gap-2 rounded-full border border-[#102a4c]/20 px-6 py-3 text-sm font-bold text-[#102a4c] transition hover:bg-white"
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