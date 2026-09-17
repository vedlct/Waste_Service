import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight } from 'lucide-react';

export default function GetPrices() {
  return (
    <section className="group relative isolate flex min-h-[360px] w-full overflow-hidden bg-sky-50 sm:min-h-[400px] lg:min-h-[360px]">
      <Image
        src="/images/cleaning.jpg"
        alt="Professional clearance team ready for a responsible collection"
        fill
        sizes="100vw"
        className="-z-30 object-cover object-[52%_68%] transition duration-700 ease-out group-hover:scale-[1.025]"
      />
      <div className="absolute inset-0 -z-20 bg-[linear-gradient(90deg,rgba(7,29,59,0.98)_0%,rgba(7,29,59,0.88)_42%,rgba(7,29,59,0.4)_75%,rgba(7,29,59,0.18)_100%)]" />
      <div className="absolute inset-0 -z-20 bg-gradient-to-t from-[#071d3b]/55 via-transparent to-[#071d3b]/15" />

      <div className="mx-auto flex w-full max-w-7xl items-center px-4 py-10 sm:px-6 sm:py-12 lg:px-8">
        <div className="max-w-2xl">
          <span className="block font-serif text-3xl font-black leading-[0.6] text-[#ffd126] transition-transform duration-500 group-hover:-translate-y-1 sm:text-4xl" aria-hidden="true">
            &ldquo;
          </span>

          <h2 className="mt-4 text-3xl font-semibold italic leading-[1.18] tracking-tight text-white sm:text-4xl">
            Clear pricing makes clearing your space feel simple from the start.
          </h2>

          <div className="mt-5 flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.17em] text-[#ffd126] sm:text-sm">
            <span className="h-0.5 w-8 bg-[#ffd126] transition-all duration-500 group-hover:w-12" aria-hidden="true" />
            Choose your collection
          </div>

          <div className="mt-7 flex flex-col gap-3 sm:flex-row sm:items-center">
            <Link
              href="/#prices"
              className="group/cta inline-flex min-h-12 items-center justify-center gap-3 rounded-full bg-[#ffd126] px-6 py-3 text-sm font-extrabold text-[#102a4c] shadow-[0_14px_28px_-16px_rgba(255,209,38,0.8)] transition duration-300 hover:-translate-y-0.5 hover:bg-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white"
            >
              View prices &amp; book
              <ArrowRight className="size-4 transition-transform duration-300 group-hover/cta:translate-x-1" aria-hidden="true" />
            </Link>
            <p className="text-sm leading-6 text-white/75 sm:max-w-xs">
              Select the option that fits your clearance and book in a few simple steps.
            </p>
          </div>
        </div>
      </div>
    </section>
  );
}
