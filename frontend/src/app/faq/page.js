import FAQ from '@/components/faq/FAQ'
import { apiGet } from '@/lib/api'
import { metadataFor } from '@/lib/seo'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/faq', { title: 'Frequently Asked Questions | MR. TEE Removals' })
}

export default async function FAQPage() {
  // General FAQs only; service-specific questions belong on their service pages.
  const faqs = await apiGet('faqs')

  return <FAQ faqs={faqs?.map(({ question, answer }) => ({ question, answer }))} />
}
