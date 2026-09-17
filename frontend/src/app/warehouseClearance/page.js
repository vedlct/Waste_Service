import QuoteForm from '@/components/home/QuoteForm'
import ServiceList from '@/components/home/ServiceList'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import JunkRubbishItem from '@/components/junkCollection/JunkRubbishItem'
import SecondaryNav from '@/components/layout/SecondaryNav'
import WareHouseHero from '@/components/warehouseClearance/WareHouseHero'
import React from 'react'

export default function page () {
  return (
    <div>
        <WareHouseHero/>
        <SecondaryNav/>
        <ServiceList/>
        <WhyChooseUs/>
        <JunkRubbishItem/>
        <QuoteForm/>
    </div>
  )
}
