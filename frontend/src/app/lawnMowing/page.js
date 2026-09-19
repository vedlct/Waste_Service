import LawnHero from '@/components/lawnMowing/LawnHero'
import LawnMowingServices from '@/components/lawnMowing/LawnMowingServices'
import LawnMowingDetails from '@/components/lawnMowing/LawnMowingDetails'
import GardenServiceCards from '@/components/lawnMowing/GardenServiceCards'
import React from 'react'
import QuoteForm from '@/components/home/QuoteForm'
import GetPrices from '@/components/houseClearance/GetPrices'
import HowItWorks from '@/components/home/HowItWorks'
import { metadataFor } from '@/lib/seo'
import { getService } from '@/lib/services'
import { getSiteContact } from '@/lib/site'
import ServiceExtras from '@/components/service/ServiceExtras'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/lawnMowing', { title: 'Lawn Mowing | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function page () {
  const [service, contact] = await Promise.all([getService('lawn-mowing'), getSiteContact()])

  return (
    <div>
        <LawnHero service={service} phone={contact.phone} />
        <LawnMowingServices/>
        <LawnMowingDetails/>
        <GardenServiceCards theme="blue"/>
        <HowItWorks/>
        <GetPrices/>
        <ServiceExtras service={service} />
        <QuoteForm/>
    </div>
  )
}
