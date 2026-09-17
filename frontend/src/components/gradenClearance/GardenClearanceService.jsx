import Link from 'next/link';
import { ArrowRight, Leaf, Recycle, ShieldCheck, Truck } from 'lucide-react';

const servicePoints = [
  {
    icon: Leaf,
    title: 'From light cuttings to full clear-outs',
    description: 'Hedge trimmings, branches, soil, old furniture and accumulated garden waste can all be handled in one organised collection.',
  },
  {
    icon: Truck,
    title: 'A team that does the heavy work',
    description: 'Our uniformed Man & Van crew completes the lifting and loading, saving you repeated journeys and unnecessary disruption.',
  },
  {
    icon: Recycle,
    title: 'Waste handled the right way',
    description: 'Garden material is collected efficiently and directed towards responsible disposal and recycling wherever possible.',
  },
];

export default function GardenClearanceService() {
  return (
    <section className="relative overflow-hidden bg-white">
      <div className="pointer-events-none absolute -left-32 top-16 size-80 rounded-full border-[3rem] border-[#dcebbe]/35" aria-hidden="true" />

      <div className="relative mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-10 lg:grid-cols-[0.82fr_1.18fr] lg:gap-16">
          <div>
            <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-[#52731f] sm:text-sm">
              <span className="h-0.5 w-9 bg-[#8dbb45]" aria-hidden="true" />
              Chingford garden clearance
            </div>

            <h2 className="mt-5 text-3xl font-bold leading-[1.04] tracking-tight text-[#102a4c] sm:text-4xl lg:text-4xl">
              Clear the waste.
              <span className="block text-[#6f9633]">Enjoy the garden again.</span>
            </h2>

            <p className="mt-6 text-base leading-8 text-slate-600 sm:text-lg">
              Our garden clearance service offers a straightforward, hassle-free way to reclaim your outdoor space. A friendly, hardworking team arrives ready to collect, load and remove the waste for you.
            </p>

            <div className="mt-8 flex items-start gap-4 border-l-2 border-[#8dbb45] pl-5 sm:pl-6">
              <ShieldCheck className="mt-1 size-6 shrink-0 text-[#52731f]" aria-hidden="true" />
              <div>
                <h3 className="text-lg font-bold text-[#102a4c]">No job too big or too small</h3>
                <p className="mt-2 text-sm leading-7 text-slate-600 sm:text-base">
                  Whether it is debris from hedge trimming or branches from a larger garden renovation, we have the capacity to clear it efficiently and with minimal fuss.
                </p>
              </div>
            </div>

            <div className="mt-9 flex flex-col gap-3 sm:flex-row">
              <Link href="/#prices" className="group inline-flex min-h-12 items-center justify-center gap-3 rounded-full bg-[#102a4c] px-6 py-3 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-[#8dbb45] hover:text-[#102a4c] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#102a4c]">
                Check garden clearance prices
                <ArrowRight className="size-4 transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true" />
              </Link>
              <a href="tel:02082266477" className="inline-flex min-h-12 items-center justify-center rounded-full border border-[#102a4c]/20 px-6 py-3 text-sm font-bold text-[#102a4c] transition duration-300 hover:-translate-y-0.5 hover:border-[#52731f] hover:bg-[#f1f7e8] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#102a4c]">
                Call 020 8226 6477
              </a>
            </div>
          </div>

          <ol className="border-t border-[#102a4c]/15 lg:mt-2">
            {servicePoints.map(({ icon: Icon, title, description }, index) => (
              <li key={title} className="group grid gap-4 border-b border-[#102a4c]/15 py-7 transition duration-300 hover:bg-[#f4f8ed] md:grid-cols-[3rem_4rem_1fr] sm:items-start sm:px-4 lg:py-8">
                <span className="text-xs font-black tracking-[0.15em] text-[#6f9633]">0{index + 1}</span>
                <span className="grid size-12 place-items-center rounded-full bg-[#edf5df] text-[#52731f] transition duration-500 group-hover:rotate-6 group-hover:bg-[#8dbb45] group-hover:text-white">
                  <Icon className="size-5" strokeWidth={2} aria-hidden="true" />
                </span>
                <div>
                  <h3 className="text-xl font-bold leading-tight text-[#102a4c] sm:text-2xl">{title}</h3>
                  <p className="mt-3 text-sm leading-7 text-slate-600 sm:text-base">{description}</p>
                </div>
              </li>
            ))}
          </ol>
        </div>
      </div>
    </section>
  );
}
