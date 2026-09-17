import RubbishService from '@/components/home/RubbishService'
import GetPrices from '@/components/houseClearance/GetPrices'
import Hero from '@/components/houseClearance/Hero'
import HouseClearanceServices from '@/components/houseClearance/HouseClearanceServices'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import ServiceHighlights from '@/components/houseClearance/ServiceHighlights'
import HouseRubbishItems from '@/components/houseClearance/HouseRubbishItems'
import React from 'react'
import QuoteForm from '@/components/home/QuoteForm'
import HowItWorks from '@/components/home/HowItWorks'

export default function page () {
  return (
    <div>
        <Hero/>
        <HouseClearanceServices/>
        <GetPrices/>
        <RubbishService/>
        <WhyChooseUs/>
        <HowItWorks/>
        <ServiceHighlights/>
        <HouseRubbishItems/>
        <QuoteForm/>
    </div>
  )
}
