import Link from 'next/link';
import { ArrowUpRight } from 'lucide-react';

const services = [
  ['01', 'Hedge cutting', '/hedgeCutting'],
  ['02', 'Grounds maintenance', '/groundMaintainance'],
  ['03', 'Lawn mowing', '/lawnMowing'],
];

export default function GardenServiceCards({ theme = 'green' }) {
  const blue = theme === 'blue';

  return (
    <section className={`${blue ? 'bg-sky-50' : 'bg-white'} text-ink`}>
      <div className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24">
        <p
          className={`text-xs font-extrabold uppercase tracking-[.18em] ${
            blue ? 'text-brand-300' : 'text-brand-300'
          }`}
        >
          Connected garden services
        </p>

        <h2 className="mt-4 text-4xl font-bold sm:text-5xl uppercase leading-[0.95]">
          One team for a better-kept outdoor space.
        </h2>

        <div className="mt-10 border-t border-white/15">
          {services.map(([n, t, h]) => (
            <Link
              key={t}
              href={h}
              className={`group grid min-h-24 grid-cols-[3rem_1fr_auto] items-center gap-4 border-b border-white/15 px-2 transition duration-500 hover:px-5 sm:min-h-28 sm:grid-cols-[5rem_1fr_auto] ${
                blue
                  ? 'hover:bg-brand-300 hover:text-ink'
                  : 'hover:bg-brand-300 hover:text-ink'
              }`}
            >
              <span
                className={`text-xs font-black ${
                  blue
                    ? 'text-brand-300 group-hover:text-ink/50'
                    : 'text-brand-300 group-hover:text-ink/50'
                }`}
              >
                {n}
              </span>

              <h3 className="text-xl font-bold sm:text-2xl lg:text-3xl">{t}</h3>

              <ArrowUpRight className="size-6 transition group-hover:translate-x-1 group-hover:-translate-y-1" />
            </Link>
          ))}
        </div>
      </div>
    </section>
  );
}