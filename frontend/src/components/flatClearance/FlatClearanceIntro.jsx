import Link from 'next/link';
import { ArrowRight, Check, Footprints, Layers3, Sofa } from 'lucide-react';

const challenges = [
  { icon: Footprints, title: 'Careful access', text: 'Experienced handling through stairwells, corridors and tight corners.' },
  { icon: Sofa, title: 'Bulky items', text: 'Furniture and heavy household goods lifted and loaded by our team.' },
  { icon: Layers3, title: 'Every clearance size', text: 'From a single room to a complete flat, maisonette or duplex.' },
];

export default function FlatClearanceIntro() {
  return (
    <section className="overflow-hidden bg-[#f7f8f4]">
      <div className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-end lg:gap-16">
          <div>
            <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-[#b56808] sm:text-sm">
              <span className="h-0.5 w-9 bg-[#f0aa26]" />
              Chingford flat clearance
            </div>

            <h2 className="mt-5 text-3xl font-bold leading-[1.04] tracking-tight text-[#102a4c] sm:text-4xl lg:text-4xl">
              A clear flat.
              <span className="block text-[#d67d0b]">Without the difficult work.</span>
            </h2>
          </div>

          <div className="border-l-2 border-[#f0aa26] pl-5 sm:pl-7">
            <p className="text-lg leading-8 text-slate-600 sm:text-xl">
              We manage single items, room clearances and complete flatsâ€”including storage areas, basements, lofts, garages and sheds.
            </p>
            <p className="mt-4 text-base leading-7 text-slate-500">
              Our local team plans around the propertyâ€™s access and handles the lifting, carrying and removal from start to finish.
            </p>
          </div>
        </div>

        <div className="mt-12 border-y border-[#102a4c]/15 lg:mt-16">
          {challenges.map(({ icon: Icon, title, text }, index) => (
            <div
              key={title}
              className="group grid gap-4 border-b border-[#102a4c]/15 py-7 last:border-b-0 md:grid-cols-[3rem_4rem_0.7fr_1fr] sm:items-center sm:px-4 lg:py-8"
            >
              <span className="text-xs font-black tracking-[0.15em] text-[#d67d0b]">0{index + 1}</span>

              <span className="grid size-12 place-items-center rounded-full border border-sky-200 bg-sky-50 text-[#102a4c] transition duration-500 group-hover:rotate-6 group-hover:bg-[#f0aa26] group-hover:text-[#102a4c]">
                <Icon className="size-5" />
              </span>

              <h3 className="text-xl font-bold text-[#102a4c] sm:text-2xl">{title}</h3>

              <p className="text-sm leading-7 text-slate-600 sm:text-base">{text}</p>
            </div>
          ))}
        </div>

        <div className="mt-9 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <p className="flex items-center gap-3 text-sm font-semibold text-[#38536b] sm:text-base">
            <Check className="size-5 text-[#d67d0b]" strokeWidth={3} />
            Hassle-free service from first enquiry to final collection.
          </p>

          <Link
            href="/#prices"
            className="group inline-flex min-h-12 shrink-0 items-center justify-center gap-3 rounded-full bg-[#102a4c] px-6 py-3 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-[#f0aa26] hover:text-[#102a4c]"
          >
            Check flat clearance prices
            <ArrowRight className="size-4 transition group-hover:translate-x-1" />
          </Link>
        </div>
      </div>
    </section>
  );
}