import ContactUs from '@/components/contactUs/ContactUs'
import { metadataFor } from '@/lib/seo'
import { getSiteContact } from '@/lib/site'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/contactUs', { title: 'Contact Us | MR. TEE Removals' })
}

export default async function ContactUsPage() {
  return <ContactUs contact={await getSiteContact()} />
}
