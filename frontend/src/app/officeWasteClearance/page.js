import HowItWorks from '@/components/home/HowItWorks'
import QuoteForm from '@/components/home/QuoteForm'
import RubbishService from '@/components/home/RubbishService'
import GetPrices from '@/components/houseClearance/GetPrices'
import HouseRubbishItems from '@/components/houseClearance/HouseRubbishItems'
import ServiceHighlights from '@/components/houseClearance/ServiceHighlights'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import OfficeHero from '@/components/officeWasteClearance/OfficeHero'
import OfficeWasteServices from '@/components/officeWasteClearance/OfficeWasteServices'
import React from 'react'
import { metadataFor } from '@/lib/seo'
import { getService } from '@/lib/services'
import { getSiteContact } from '@/lib/site'
import ServiceExtras from '@/components/service/ServiceExtras'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/officeWasteClearance', { title: 'Office Waste Clearance | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function page () {
  const [service, contact] = await Promise.all([getService('office-waste-clearance'), getSiteContact()])

  return (
    <div>
        <OfficeHero service={service} phone={contact.phone} />
        <OfficeWasteServices/>
        <GetPrices/>
        <RubbishService/>
        <WhyChooseUs/>
        <ServiceHighlights/>
        <HowItWorks/>
        <HouseRubbishItems/>
        <ServiceExtras service={service} />
        <QuoteForm/>
    </div>
  )
}
