import FlyHero from '@/components/flyTippingClearance/FlyHero'
import FlyTippingServices from '@/components/flyTippingClearance/FlyTippingServices'
import FlyTippedRubbishRemoval from '@/components/flyTippingClearance/FlyTippedRubbishRemoval'
import RubbishService from '@/components/home/RubbishService'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import QuoteForm from '@/components/home/QuoteForm'
import HowItWorks from '@/components/home/HowItWorks'
import { metadataFor } from '@/lib/seo'
import { getService } from '@/lib/services'
import { getSiteContact } from '@/lib/site'
import ServiceExtras from '@/components/service/ServiceExtras'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/flyTippingClearance', { title: 'Fly Tipping Clearance | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function FlyTippingClearancePage () {
  const [service, contact] = await Promise.all([getService('fly-tipping-clearance'), getSiteContact()])

  return (
    <main>
      <FlyHero service={service} phone={contact.phone} />
      <FlyTippedRubbishRemoval/>
      <WhyChooseUs/>
      <HowItWorks/>
      <RubbishService/>
      <FlyTippingServices/>
      <ServiceExtras service={service} />
      <QuoteForm/>
    </main>
  )
}
