import FlyHero from '@/components/flyTippingClearance/FlyHero'
import FlyTippingServices from '@/components/flyTippingClearance/FlyTippingServices'
import FlyTippedRubbishRemoval from '@/components/flyTippingClearance/FlyTippedRubbishRemoval'
import RubbishService from '@/components/home/RubbishService'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import QuoteForm from '@/components/home/QuoteForm'
import HowItWorks from '@/components/home/HowItWorks'

export default function FlyTippingClearancePage () {
  return (
    <main>
      <FlyHero/>
      <FlyTippedRubbishRemoval/>
      <WhyChooseUs/>
      <HowItWorks/>
      <RubbishService/>
      <FlyTippingServices/>
      <QuoteForm/>
    </main>
  )
}
