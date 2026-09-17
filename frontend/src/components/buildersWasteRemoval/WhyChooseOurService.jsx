import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, Recycle, ShieldCheck } from 'lucide-react';

const reasons = [
  'Trusted London builders waste service',
  '95% of collected waste recycled',
  'Branded Wait & Load vehicles',
  'Reliable Man & Van teams',
  'Collections six days a week',
  'Friendly experienced crews',
  'Fully insured and licensed',
  'London and Home Counties coverage',
  'Clear competitive pricing',
];

const steps = [
  ['Book the collection', 'Tell us the waste type, volume, access and preferred collection window.'],
  ['Meet the team', 'A uniformed crew arrives in a branded vehicle and confirms the load.'],
  ['Clear the site', 'We safely load the waste, sweep the collection area and record the result.'],
  ['Recycle responsibly', 'Material is taken to a licensed facility, with an average 95% diverted from landfill.'],
];

export default function WhyChooseOurService() {
  return (
    <section className="overflow-hidden bg-white">
      <div className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
        <div className="grid gap-12 lg:grid-cols-[.86fr_1.14fr] lg:items-center lg:gap-16">
          <div className="relative h-[380px] sm:h-[650px]">
            <div className="group absolute left-0 top-0 h-[62%] w-[78%] overflow-hidden rounded-[2rem]">
              <Image
                src="/images/buildersRubbish1.jpg"
                alt="Building waste ready for collection"
                fill
                sizes="(max-width:1024px) 75vw,36vw"
                className="object-cover transition duration-700 group-hover:scale-105"
              />
            </div>

            <div className="group absolute bottom-0 right-0 h-[48%] w-[62%] overflow-hidden rounded-[2rem] border-8 border-white shadow-2xl">
              <Image
                src="/images/buildersRubbish2.jpg"
                alt="Responsible construction waste handling"
                fill
                sizes="(max-width:1024px) 60vw,28vw"
                className="object-cover transition duration-700 group-hover:scale-105"
              />
            </div>

            <div className="absolute bottom-[31%] left-5 rounded-2xl bg-[#ffd126] p-4 text-[#102a4c] shadow-xl">
              <Recycle className="size-7" />
              <p className="mt-2 text-2xl font-black">95%</p>
              <p className="text-xs font-bold">recycled</p>
            </div>
          </div>

          <div>
            <p className="text-xs font-extrabold uppercase tracking-[.18em] text-[#b56808]">Why work with us</p>

            <h2 className="mt-5 text-3xl font-bold leading-[1.05] text-[#102a4c] sm:text-4xl lg:text-4xl">
              A safer site.
              <span className="block text-[#d67d0b]">A dependable clearance.</span>
            </h2>

            <p className="mt-6 text-base leading-8 text-slate-600 sm:text-lg">
              Experienced teams, licensed handling and clear communication make builders waste easier to manage.
            </p>

            <ul className="mt-8 grid gap-3 sm:grid-cols-2">
              {reasons.map((r) => (
                <li
                  key={r}
                  className="group flex min-h-14 items-center gap-3 border-b border-[#102a4c]/10 px-2 py-3 text-sm font-semibold text-[#38536b] transition hover:translate-x-1 hover:border-[#f0aa26]"
                >
                  <Check className="size-4 shrink-0 text-[#d67d0b]" strokeWidth={3} />
                  {r}
                </li>
              ))}
            </ul>

            <Link
              href="/faq"
              className="group mt-7 inline-flex items-center gap-2 font-bold text-[#102a4c] underline decoration-[#f0aa26] decoration-2 underline-offset-4 hover:text-[#d67d0b]"
            >
              Read common questions
              <ArrowRight className="size-4 transition group-hover:translate-x-1" />
            </Link>
          </div>
        </div>

        <div className="mt-20 overflow-hidden rounded-[2rem] border border-sky-200 bg-sky-50 text-[#102a4c]">
          <div className="grid lg:grid-cols-[.65fr_1.35fr]">
            <div className="p-7 sm:p-10">
              <ShieldCheck className="size-9 text-[#ffd126]" />
              <h3 className="mt-5 text-3xl font-bold sm:text-4xl">Builders rubbish removal, made simple.</h3>
            </div>

            <ol className="grid sm:grid-cols-2 lg:grid-cols-4">
              {steps.map(([title, text], i) => (
                <li
                  key={title}
                  className="group border-t border-white/10 p-6 transition hover:bg-[#ffd126] hover:text-[#102a4c] sm:border-l sm:border-t-0"
                >
                  <span className="text-sm font-black text-[#ffd126] group-hover:text-[#102a4c]/50">0{i + 1}</span>
                  <h4 className="mt-4 text-lg font-bold">{title}</h4>
                  <p className="mt-3 text-sm leading-6 text-[#102a4c]/65 group-hover:text-[#102a4c]/75">{text}</p>
                </li>
              ))}
            </ol>
          </div>
        </div>
      </div>
    </section>
  );
}