import Link from 'next/link';
import { ArrowUpRight, Clock3, PoundSterling, Recycle, Truck } from 'lucide-react';

const highlights = [
  {
    number: '01',
    eyebrow: 'Collection',
    title: 'Man & Van rubbish removal',
    description:
      'A practical alternative to hiring a skip. Our team arrives, loads your unwanted items and takes them away in one organised visit.',
    detail: 'We do the lifting',
    icon: Truck,
  },
  {
    number: '02',
    eyebrow: 'Responsibility',
    title: '95% of waste recycled',
    description:
      'Collected materials are carefully directed towards reuse and recycling wherever possible, helping to reduce unnecessary landfill.',
    detail: 'Responsible disposal',
    icon: Recycle,
  },
  {
    number: '03',
    eyebrow: 'Availability',
    title: 'Clearance within 24 hours',
    description:
      'When timing is important, we can often arrange a fast collection subject to team availability and the size of your clearance.',
    detail: 'Fast local response',
    icon: Clock3,
  },
  {
    number: '04',
    eyebrow: 'Booking',
    title: 'Simple options. Clear prices.',
    description:
      'Choose the service that matches your load, understand the price before booking and select a collection time that works for you.',
    detail: 'No complicated process',
    icon: PoundSterling,
  },
];

export default function ServiceHighlights() {
  return (
    <section className="relative overflow-hidden border-y border-sky-100 bg-sky-50 text-[#102a4c]">
      <div className="pointer-events-none absolute -left-28 top-1/3 size-72 rounded-full border-[3.5rem] border-white/3" aria-hidden="true" />
      <div className="pointer-events-none absolute -right-16 -top-16 size-64 rounded-full border-[3rem] border-[#ffd126]/6" aria-hidden="true" />

      <div className="relative mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-6 border-b border-white/15 pb-10 lg:grid-cols-[0.8fr_1.2fr] lg:items-end lg:gap-16 lg:pb-14">
          <div>
            <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.19em] text-[#ffd126] sm:text-sm">
              <span className="h-0.5 w-9 bg-[#ffd126]" aria-hidden="true" />
              The service difference
            </div>
            <h2 className="mt-5 text-3xl font-bold leading-[1.04] tracking-tight sm:text-4xl lg:text-4xl">
              Less hassle at
              <span className="block font-serif font-medium italic text-[#ffd126]">every stage.</span>
            </h2>
          </div>

          <p className="max-w-2xl text-base leading-8 text-[#102a4c]/65 sm:text-lg">
            House clearance should feel organised, not overwhelming. These four commitments shape how we collect, communicate and handle your unwanted items.
          </p>
        </div>

        <ol className="relative mt-8 space-y-4">
          {highlights.map(({ number, eyebrow, title, description, detail, icon: Icon }) => (
            <li
              key={number}
              className="group relative overflow-hidden rounded-2xl border border-sky-300 bg-white/75 px-4 shadow-sm ring-1 ring-sky-200/80 transition-all duration-500 hover:-translate-y-1 hover:border-[#F4B942] hover:bg-[#FFF8D9] hover:text-[#081f3d] hover:shadow-xl hover:ring-2 hover:ring-[#F4B942]/50 sm:px-6"
            >
              <div className="grid gap-5 py-8 transition-transform duration-500 group-hover:translate-x-1 sm:grid-cols-[4rem_1fr] sm:items-start sm:gap-6 lg:grid-cols-[5rem_4.5rem_1fr_1.05fr_auto] lg:items-center lg:gap-8 lg:py-9">
                <span className="inline-flex w-fit rounded-full border border-[#F4B942]/45 bg-[#FFF4C2] px-3 py-1.5 text-sm font-black tracking-[0.16em] text-[#B97800] transition-colors duration-300 group-hover:text-[#081f3d]/55">
                  {number}
                </span>

                <span className="hidden size-14 place-items-center rounded-full border-2 border-sky-300 bg-sky-50 text-[#E5A900] shadow-sm ring-4 ring-white/80 transition duration-500 group-hover:rotate-6 group-hover:border-[#081f3d]/20 group-hover:bg-[#081f3d] group-hover:text-[#ffd126] sm:grid lg:size-16">
                  <Icon className="size-6 lg:size-7" strokeWidth={1.8} aria-hidden="true" />
                </span>

                <div className="sm:col-start-2 lg:col-start-auto">
                  <p className="text-[0.68rem] font-extrabold uppercase tracking-[0.18em] text-[#102a4c]/45 transition-colors duration-300 group-hover:text-[#081f3d]/55">
                    {eyebrow}
                  </p>
                  <h3 className="mt-2 text-2xl font-bold leading-tight sm:text-3xl lg:text-[2rem]">{title}</h3>
                </div>

                <p className="text-sm leading-7 text-[#102a4c]/62 transition-colors duration-300 group-hover:text-[#081f3d]/75 sm:col-start-2 sm:text-base lg:col-start-auto">
                  {description}
                </p>

                <div className="flex items-center justify-between gap-4 sm:col-start-2 lg:col-start-auto lg:block lg:text-right">
                  <span className="text-xs font-bold uppercase tracking-[0.12em] text-[#102a4c]/45 transition-colors duration-300 group-hover:text-[#081f3d]/65">
                    {detail}
                  </span>
                  <ArrowUpRight className="mt-0 size-6 text-[#ffd126] transition duration-500 group-hover:translate-x-1 group-hover:-translate-y-1 group-hover:text-[#081f3d] lg:ml-auto lg:mt-3" aria-hidden="true" />
                </div>
              </div>
            </li>
          ))}
        </ol>

        <div className="mt-10 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
          <p className="max-w-xl text-sm leading-7 text-[#102a4c]/60 sm:text-base">
            Need help choosing the right clearance option? Start with our straightforward pricing guide.
          </p>
          <Link
            href="/#prices"
            className="group inline-flex min-h-13 shrink-0 items-center justify-center gap-3 rounded-full bg-white px-6 py-3.5 text-sm font-extrabold text-[#081f3d] transition duration-300 hover:-translate-y-0.5 hover:bg-[#ffd126] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white sm:text-base"
          >
            Explore prices &amp; book
            <ArrowUpRight className="size-5 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" aria-hidden="true" />
          </Link>
        </div>
      </div>
    </section>
  );
}
