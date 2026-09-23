import Checkout from '@/components/checkout/Checkout'
import { metadataFor } from '@/lib/seo'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/checkout', { title: 'Checkout | MR. TEE Removals', indexable: false })
}
export default function CheckoutPage(){return <Checkout/>}
