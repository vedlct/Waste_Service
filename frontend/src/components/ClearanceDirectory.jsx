import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, Check, Recycle } from 'lucide-react';

export default function ClearanceDirectory({ eyebrow, heading, accent, intro, note, categories, images }) {
  return (
    <section className="overflow-hidden bg-mist">
      <div className="mx-auto grid w-full max-w-7xl gap-10 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[0.72fr_1.28fr] lg:gap-16 lg:px-8 lg:py-28">
        <div className="lg:sticky lg:top-28 lg:self-start">
          <div className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-brand-700 sm:text-sm"><span className="h-0.5 w-9 bg-brand-500" />{eyebrow}</div>
          <h2 className="mt-5 text-4xl font-bold leading-[0.95] text-ink sm:text-5xl lg:text-5xl uppercase">{heading}<span className="block text-brand-700">{accent}</span></h2>
          <p className="mt-6 max-w-lg text-base leading-8 text-slate-600 sm:text-lg">{intro}</p>
          <div className="mt-8 border-l-2 border-brand-500 pl-5"><p className="text-sm font-bold text-ink sm:text-base">Cannot see your item listed?</p><p className="mt-2 text-sm leading-6 text-slate-600">{note}</p></div>
          <Link href="/contactUs" className="group mt-8 inline-flex min-h-12 items-center gap-3 rounded-full bg-brand-900 px-6 py-3 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-brand-500 hover:text-white">Ask about an item<ArrowRight className="size-4 transition group-hover:translate-x-1" /></Link>

          <div className="relative mt-10 h-[390px] sm:h-[470px] lg:h-[520px]">
            {images.map(({ src, alt }, index) => {
              const positions = ['left-0 top-0 h-[47%] w-[72%]', 'right-0 top-[9%] h-[35%] w-[38%]', 'bottom-[5%] left-[4%] h-[42%] w-[42%]', 'bottom-0 right-0 h-[48%] w-[55%]'];
              return <div key={src} className={`group/image absolute overflow-hidden rounded-[1.6rem] border-4 border-mist bg-brand-900 shadow-xl transition duration-500 hover:z-20 hover:-translate-y-1 ${positions[index]}`}><Image src={src} alt={alt} fill sizes="(max-width:1024px) 65vw,360px" className="object-cover transition duration-700 group-hover/image:scale-110"/><span className="absolute bottom-3 left-3 grid size-9 place-items-center rounded-full bg-brand-500 text-white"><Recycle className="size-4" /></span></div>;
            })}
          </div>
        </div>

        <div className="border-t border-ink/20">
          {categories.map(({ title, summary, items }, index) => (
            <article key={title} className="group/category border-b border-ink/20 py-8 sm:py-10">
              <div className="grid gap-5 sm:grid-cols-[4rem_1fr] sm:gap-6"><span className="text-sm font-black tracking-[0.16em] text-brand-700">0{index + 1}</span><div><div className="flex justify-between gap-4"><div><h3 className="text-2xl font-bold text-ink sm:text-3xl">{title}</h3><p className="mt-3 text-sm leading-7 text-slate-600 sm:text-base">{summary}</p></div><span className="hidden size-12 shrink-0 place-items-center rounded-full border border-ink/15 transition group-hover/category:rotate-12 group-hover/category:bg-brand-500 sm:grid"><Recycle className="size-5" /></span></div>
                <ul className="mt-7 grid gap-x-7 sm:grid-cols-2 xl:grid-cols-3">{items.map((item) => <li key={item} className="group/item flex min-h-11 items-center gap-3 border-t border-ink/10 py-2.5 text-sm font-semibold text-slate-600 transition hover:translate-x-1 hover:border-brand-500 hover:text-ink"><Check className="size-4 shrink-0 text-brand-700" strokeWidth={3}/>{item}</li>)}</ul>
              </div></div>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
