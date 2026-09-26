import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, Monitor, Phone, Recycle, Warehouse } from 'lucide-react';

const serviceScope = [
  {
    icon: Monitor,
    title: 'Furniture & equipment',
    description: 'Desks, chairs, cabinets, shelving and workplace equipment cleared carefully from your premises.',
  },
  {
    icon: Warehouse,
    title: 'Rooms & storage areas',
    description: 'from one office or stockroom to a full workplace clearance, including hard-to-manage storage spaces.',
  },
  {
    icon: Recycle,
    title: 'Responsible removal',
    description: 'Reusable and recyclable materials are separated wherever possible and handled by a licensed team.',
  },
];

export default function OfficeWasteServices() {
  return (
    <section className="relative overflow-hidden bg-mist">
      <div className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-10 lg:grid-cols-[0.78fr_1.22fr] lg:items-center lg:gap-16">
          <div className="group relative min-h-[360px] overflow-hidden rounded-[2rem] bg-sky-50 shadow-[0_28px_65px_-34px_rgba(15,37,87,0.7)] sm:min-h-[380px]">
            <Image
              src="/images/officeRubbish.jpg"
              alt="Electronic office equipment prepared for responsible recycling"
              fill
              sizes="(max-width: 1024px) 100vw, 38vw"
              className="object-cover object-center transition duration-700 ease-out group-hover:scale-105"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-brand-950/90 via-brand-900/15 to-transparent" />

            <div className="absolute inset-x-6 bottom-6 rounded-2xl border border-sky-200 bg-white/90 p-5 text-ink backdrop-blur-sm sm:inset-x-8 sm:bottom-8">
              <p className="text-xs font-extrabold uppercase tracking-[0.16em] text-brand-500">Business-ready collections</p>
              <p className="mt-2 text-lg font-bold leading-snug sm:text-xl">Planned to reduce disruption to your staff and working day.</p>
            </div>
          </div>

          <div>
            <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-brand-700 sm:text-sm">
              <span className="h-0.5 w-9 bg-brand-500" aria-hidden="true" />
              Portsmouth office clearance
            </div>

            <h2 className="mt-5 text-4xl font-bold leading-[0.95] text-ink sm:text-5xl lg:text-5xl uppercase">
              Clear the workplace.
              <span className="block text-brand-700">Keep Business moving.</span>
            </h2>

            <p className="mt-6 max-w-2xl text-base leading-8 text-slate-600 sm:text-lg">
              We handle everything from single unwanted items and room clearances to complete offices and storage areas, with the lifting and removal managed for you.
            </p>

            <div className="mt-9 border-t border-ink/15">
              {serviceScope.map(({ icon: icon, title, description }, index) => (
                <div key={title} className="group/row grid gap-4 border-b border-ink/15 py-6 transition duration-300 hover:bg-white hover:px-4 sm:grid-cols-[2.5rem_3.5rem_1fr] sm:items-start">
                  <span className="text-xs font-black tracking-[0.14em] text-brand-700">0{index + 1}</span>
                  <span className="grid size-11 place-items-center rounded-full border border-sky-200 bg-sky-50 text-ink transition duration-500 group-hover/row:rotate-6 group-hover/row:bg-brand-500 group-hover/row:text-ink">
                    <icon className="size-5" aria-hidden="true" />
                  </span>
                  <div>
                    <h3 className="text-xl font-bold text-ink sm:text-2xl">{title}</h3>
                    <p className="mt-2 text-sm leading-7 text-slate-600 sm:text-base">{description}</p>
                  </div>
                </div>
              ))}
            </div>

            <p className="mt-7 flex items-start gap-3 text-sm font-semibold leading-6 text-slate-600 sm:text-base">
              <Check className="mt-0.5 size-5 shrink-0 text-brand-700" strokeWidth={3} aria-hidden="true" />
              Flexible collection planning for offices, commercial premises and managed workplaces.
            </p>

            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
              <Link href="/#prices" className="group inline-flex min-h-12 items-center justify-center gap-3 rounded-full bg-brand-900 px-6 py-3 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-brand-500 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink">
                Check office clearance prices
                <ArrowRight className="size-4 transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true" />
              </Link>
              <a href="tel:07862138033" className="inline-flex min-h-12 items-center justify-center gap-2 rounded-full border border-ink/20 px-6 py-3 text-sm font-bold text-ink transition duration-300 hover:-translate-y-0.5 hover:border-ink hover:bg-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink">
                <Phone className="size-4" aria-hidden="true" />
                078 6213 8033
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
