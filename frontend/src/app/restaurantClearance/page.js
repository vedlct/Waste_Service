import RubbishService from '@/components/home/RubbishService'
import GetPrices from '@/components/houseClearance/GetPrices'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import RestaurantHero from '@/components/restaurantClearance/RestaurantHero'
import RestaurantClearanceServices from '@/components/restaurantClearance/RestaurantClearanceServices'
import RestaurantClearanceIntro from '@/components/restaurantClearance/RestaurantClearanceIntro'
import QuoteForm from '@/components/home/QuoteForm'
import HowItWorks from '@/components/home/HowItWorks'
import { metadataFor } from '@/lib/seo'
import { getService } from '@/lib/services'
import { getSiteContact } from '@/lib/site'
import ServiceExtras from '@/components/service/ServiceExtras'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/restaurantClearance', { title: 'Restaurant Clearance | MR. TEE Removals' })
}

// Headline, summary, hero image, FAQs, related services and extra content blocks come
// from the service in the admin; each falls back to the built-in page content.
export default async function RestaurantClearancePage () {
  const [service, contact] = await Promise.all([getService('restaurant-clearance'), getSiteContact()])

  return (
    <main>
        <RestaurantHero service={service} phone={contact.phone} />
        <RestaurantClearanceIntro/>
        <GetPrices/>
        <WhyChooseUs/>
        <HowItWorks/>
        <RubbishService/>
        <RestaurantClearanceServices/>
        <ServiceExtras service={service} />
        <QuoteForm/>
    </main>
  )
}
