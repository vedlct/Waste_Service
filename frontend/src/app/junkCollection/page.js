import WhyChooseOurService from '@/components/buildersWasteRemoval/WhyChooseOurService'
import QuoteForm from '@/components/home/QuoteForm'
import ServiceList from '@/components/home/ServiceList'
import JunkHero from '@/components/junkCollection/JunkHero'
import JunkRubbishItem from '@/components/junkCollection/JunkRubbishItem'
import SecondaryNav from '@/components/layout/SecondaryNav'
import React from 'react'
import { metadataFor } from '@/lib/seo'
import { getService } from '@/lib/services'
import { getSiteContact } from '@/lib/site'
import ServiceExtras from '@/components/service/ServiceExtras'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/junkCollection', { title: 'Junk Collection | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function page () {
  const [service, contact] = await Promise.all([getService('junk-collection'), getSiteContact()])

  return (
    <div>
        <JunkHero service={service} phone={contact.phone} />
        <SecondaryNav/>
        <WhyChooseOurService/>
        <ServiceList/>
        <JunkRubbishItem/>
        <ServiceExtras service={service} />
        <QuoteForm/>
    </div>
  )
}
