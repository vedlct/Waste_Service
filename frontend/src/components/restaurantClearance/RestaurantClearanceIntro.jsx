import Link from 'next/link';
import { ArrowRight, Building2, Check, Clock3, Phone, Recycle, ShieldCheck, Warehouse } from 'lucide-react';

const clearanceAreas = [
  {
    icon: Building2,
    label: 'Front of house',
    title: 'Dining rooms, bars and customer areas',
    description: 'Furniture, counters, fixtures and unwanted fittings removed carefully from the areas your customers use.',
  },
  {
    icon: Warehouse,
    label: 'Back of house',
    title: 'Kitchens, stores and service spaces',
    description: 'Clearance support for kitchens, storerooms, basements, garages and other operational areas.',
  },
  {
    icon: Recycle,
    label: 'After collection',
    title: 'Licensed, responsible waste handling',
    description: 'Materials are managed through licensed channels, with reuse and recycling prioritised wherever practical.',
  },
];

export default function RestaurantClearanceIntro() {
  return (
    <section className="relative overflow-hidden bg-[#f7f8f4]">
      <div className="pointer-events-none absolute -left-28 top-20 size-72 rounded-full border-[3rem] border-[#ffd126]/10" aria-hidden="true" />

      <div className="relative mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-9 lg:grid-cols-[0.85fr_1.15fr] lg:items-end lg:gap-16">
          <div>
            <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-[#b56808] sm:text-sm">
              <span className="h-0.5 w-9 bg-[#f0aa26]" aria-hidden="true" />
              Chingford restaurant clearance
            </div>
            <h2 className="mt-5 text-3xl font-bold leading-[1.04] tracking-tight text-[#102a4c] sm:text-4xl lg:text-4xl">
              Clear the premises.
              <span className="block text-[#d67d0b]">Protect your next opening.</span>
            </h2>
          </div>

          <div className="border-l-2 border-[#f0aa26] pl-5 sm:pl-7">
            <p className="text-lg leading-8 text-slate-600 sm:text-xl">
              From one unwanted item to a complete restaurant strip-out, our team manages the lifting and clearance around your premises and schedule.
            </p>
            <p className="mt-4 text-base leading-7 text-slate-500">
              We work across dining spaces, kitchens and storage areas, helping operators, landlords and contractors move confidently into the next stage.
            </p>
          </div>
        </div>

        <ol className="mt-12 border-t border-[#102a4c]/15 lg:mt-16">
          {clearanceAreas.map(({ icon: Icon, label, title, description }, index) => (
            <li key={title} className="group grid gap-5 border-b border-[#102a4c]/15 py-8 transition duration-500 hover:bg-white hover:px-4 md:grid-cols-[3rem_4rem_0.9fr_1.1fr] sm:items-center lg:gap-8 lg:py-9">
              <span className="text-xs font-black tracking-[0.15em] text-[#d67d0b]">0{index + 1}</span>
              <span className="grid size-13 place-items-center rounded-full border border-sky-200 bg-sky-50 text-[#102a4c] transition duration-500 group-hover:rotate-6 group-hover:bg-[#f0aa26] group-hover:text-[#102a4c]">
                <Icon className="size-6" strokeWidth={1.8} aria-hidden="true" />
              </span>
              <div>
                <p className="text-[0.68rem] font-extrabold uppercase tracking-[0.17em] text-[#d67d0b]">{label}</p>
                <h3 className="mt-2 text-xl font-bold leading-tight text-[#102a4c] sm:text-2xl">{title}</h3>
              </div>
              <p className="text-sm leading-7 text-slate-600 sm:text-base">{description}</p>
            </li>
          ))}
        </ol>

        <div className="mt-12 overflow-hidden rounded-[2rem] border border-sky-200 bg-sky-50 text-[#102a4c]">
          <div className="grid lg:grid-cols-[1fr_auto] lg:items-center">
            <div className="p-7 sm:p-9 lg:p-10">
              <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.17em] text-[#ffd126]">
                <Clock3 className="size-5" aria-hidden="true" />
                Planned around your operation
              </div>
              <h3 className="mt-4 text-2xl font-bold leading-tight sm:text-3xl">A practical collection plan with minimal disruption.</h3>
              <div className="mt-5 flex flex-col gap-3 text-sm font-semibold text-[#102a4c]/75 sm:flex-row sm:gap-6 sm:text-base">
                <span className="flex items-center gap-2"><Check className="size-4 text-[#ffd126]" strokeWidth={3} />Flexible collection times</span>
                <span className="flex items-center gap-2"><ShieldCheck className="size-4 text-[#ffd126]" />Fully insured waste carrier</span>
              </div>
            </div>

            <div className="flex flex-col gap-3 border-t border-white/10 p-7 sm:flex-row lg:border-l lg:border-t-0 lg:p-9">
              <Link href="/#prices" className="group inline-flex min-h-12 items-center justify-center gap-2 rounded-full bg-[#ffd126] px-6 py-3 text-sm font-extrabold text-[#102a4c] transition duration-300 hover:-translate-y-0.5 hover:bg-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white">
                Check clearance prices
                <ArrowRight className="size-4 transition-transform group-hover:translate-x-1" aria-hidden="true" />
              </Link>
              <a href="tel:02082266477" className="inline-flex min-h-12 items-center justify-center gap-2 rounded-full border border-white/30 px-6 py-3 text-sm font-bold transition duration-300 hover:-translate-y-0.5 hover:border-white hover:bg-white hover:text-[#102a4c] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white">
                <Phone className="size-4" aria-hidden="true" />
                Call us
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}