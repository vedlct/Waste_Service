import Link from 'next/link';
import { ArrowRight, Check, MapPin, Phone, Recycle, ShieldCheck, Truck } from 'lucide-react';

const servicePromises = [
  { icon: MapPin, title: 'Assess the location', description: 'Tell us where the waste has been dumped, what it contains and whether there are any access restrictions.' },
  { icon: Truck, title: 'Send the right collection team', description: 'We match the vehicle and crew to the volume and type of rubbish, from a small load to a larger clearance.' },
  { icon: ShieldCheck, title: 'Remove it safely', description: 'Our uniformed team loads the waste carefully and works to leave the affected space clear and usable again.' },
  { icon: Recycle, title: 'Handle it responsibly', description: 'Collected waste is taken through licensed channels, with recyclable material separated wherever possible.' },
];

const wasteTypes = [
  'General household waste', 'Garden waste, sheds and garages',
  'Builders waste and rubble', 'Fridges, freezers and white goods',
  'Old furniture, sofas and cabinets', 'Home-improvement waste',
  'Small isolated loads', 'Larger mixed clearances',
];

export default function FlyTippingServices() {
  return (
    <section className="relative overflow-hidden border-y border-sky-100 bg-sky-50 text-[#102a4c]">
      <div className="pointer-events-none absolute -right-24 top-16 size-72 rounded-full border-[3rem] border-[#ffd126]/5" aria-hidden="true" />

      <div className="relative mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-8 border-b border-white/15 pb-12 lg:grid-cols-[0.8fr_1.2fr] lg:items-end lg:gap-16 lg:pb-16">
          <div>
            <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-[#ffd126] sm:text-sm">
              <span className="h-0.5 w-9 bg-[#ffd126]" aria-hidden="true" />
              Chingford fly-tipping response
            </div>
            <h2 className="mt-5 text-3xl font-bold leading-[1.04] tracking-tight sm:text-4xl lg:text-4xl">
              From dumped waste
              <span className="block font-serif font-medium italic text-[#ffd126]">to a clear space.</span>
            </h2>
          </div>
          <div>
            <p className="text-base leading-8 text-[#102a4c]/68 sm:text-lg">
              Fly-tipped rubbish can be disruptive, unsafe and difficult to manage alone. We organise the collection around the waste type, site access and urgency of the situation.
            </p>
            <p className="mt-4 text-sm leading-7 text-[#102a4c]/50 sm:text-base">
              No load is automatically too small or too largeâ€”share the details and we will advise on the most suitable response.
            </p>
          </div>
        </div>

        <ol>
          {servicePromises.map(({ icon: Icon, title, description }, index) => (
            <li key={title} className="group grid gap-5 border-b border-white/15 py-8 transition-colors duration-500 hover:bg-[#ffd126] hover:text-[#081f3d] md:grid-cols-[4rem_4rem_0.8fr_1.2fr] sm:items-center sm:px-4 lg:gap-8 lg:py-9">
              <span className="text-sm font-black tracking-[0.15em] text-[#ffd126] transition-colors group-hover:text-[#081f3d]/50">0{index + 1}</span>
              <span className="grid size-13 place-items-center rounded-full border border-white/20 text-[#ffd126] transition duration-500 group-hover:rotate-6 group-hover:border-[#081f3d] group-hover:bg-[#081f3d]">
                <Icon className="size-6" strokeWidth={1.8} aria-hidden="true" />
              </span>
              <h3 className="text-xl font-bold sm:text-2xl">{title}</h3>
              <p className="text-sm leading-7 text-[#102a4c]/62 transition-colors group-hover:text-[#081f3d]/75 sm:text-base">{description}</p>
            </li>
          ))}
        </ol>

        <div className="mt-14 grid gap-8 rounded-[2rem] border border-white/15 bg-white/5 p-6 sm:p-8 lg:grid-cols-[0.72fr_1.28fr] lg:gap-12 lg:p-10">
          <div>
            <p className="text-xs font-extrabold uppercase tracking-[0.17em] text-[#ffd126]">Waste we can assess</p>
            <h3 className="mt-4 text-3xl font-bold leading-tight sm:text-4xl">Mixed rubbish, handled by one local team.</h3>
          </div>
          <ul className="grid gap-x-8 sm:grid-cols-2">
            {wasteTypes.map((item) => (
              <li key={item} className="group/item flex min-h-12 items-center gap-3 border-b border-white/12 py-3 text-sm font-semibold text-[#102a4c]/78 transition hover:translate-x-1 hover:border-[#ffd126] hover:text-white sm:text-base">
                <Check className="size-4 shrink-0 text-[#ffd126] transition-transform group-hover/item:scale-110" strokeWidth={3} aria-hidden="true" />
                {item}
              </li>
            ))}
          </ul>
        </div>

        <div className="mt-10 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
          <p className="max-w-xl text-sm leading-7 text-[#102a4c]/58 sm:text-base">Send us the location and an honest description of the waste for the clearest collection guidance.</p>
          <div className="flex flex-col gap-3 sm:flex-row">
            <Link href="/contactUs" className="group inline-flex min-h-12 items-center justify-center gap-2 rounded-full bg-[#ffd126] px-6 py-3 text-sm font-extrabold text-[#081f3d] transition duration-300 hover:-translate-y-0.5 hover:bg-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white">
              Request a clearance
              <ArrowRight className="size-4 transition-transform group-hover:translate-x-1" aria-hidden="true" />
            </Link>
            <a href="tel:02082266477" className="inline-flex min-h-12 items-center justify-center gap-2 rounded-full border border-white/30 px-6 py-3 text-sm font-bold transition duration-300 hover:-translate-y-0.5 hover:border-white hover:bg-white hover:text-[#081f3d] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white">
              <Phone className="size-4" aria-hidden="true" />
              020 8226 6477
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}
