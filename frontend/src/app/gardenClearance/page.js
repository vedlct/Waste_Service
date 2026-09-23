import GardenClearanceService from '@/components/gradenClearance/GardenClearanceService'
import GardenHero from '@/components/gradenClearance/GardenHero'
import GetPrices from '@/components/houseClearance/GetPrices'
import ServiceHighlights from '@/components/houseClearance/ServiceHighlights'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import React from 'react'
import QuoteForm from '@/components/home/QuoteForm'
import GardenRubbishItems from '@/components/gradenClearance/GardenRubbishItems'
import HowItWorks from '@/components/home/HowItWorks'
import { metadataFor } from '@/lib/seo'
import { getService } from '@/lib/services'
import { getSiteContact } from '@/lib/site'
import ServiceExtras from '@/components/service/ServiceExtras'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/gardenClearance', { title: 'Garden Clearance | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function page () {
  const [service, contact] = await Promise.all([getService('garden-clearance'), getSiteContact()])

  return (
    <div>
        <GardenHero service={service} phone={contact.phone} />
        <GardenClearanceService/>
        <WhyChooseUs/>
        <GetPrices/>
        <HowItWorks/>
        <ServiceHighlights/>
        <GardenRubbishItems/>
        <ServiceExtras service={service} />
        <QuoteForm/>
    </div>
  )
}
