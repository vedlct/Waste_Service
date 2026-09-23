import CommunalAreaDetails from '@/components/communalAreaCleaning/CommunalAreaDetails'
import CommunalAreaHero from '@/components/communalAreaCleaning/CommunalAreaHero'
import CommunalAreaService from '@/components/communalAreaCleaning/CommunalAreaService'
import HowItWorks from '@/components/home/HowItWorks'
import QuoteForm from '@/components/home/QuoteForm'
import GetPrices from '@/components/houseClearance/GetPrices'
import WindowServiceCards from '@/components/windowCleaning/WindowServiceCards'
import React from 'react'
import { metadataFor } from '@/lib/seo'
import { getService } from '@/lib/services'
import { getSiteContact } from '@/lib/site'
import ServiceExtras from '@/components/service/ServiceExtras'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/communalAreaCleaning', { title: 'Communal Area Cleaning | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function page () {
  const [service, contact] = await Promise.all([getService('communal-area-cleaning'), getSiteContact()])

  return (
    <div>
        <CommunalAreaHero service={service} phone={contact.phone} />
        <CommunalAreaService/>
        <HowItWorks/>
        <GetPrices/>
        <CommunalAreaDetails/>
        <WindowServiceCards/>
        <ServiceExtras service={service} />
        <QuoteForm/>
    </div>
  )
}
