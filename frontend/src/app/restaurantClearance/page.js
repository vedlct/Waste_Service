import RubbishService from '@/components/home/RubbishService'
import GetPrices from '@/components/houseClearance/GetPrices'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import RestaurantHero from '@/components/restaurantClearance/RestaurantHero'
import RestaurantClearanceServices from '@/components/restaurantClearance/RestaurantClearanceServices'
import RestaurantClearanceIntro from '@/components/restaurantClearance/RestaurantClearanceIntro'
import QuoteForm from '@/components/home/QuoteForm'
import HowItWorks from '@/components/home/HowItWorks'

export default function RestaurantClearancePage () {
  return (
    <main>
        <RestaurantHero/>
        <RestaurantClearanceIntro/>
        <GetPrices/>
        <WhyChooseUs/>
        <HowItWorks/>
        <RubbishService/>
        <RestaurantClearanceServices/>
        <QuoteForm/>
    </main>
  )
}
