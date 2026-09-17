import QuoteForm from '@/components/home/QuoteForm'
import ServiceList from '@/components/home/ServiceList'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import JunkRubbishItem from '@/components/junkCollection/JunkRubbishItem'
import SecondaryNav from '@/components/layout/SecondaryNav'
import WaitLoadHero from '@/components/waitLoad/WaitLoadHero'
import React from 'react'

export default function page () {
  return (
    <div>
        <WaitLoadHero/>
        <SecondaryNav/>
        <ServiceList/>
        <WhyChooseUs/>
        <JunkRubbishItem/>
        <QuoteForm/>
    </div>
  )
}
