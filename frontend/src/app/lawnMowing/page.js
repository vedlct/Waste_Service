import LawnHero from '@/components/lawnMowing/LawnHero'
import LawnMowingServices from '@/components/lawnMowing/LawnMowingServices'
import LawnMowingDetails from '@/components/lawnMowing/LawnMowingDetails'
import GardenServiceCards from '@/components/lawnMowing/GardenServiceCards'
import React from 'react'
import QuoteForm from '@/components/home/QuoteForm'
import GetPrices from '@/components/houseClearance/GetPrices'
import HowItWorks from '@/components/home/HowItWorks'

export default function page () {
  return (
    <div>
        <LawnHero/>
        <LawnMowingServices/>
        <LawnMowingDetails/>
        <GardenServiceCards theme="blue"/>
        <HowItWorks/>
        <GetPrices/>
        <QuoteForm/>
    </div>
  )
}
