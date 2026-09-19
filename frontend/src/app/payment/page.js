import Payment from '@/components/payment/Payment'
import { metadataFor } from '@/lib/seo'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/payment', { title: 'Payment | MR. TEE Removals', indexable: false })
}

export default function PaymentPage() {
  return <Payment />
}