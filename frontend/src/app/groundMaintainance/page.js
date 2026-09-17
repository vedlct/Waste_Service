import GroundDetails from '@/components/groundMaintainance/GroundDetails'
import GroundHero from '@/components/groundMaintainance/GroundHero'
import GroundServices from '@/components/groundMaintainance/GroundServices'
import HowItWorks from '@/components/home/HowItWorks'
import QuoteForm from '@/components/home/QuoteForm'
import GetPrices from '@/components/houseClearance/GetPrices'
import WindowServiceCards from '@/components/windowCleaning/WindowServiceCards'
import React from 'react'

export default function page () {
  return (
    <div>
        <GroundHero/>
        <GroundServices/>
        <GroundDetails/>
        <WindowServiceCards/>
        <HowItWorks/>
        <GetPrices/>
        <QuoteForm/>
    </div>
  )
}
