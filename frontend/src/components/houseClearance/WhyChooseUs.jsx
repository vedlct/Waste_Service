import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, Clock3, Phone, Recycle, ShieldCheck } from 'lucide-react';

const benefits = [
  'Trusted London house clearance service',
  '95% of collected waste recycled',
  'Recognisable Wait & Load vehicles',
  'Reliable local Man & Van teams',
  'clearance appointments six days a week',
  'Friendly, experienced collection crews',
  'Fully insured and licensed waste carrier',
  'London and Home Counties coverage',
  'Clear, competitive local pricing',
];

export default function WhyChooseUs() {
  return (
    <section className="overflow-hidden bg-white">
      <div className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-center lg:gap-16">
          <div className="group relative min-h-[360px] overflow-hidden rounded-[2rem] bg-sky-50 shadow-[0_28px_65px_-35px_rgba(16,42,76,0.65)] sm:min-h-[380px]">
            <Image
              src="/images/chooseUs.jpg"
              alt="Prepared waste bags and collection equipment ready for responsible removal"
              fill
              sizes="(max-width: 1024px) 100vw, 44vw"
              className="object-cover object-center transition duration-700 ease-out group-hover:scale-105"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-[#071d3b]/85 via-transparent to-transparent" />

            <div className="absolute inset-x-5 bottom-5 rounded-2xl border border-sky-200 bg-white/90 p-5 text-[#102a4c] shadow-lg backdrop-blur-md sm:inset-x-7 sm:bottom-7 sm:p-6">
              <div className="flex items-center gap-4">
                <span className="grid size-12 shrink-0 place-items-center rounded-full bg-[#ffd126] text-[#102a4c]">
                  <Recycle className="size-6" aria-hidden="true" />
                </span>
                <div>
                  <p className="text-2xl font-black leading-none sm:text-3xl">95%</p>
                  <p className="mt-1 text-sm text-[#102a4c]/75">of collected waste is recycled</p>
                </div>
              </div>
            </div>
          </div>

          <div>
            <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-[#b56808] sm:text-sm">
              <span className="h-0.5 w-9 bg-[#f0aa26]" aria-hidden="true" />
              Why choose our team
            </div>

            <h2 className="mt-5 text-3xl font-bold leading-[1.05] tracking-tight text-[#102a4c] sm:text-4xl lg:text-4xl">
              clearance built around
              <span className="mt-1 block text-[#d67d0b]">care, reliability and value.</span>
            </h2>

            <p className="mt-6 max-w-2xl text-base leading-8 text-slate-600 sm:text-lg">
              Clearing a Home can feel like a large job. We make it manageable with dependable crews, flexible availability and responsible handling from collection to disposal.
            </p>

            <ul className="mt-8 grid gap-x-6 gap-y-3 sm:grid-cols-2" aria-label="Reasons to choose London waste Services">
              {benefits.map((benefit) => (
                <li
                  key={benefit}
                  className="group/item flex min-h-14 items-center gap-3 rounded-xl border border-[#102a4c]/8 bg-[#f7f8f4] px-4 py-3 text-sm font-semibold leading-5 text-[#294761] transition duration-300 hover:-translate-y-0.5 hover:border-[#f0aa26]/45 hover:bg-[#fff9e9] hover:shadow-md"
                >
                  <span className="grid size-7 shrink-0 place-items-center rounded-full border border-sky-200 bg-sky-50 text-[#102a4c] transition duration-300 group-hover/item:bg-[#f0aa26] group-hover/item:text-[#102a4c]">
                    <Check className="size-4" strokeWidth={3} aria-hidden="true" />
                  </span>
                  {benefit}
                </li>
              ))}
            </ul>

            <Link href="/faq" className="group mt-7 inline-flex items-center gap-2 text-sm font-extrabold text-[#102a4c] underline decoration-[#f0aa26] decoration-2 underline-offset-4 transition hover:text-[#d67d0b] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#102a4c] sm:text-base">
              Read helpful information and FAQs
              <ArrowRight className="size-4 transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true" />
            </Link>
          </div>
        </div>

        <div className="group relative isolate mt-16 min-h-[360px] overflow-hidden rounded-[2rem] bg-sky-50 shadow-[0_28px_70px_-38px_rgba(16,42,76,0.75)] lg:mt-24 lg:min-h-[360px]">
          <Image
            src="/images/choose2.jpg"
            alt="Recyclable cardboard prepared for collection"
            fill
            sizes="(max-width: 1280px) 100vw, 1280px"
            className="-z-20 object-cover object-center transition duration-700 ease-out group-hover:scale-[1.03]"
          />
          <div className="absolute inset-0 -z-10 bg-[linear-gradient(90deg,rgba(7,29,59,0.98)_0%,rgba(7,29,59,0.88)_48%,rgba(7,29,59,0.46)_78%,rgba(7,29,59,0.26)_100%)]" />

          <div className="flex min-h-[360px] items-center p-6 sm:p-10 lg:min-h-[360px] lg:p-14">
            <div className="max-w-2xl">
              <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-[#ffd126] sm:text-sm">
                <Clock3 className="size-5" aria-hidden="true" />
                When timing matters
              </div>

              <h3 className="mt-5 text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-4xl">
                24-hour house clearance availability in Chingford
              </h3>

              <p className="mt-6 text-base leading-8 text-white/78 sm:text-lg">
                Residential customers, letting agents and solicitors sometimes need a quick turnaround. Subject to availability, our team can often arrange collection within 24 hours.
              </p>
              <p className="mt-3 text-sm leading-7 text-white/65 sm:text-base">
                Fast appointments can fill quickly, so contact us early and we will confirm the most suitable collection window for your property.
              </p>

              <div className="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                <a href="tel:02082266477" className="group/call inline-flex min-h-13 items-center justify-center gap-3 rounded-full bg-[#ffd126] px-6 py-3.5 text-sm font-extrabold text-[#102a4c] transition duration-300 hover:-translate-y-0.5 hover:bg-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white sm:text-base">
                  <Phone className="size-5" aria-hidden="true" />
                  call 020 8226 6477
                  <ArrowRight className="size-4 transition-transform duration-300 group-hover/call:translate-x-1" aria-hidden="true" />
                </a>

                <div className="flex items-center justify-center gap-2 text-sm font-semibold text-white/85 sm:justify-start">
                  <ShieldCheck className="size-5 text-[#ffd126]" aria-hidden="true" />
                  Fully insured &amp; licensed
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
