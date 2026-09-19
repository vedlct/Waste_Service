import Benefits from "@/components/home/Benefits";
import Order from "@/components/home/Order";
import RubbishClearance from "@/components/home/RubbishClearance";
import RubbishRemoval from "@/components/home/RubbishRemoval";
import RubbishService from "@/components/home/RubbishService";
import QuoteForm from "@/components/home/QuoteForm";
import SecondaryNav from "@/components/layout/SecondaryNav";
import Hero from "@/components/home/Hero";
import HowItWorks from "@/components/home/HowItWorks";
import AboutUs from "@/components/home/AboutUs";
import Impact from "@/components/home/Impact";
import Review from "@/components/home/Review";
import { apiGet, timeAgo } from "@/lib/api";
import { metadataFor } from "@/lib/seo"

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/', { title: 'MR. TEE Removals | Rubbish Removal and Clearance' })
}

export default async function Home() {
  const reviews = await apiGet("reviews?limit=12");

  return (
    <div>
      <main>
        <Hero/>
        <SecondaryNav/>
        <Order/>
        <RubbishRemoval/>
        <RubbishService/>
        <QuoteForm/>
        <Review
          reviews={reviews?.map((review) => ({
            name: review.reviewer_name,
            time: timeAgo(review.published_at),
            rating: review.rating,
            text: review.body,
          }))}
        />
      </main>
    </div>
  );
}
