import HowItWorks from '@/components/home/HowItWorks'
import QuoteForm from '@/components/home/QuoteForm'
import GetPrices from '@/components/houseClearance/GetPrices'
import Window from '@/components/windowCleaning/Window'
import WindowCleaning from '@/components/windowCleaning/WindowCleaning'
import WindowCleaningDetails from '@/components/windowCleaning/WindowCleaningDetails'
import WindowServiceCards from '@/components/windowCleaning/WindowServiceCards'
import { metadataFor } from '@/lib/seo'
import { getService } from '@/lib/services'
import { getSiteContact } from '@/lib/site'
import ServiceExtras from '@/components/service/ServiceExtras'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/windowCleaning', { title: 'Window Cleaning | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function WindowCleaningPage () {
  const [service, contact] = await Promise.all([getService('window-cleaning'), getSiteContact()])

  return (
    <main>
        <Window service={service} phone={contact.phone} />
        <WindowCleaning/>
        <WindowCleaningDetails/>
        <WindowServiceCards/>
        <HowItWorks/>
        <GetPrices/>
        <ServiceExtras service={service} />
        <QuoteForm/>
    </main>
  )
}
