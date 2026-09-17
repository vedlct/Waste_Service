import Link from 'next/link';
import {
  ArrowRight,
  Boxes,
  Check,
  Home,
  Phone,
  Sofa,
  Warehouse,
} from 'lucide-react';

const clearanceOptions = [
  {
    icon: Sofa,
    number: '01',
    title: 'Single items & rooms',
    description:
      'Ideal when you need to remove a bulky sofa, appliance, bedroom set or the contents of one room without hiring a skip.',
    examples: ['Furniture and appliances', 'Bedrooms and living spaces'],
  },
  {
    icon: Home,
    number: '02',
    title: 'Full house clearance',
    description:
      'A complete, carefully managed clearance for houses, flats, rental properties and homes being prepared for sale or renovation.',
    examples: ['Whole-property clearance', 'Lofts, cupboards and storage'],
  },
  {
    icon: Warehouse,
    number: '03',
    title: 'Garages & outbuildings',
    description:
      'We clear accumulated household rubbish and bulky items from garages, sheds, basements and other hard-to-manage spaces.',
    examples: ['Garages, sheds and basements', 'Mixed household rubbish'],
  },
];

const processSteps = [
  ['Tell us what needs clearing', 'Share the items, rooms or property size so we can guide you to the right option.'],
  ['Choose a convenient time', 'Select an available collection time that works around your home and schedule.'],
  ['We clear and tidy the space', 'Our team completes the lifting, loading and responsible removal for you.'],
];

