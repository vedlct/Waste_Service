import BuildersHero from '@/components/buildersWasteRemoval/BuildersHero'
import BuildersWasteServices from '@/components/buildersWasteRemoval/BuildersWasteServices'
import WhyChooseOurService from '@/components/buildersWasteRemoval/WhyChooseOurService'
import QuoteForm from '@/components/home/QuoteForm'
import RubbishService from '@/components/home/RubbishService'
import GetPrices from '@/components/houseClearance/GetPrices'
import ServiceHighlights from '@/components/houseClearance/ServiceHighlights'
import BuildersWasteInfo from '@/components/buildersWasteRemoval/BuildersWasteInfo'
import React from 'react'
import HowItWorks from '@/components/home/HowItWorks'

export default function page () {
  return (
    <div>
        <BuildersHero/>
        <BuildersWasteInfo/>
        <GetPrices/>
        <HowItWorks/>
        <RubbishService/>
        <BuildersWasteServices/>
        <WhyChooseOurService/>
        <ServiceHighlights/>
        <QuoteForm/>
    </div>
  )
}
