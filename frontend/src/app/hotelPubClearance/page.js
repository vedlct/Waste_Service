import QuoteForm from '@/components/home/QuoteForm'
import ServiceList from '@/components/home/ServiceList'
import HotelPubHero from '@/components/hotelPubClearance/HotelPubHero'
import HotelRubbishItem from '@/components/hotelPubClearance/HotelRubbishItem'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import SecondaryNav from '@/components/layout/SecondaryNav'
import React from 'react'
import { metadataFor } from '@/lib/seo'
import { getService } from '@/lib/services'
import { getSiteContact } from '@/lib/site'
import ServiceExtras from '@/components/service/ServiceExtras'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/hotelPubClearance', { title: 'Hotel & Pub Clearance | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function page () {
  const [service, contact] = await Promise.all([getService('hotel-pub-clearance'), getSiteContact()])

  return (
    <div>
        <HotelPubHero service={service} phone={contact.phone} />
        <SecondaryNav/>
        <WhyChooseUs/>
        <HotelRubbishItem/>
        <ServiceList/>
        <ServiceExtras service={service} />
        <QuoteForm/>
    </div>
  )
}
