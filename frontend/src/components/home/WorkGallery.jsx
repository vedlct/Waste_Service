import Image from 'next/image'
import SectionHeader from '@/components/ui/SectionHeader'

const photos = [
  { src: '/images/highlight2.jpeg', alt: 'One of the team carrying a bag of rubbish along a street', className: 'col-span-2 row-span-2' },
  { src: '/images/junkRubbish2.jpg', alt: 'A pile of old furniture ready for collection', className: '' },
  { src: '/images/lawn2.jpg', alt: 'Bulk bags of garden waste', className: '' },
  { src: '/images/load3.jpg', alt: 'A van loaded with household clutter', className: '' },
  { src: '/images/furnitureRubbish1.jpg', alt: 'An old sofa waiting for collection', className: '' },
]

export default function WorkGallery() {
  return (
    <section className='bg-white py-20 sm:py-24'>
      <div className='mx-auto max-w-7xl px-4 sm:px-6 lg:px-8'>
        <SectionHeader
          eyebrow='On the job'
          title='Real clearances,'
          accent='every day'
          intro='From a single sofa to a full property, this is the kind of work our team handles across Portsmouth.'
          align='center'
        />

        <div className='mt-12 grid auto-rows-[10rem] grid-cols-2 gap-3 sm:auto-rows-[13rem] sm:gap-4 lg:grid-cols-4'>
          {photos.map((photo) => (
            <div key={photo.src} className={`group relative overflow-hidden rounded-2xl ${photo.className}`}>
              <Image
                src={photo.src}
                alt={photo.alt}
                fill
                sizes='(min-width: 1024px) 25vw, 50vw'
                className='object-cover transition-transform duration-700 group-hover:scale-105'
              />
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}
