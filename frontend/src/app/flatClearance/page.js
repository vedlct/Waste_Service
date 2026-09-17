import FlatClearanceIntro from '@/components/flatClearance/FlatClearanceIntro'
import FlatHero from '@/components/flatClearance/FlatHero'
import FlyTippedRubbishRemoval from '@/components/flyTippingClearance/FlyTippedRubbishRemoval'
import HowItWorks from '@/components/home/HowItWorks'
import RubbishService from '@/components/home/RubbishService'
import GetPrices from '@/components/houseClearance/GetPrices'
import ServiceHighlights from '@/components/houseClearance/ServiceHighlights'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import React from 'react'

export default function page () {
  return (
    <div>
        <FlatHero/>
        <FlatClearanceIntro/>
        <GetPrices/>
        <HowItWorks/>
        <WhyChooseUs/>
        <ServiceHighlights/>
        <RubbishService/>
        <FlyTippedRubbishRemoval/>
    </div>
  )
}
