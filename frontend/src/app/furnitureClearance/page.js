import FurnitureHero from '@/components/furnitureClearance/FurnitureHero'
import FurnitureRubbishItems from '@/components/furnitureClearance/FurnitureRubbishItems'
import QuoteForm from '@/components/home/QuoteForm'
import ServiceList from '@/components/home/ServiceList'
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
  return metadataFor('/furnitureClearance', { title: 'Furniture Removal & Disposal | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function page () {
  const [service, contact] = await Promise.all([getService('furniture-clearance'), getSiteContact()])

  return (
    <div>
        <FurnitureHero service={service} phone={contact.phone} />
        <SecondaryNav/>
        <ServiceList/>
        <WhyChooseUs/>
        <FurnitureRubbishItems/>
        <ServiceExtras service={service} />
        <QuoteForm/>
    </div>
  )
}
