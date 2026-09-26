import Hero from "@/components/home/Hero";
import TrustBand from "@/components/home/TrustBand";
import ServicesShowcase from "@/components/home/ServicesShowcase";
import StatsBand from "@/components/home/StatsBand";
import Order from "@/components/home/Order";
import WhyChoose from "@/components/home/WhyChoose";
import HowItWorks from "@/components/home/HowItWorks";
import WorkGallery from "@/components/home/WorkGallery";
import LocalAreas from "@/components/home/LocalAreas";
import Review from "@/components/home/Review";
import { getSiteContact } from "@/lib/site";
import { apiGet, timeAgo } from "@/lib/api";
import { metadataFor } from "@/lib/seo"

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/', { title: 'MR. TEE Removals | Rubbish Removal and Clearance' })
}

export default async function Home() {
  const [reviews, coverage, contact] = await Promise.all([
    apiGet("reviews?limit=12"),
    apiGet("coverage"),
    getSiteContact(),
  ]);

  return (
    <main>
      <Hero phone={contact.phone} coverage={coverage} />
      <TrustBand />
      <ServicesShowcase />
      <StatsBand coverage={coverage} />
      <Order />
      <WhyChoose />
      <HowItWorks />
      <WorkGallery />
      <Review
        reviews={reviews?.map((review) => ({
          name: review.reviewer_name,
          time: timeAgo(review.published_at),
          rating: review.rating,
          text: review.body,
        }))}
      />
      <LocalAreas coverage={coverage} />
    </main>
  );
}
