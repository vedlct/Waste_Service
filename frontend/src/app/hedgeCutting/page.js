import HedgeCuttingHero from '@/components/hedgeCutting/HedgeCuttingHero'
import HedgeCuttingServices from '@/components/hedgeCutting/HedgeCuttingServices'
import HedgeCuttingDetails from '@/components/hedgeCutting/HedgeCuttingDetails'
import GardenServiceCards from '@/components/lawnMowing/GardenServiceCards'
import QuoteForm from '@/components/home/QuoteForm'
import GetPrices from '@/components/houseClearance/GetPrices'
import HowItWorks from '@/components/home/HowItWorks'

export default function HedgeCuttingPage () {
  return (
    <main>
      <HedgeCuttingHero/>
      <HedgeCuttingServices/>
      <HedgeCuttingDetails/>
      <GardenServiceCards theme="blue"/>
      <HowItWorks/>
      <GetPrices/>
      <QuoteForm/>
    </main>
  )
}
