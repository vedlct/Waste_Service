import CommunalAreaDetails from '@/components/communalAreaCleaning/CommunalAreaDetails'
import CommunalAreaHero from '@/components/communalAreaCleaning/CommunalAreaHero'
import CommunalAreaService from '@/components/communalAreaCleaning/CommunalAreaService'
import HowItWorks from '@/components/home/HowItWorks'
import QuoteForm from '@/components/home/QuoteForm'
import GetPrices from '@/components/houseClearance/GetPrices'
import WindowServiceCards from '@/components/windowCleaning/WindowServiceCards'
import React from 'react'

export default function page () {
  return (
    <div>
        <CommunalAreaHero/>
        <CommunalAreaService/>
        <HowItWorks/>
        <GetPrices/>
        <CommunalAreaDetails/>
        <WindowServiceCards/>
        <QuoteForm/>
    </div>
  )
}
