import HedgeCuttingHero from '@/components/hedgeCutting/HedgeCuttingHero'
import HedgeCuttingServices from '@/components/hedgeCutting/HedgeCuttingServices'
import HedgeCuttingDetails from '@/components/hedgeCutting/HedgeCuttingDetails'
import GardenServiceCards from '@/components/lawnMowing/GardenServiceCards'
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
  return metadataFor('/hedgeCutting', { title: 'Hedge Cutting | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function HedgeCuttingPage () {
  const [service, contact] = await Promise.all([getService('hedge-cutting'), getSiteContact()])

  return (
    <main>
      <HedgeCuttingHero service={service} phone={contact.phone} />
      <HedgeCuttingServices/>
      <HedgeCuttingDetails/>
      <GardenServiceCards theme="blue"/>
      <HowItWorks/>
      <GetPrices/>
      <ServiceExtras service={service} />
      <QuoteForm/>
    </main>
  )
}
