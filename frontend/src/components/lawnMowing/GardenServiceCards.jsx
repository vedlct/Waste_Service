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
    <section className={`${blue ? 'bg-sky-50' : 'bg-white'} text-[#102a4c]`}>
      <div className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24">
        <p
          className={`text-xs font-extrabold uppercase tracking-[.18em] ${
            blue ? 'text-[#7dc6f2]' : 'text-[#badd70]'
          }`}
        >
          Connected garden services
        </p>

        <h2 className="mt-4 text-3xl font-bold sm:text-4xl">
          One team for a better-kept outdoor space.
        </h2>

        <div className="mt-10 border-t border-white/15">
          {services.map(([n, t, h]) => (
            <Link
              key={t}
              href={h}
              className={`group grid min-h-24 grid-cols-[3rem_1fr_auto] items-center gap-4 border-b border-white/15 px-2 transition duration-500 hover:px-5 sm:min-h-28 sm:grid-cols-[5rem_1fr_auto] ${
                blue
                  ? 'hover:bg-[#7dc6f2] hover:text-[#081f3d]'
                  : 'hover:bg-[#badd70] hover:text-[#173525]'
              }`}
            >
              <span
                className={`text-xs font-black ${
                  blue
                    ? 'text-[#7dc6f2] group-hover:text-[#081f3d]/50'
                    : 'text-[#badd70] group-hover:text-[#173525]/50'
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