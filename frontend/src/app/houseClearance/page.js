import RubbishService from '@/components/home/RubbishService'
import GetPrices from '@/components/houseClearance/GetPrices'
import Hero from '@/components/houseClearance/Hero'
import HouseClearanceServices from '@/components/houseClearance/HouseClearanceServices'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import ServiceHighlights from '@/components/houseClearance/ServiceHighlights'
import HouseRubbishItems from '@/components/houseClearance/HouseRubbishItems'
import React from 'react'
import QuoteForm from '@/components/home/QuoteForm'
import HowItWorks from '@/components/home/HowItWorks'
import { metadataFor } from '@/lib/seo'
import { getService } from '@/lib/services'
import { getSiteContact } from '@/lib/site'
import ServiceExtras from '@/components/service/ServiceExtras'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/houseClearance', { title: 'House Clearance | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function page () {
  const [service, contact] = await Promise.all([getService('house-clearance'), getSiteContact()])

  return (
    <div>
        <Hero service={service} phone={contact.phone} />
        <HouseClearanceServices/>
        <GetPrices/>
        <RubbishService/>
        <WhyChooseUs/>
        <HowItWorks/>
        <ServiceHighlights/>
        <HouseRubbishItems/>
        <ServiceExtras service={service} />
        <QuoteForm/>
    </div>
  )
}
