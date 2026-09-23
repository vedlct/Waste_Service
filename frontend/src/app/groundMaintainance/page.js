import GroundDetails from '@/components/groundMaintainance/GroundDetails'
import GroundHero from '@/components/groundMaintainance/GroundHero'
import GroundServices from '@/components/groundMaintainance/GroundServices'
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
  return metadataFor('/groundMaintainance', { title: 'Ground Maintenance | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function page () {
  const [service, contact] = await Promise.all([getService('ground-maintenance'), getSiteContact()])

  return (
    <div>
        <GroundHero service={service} phone={contact.phone} />
        <GroundServices/>
        <GroundDetails/>
        <WindowServiceCards/>
        <HowItWorks/>
        <GetPrices/>
        <ServiceExtras service={service} />
        <QuoteForm/>
    </div>
  )
}
