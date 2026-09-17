import GarageHero from '@/components/garageClearance/GarageHero'
import HowItWorks from '@/components/home/HowItWorks'
import QuoteForm from '@/components/home/QuoteForm'
import RubbishService from '@/components/home/RubbishService'
import GetPrices from '@/components/houseClearance/GetPrices'
import HouseRubbishItems from '@/components/houseClearance/HouseRubbishItems'
import ServiceHighlights from '@/components/houseClearance/ServiceHighlights'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import GarageRubbishClearance from '@/components/flatClearance/GarageRubbishClearance'
import React from 'react'

export default function page () {
  return (
    <div>
        <GarageHero/>
        <RubbishService/>
        <GetPrices/>
        <HowItWorks/>
        <GarageRubbishClearance/>
        <WhyChooseUs/>
        <ServiceHighlights/>
        <HouseRubbishItems/>
        <QuoteForm/>
    </div>
  )
}
