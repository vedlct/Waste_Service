// The heading block shared by content sections: a small eyebrow, a bold condensed title
// and an optional intro.
export default function SectionHeader({ eyebrow, title, accent, intro, align = 'left', dark = false, className = '' }) {
  const centred = align === 'center'

  return (
    <div className={`${centred ? 'mx-auto max-w-3xl text-center' : 'max-w-3xl'} ${className}`}>
      {eyebrow && <p className={`eyebrow ${dark ? 'text-brand-300 before:bg-brand-400' : ''}`}>{eyebrow}</p>}
      <h2 className={`mt-3 text-4xl font-bold uppercase leading-[0.95] sm:text-5xl ${dark ? 'text-white' : 'text-ink'}`}>
        {title}
        {accent && <span className={`block ${dark ? 'text-brand-400' : 'text-brand-600'}`}>{accent}</span>}
      </h2>
      {intro && <p className={`mt-5 text-base leading-7 sm:text-lg ${dark ? 'text-white/75' : 'text-slate-600'} ${centred ? 'mx-auto max-w-2xl' : 'max-w-2xl'}`}>{intro}</p>}
    </div>
  )
}
