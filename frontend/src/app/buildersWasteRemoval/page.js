import BuildersHero from '@/components/buildersWasteRemoval/BuildersHero'
import BuildersWasteServices from '@/components/buildersWasteRemoval/BuildersWasteServices'
import WhyChooseOurService from '@/components/buildersWasteRemoval/WhyChooseOurService'
import QuoteForm from '@/components/home/QuoteForm'
import RubbishService from '@/components/home/RubbishService'
import GetPrices from '@/components/houseClearance/GetPrices'
import ServiceHighlights from '@/components/houseClearance/ServiceHighlights'
import BuildersWasteInfo from '@/components/buildersWasteRemoval/BuildersWasteInfo'
import React from 'react'
import HowItWorks from '@/components/home/HowItWorks'
import { metadataFor } from '@/lib/seo'
import { getService } from '@/lib/services'
import { getSiteContact } from '@/lib/site'
import ServiceExtras from '@/components/service/ServiceExtras'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/buildersWasteRemoval', { title: 'Builders Waste Removal | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function page () {
  const [service, contact] = await Promise.all([getService('builders-waste-removal'), getSiteContact()])

  return (
    <div>
        <BuildersHero service={service} phone={contact.phone} />
        <BuildersWasteInfo/>
        <GetPrices/>
        <HowItWorks/>
        <RubbishService/>
        <BuildersWasteServices/>
        <WhyChooseOurService/>
        <ServiceHighlights/>
        <ServiceExtras service={service} />
        <QuoteForm/>
    </div>
  )
}