export default function HouseClearanceServices() {
  return (
    <section className="relative overflow-hidden bg-[#f7f8f4]">
      <div className="pointer-events-none absolute -right-28 top-16 size-80 rounded-full border-[3rem] border-[#ffd126]/12" aria-hidden="true" />

      <div className="relative mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-8 lg:grid-cols-[0.85fr_1.15fr] lg:items-end lg:gap-16">
          <div>
            <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-[#b56808] sm:text-sm">
              <span className="h-0.5 w-9 bg-[#f0aa26]" aria-hidden="true" />
              What we clear
            </div>
            <h2 className="mt-5 text-3xl font-bold leading-[1.05] tracking-tight text-[#102a4c] sm:text-4xl lg:text-4xl">
              House clearance,
              <span className="mt-1 block text-[#d67d0b]">handled from start to finish.</span>
            </h2>
          </div>

          <div className="border-l-2 border-[#f0aa26] pl-5 sm:pl-7">
            <p className="text-lg leading-8 text-slate-600 sm:text-xl">
              From one unwanted item to the contents of an entire property, our Chingford clearance team manages the hard work so you do not have to.
            </p>
            <p className="mt-4 text-base leading-7 text-slate-500">
              We make every collection straightforward, respectful and efficientâ€”with clear options, careful handling and responsible disposal throughout.
            </p>
          </div>
        </div>

        <div className="mt-12 grid gap-5 md:grid-cols-3 lg:mt-16 lg:gap-6">
          {clearanceOptions.map(({ icon: Icon, number, title, description, examples }) => (
            <article
              key={title}
              className="group relative flex min-h-full flex-col overflow-hidden rounded-[1.75rem] border border-[#102a4c]/10 bg-white p-6 shadow-[0_18px_50px_-34px_rgba(16,42,76,0.45)] transition duration-500 hover:-translate-y-2 hover:border-[#f0aa26]/55 hover:shadow-[0_28px_60px_-30px_rgba(16,42,76,0.4)] sm:p-7"
            >
              <div className="flex items-start justify-between gap-4">
                <span className="grid size-14 place-items-center rounded-2xl border border-sky-200 bg-sky-50 text-[#102a4c] transition duration-500 group-hover:rotate-3 group-hover:bg-[#f0aa26] group-hover:text-[#102a4c]">
                  <Icon className="size-7" strokeWidth={1.8} aria-hidden="true" />
                </span>
                <span className="text-sm font-black tracking-[0.16em] text-[#102a4c]/25 transition-colors duration-300 group-hover:text-[#d67d0b]">
                  {number}
                </span>
              </div>

              <h3 className="mt-7 text-2xl font-bold leading-tight text-[#102a4c]">{title}</h3>
              <p className="mt-4 flex-1 text-sm leading-7 text-slate-600 sm:text-base">{description}</p>

              <ul className="mt-6 space-y-3 border-t border-[#102a4c]/10 pt-5">
                {examples.map((example) => (
                  <li key={example} className="flex items-center gap-2.5 text-sm font-semibold text-[#38536b]">
                    <Check className="size-4 shrink-0 text-[#d67d0b]" strokeWidth={3} aria-hidden="true" />
                    {example}
                  </li>
                ))}
              </ul>
            </article>
          ))}
        </div>

        <div className="mt-14 overflow-hidden rounded-[2rem] border border-sky-200 bg-sky-50 text-[#102a4c] lg:mt-20">
          <div className="grid lg:grid-cols-[0.68fr_1.32fr]">
            <div className="relative overflow-hidden border-b border-white/10 p-7 sm:p-9 lg:border-b-0 lg:border-r lg:p-10">
              <Boxes className="absolute -bottom-7 -right-7 size-40 text-[#102a4c]/5" strokeWidth={1} aria-hidden="true" />
              <p className="text-xs font-extrabold uppercase tracking-[0.18em] text-[#ffd126]">A simpler clearance</p>
              <h3 className="mt-4 text-3xl font-bold leading-tight sm:text-4xl">Three clear steps. One helpful team.</h3>
              <p className="mt-4 max-w-md text-sm leading-7 text-[#102a4c]/70 sm:text-base">
                We keep the process easy to understand from your first enquiry through to collection day.
              </p>
            </div>

            <ol className="grid sm:grid-cols-3">
              {processSteps.map(([title, description], index) => (
                <li key={title} className="group/step border-b border-white/10 p-6 transition-colors duration-300 last:border-b-0 hover:bg-white/8 sm:border-b-0 sm:border-r sm:last:border-r-0 lg:p-7">
                  <span className="text-sm font-black text-[#ffd126]">0{index + 1}</span>
                  <h4 className="mt-4 text-lg font-bold leading-snug">{title}</h4>
                  <p className="mt-3 text-sm leading-6 text-[#102a4c]/65 transition-colors duration-300 group-hover/step:text-white/85">{description}</p>
                </li>
              ))}
            </ol>
          </div>
        </div>

        <div className="mt-10 flex flex-col gap-5 rounded-[1.5rem] border border-[#102a4c]/10 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-8">
          <div>
            <h3 className="text-xl font-bold text-[#102a4c] sm:text-2xl">Ready to arrange your Chingford clearance?</h3>
            <p className="mt-2 text-sm leading-6 text-slate-600 sm:text-base">Check your options online or speak directly with our team.</p>
          </div>
          <div className="flex shrink-0 flex-col gap-3 sm:flex-row">
            <Link href="/#prices" className="group inline-flex min-h-12 items-center justify-center gap-2 rounded-full bg-[#ffd126] px-6 py-3 text-sm font-extrabold text-[#102a4c] transition duration-300 hover:-translate-y-0.5 hover:bg-[#102a4c] hover:text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#102a4c]">
              View prices
              <ArrowRight className="size-4 transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true" />
            </Link>
            <a href="tel:02082266477" className="inline-flex min-h-12 items-center justify-center gap-2 rounded-full border border-[#102a4c]/20 px-6 py-3 text-sm font-bold text-[#102a4c] transition duration-300 hover:-translate-y-0.5 hover:border-[#102a4c] hover:bg-[#eef3f7] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#102a4c]">
              <Phone className="size-4" aria-hidden="true" />
              020 8226 6477
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}
