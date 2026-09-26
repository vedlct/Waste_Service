import Image from 'next/image'

// A full-width hero with a completely clear photo: no overlay, tint or blur. The text sits on a
// solid white card, over the photo on large screens and below it on phones.
export default function PhotoHero({
  image,
  alt = '',
  imagePosition = 'object-center',
  imageFrameClassName = 'h-72 sm:h-[26rem]',
  cardClassName = '',
  mobileOverlap = true,
  unoptimized = false,
  minHeight = 'lg:min-h-[40rem]',
  children,
}) {
  return (
    <section className={`relative isolate w-full overflow-hidden bg-mist lg:flex lg:items-center ${minHeight}`}>
      <div className={`relative ${imageFrameClassName} lg:absolute lg:inset-0 lg:h-auto`}>
        <Image src={image} alt={alt} fill preload quality={90} sizes='100vw' unoptimized={unoptimized} className={`object-cover ${imagePosition}`} />
      </div>

      <div className='relative mx-auto min-w-0 w-full max-w-7xl px-4 pb-10 sm:px-6 lg:px-8 lg:pb-24 lg:pt-16'>
        <div className={`${mobileOverlap ? '-mt-16 sm:-mt-24 lg:mt-0' : 'mt-0'} min-w-0 max-w-full rounded-2xl border border-white/80 bg-white p-6 shadow-[0_30px_70px_-30px_rgba(15,37,87,0.45)] sm:p-10 lg:max-w-xl ${cardClassName}`}>
          {children}
        </div>
      </div>
    </section>
  )
}
