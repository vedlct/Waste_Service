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

export default function Home() {
  return (
    <div>
      <main>
        <Hero/>
        <SecondaryNav/>
        <Order/>
        <RubbishRemoval/>
        <RubbishService/>
        <QuoteForm/>
        <Review/>
      </main>
    </div>
  );
}
