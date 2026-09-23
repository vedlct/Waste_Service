import FlatClearanceIntro from '@/components/flatClearance/FlatClearanceIntro'
import FlatHero from '@/components/flatClearance/FlatHero'
import FlyTippedRubbishRemoval from '@/components/flyTippingClearance/FlyTippedRubbishRemoval'
import HowItWorks from '@/components/home/HowItWorks'
import RubbishService from '@/components/home/RubbishService'
import GetPrices from '@/components/houseClearance/GetPrices'
import ServiceHighlights from '@/components/houseClearance/ServiceHighlights'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import React from 'react'
import { metadataFor } from '@/lib/seo'
import { getService } from '@/lib/services'
import { getSiteContact } from '@/lib/site'
import ServiceExtras from '@/components/service/ServiceExtras'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/flatClearance', { title: 'Flat Clearance | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function page () {
  const [service, contact] = await Promise.all([getService('flat-clearance'), getSiteContact()])

  return (
    <div>
        <FlatHero service={service} phone={contact.phone} />
        <FlatClearanceIntro/>
        <GetPrices/>
        <HowItWorks/>
        <WhyChooseUs/>
        <ServiceHighlights/>
        <RubbishService/>
        <FlyTippedRubbishRemoval/>
        <ServiceExtras service={service} />
    </div>
  )
}
