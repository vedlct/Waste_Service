import QuoteForm from '@/components/home/QuoteForm'
import ServiceList from '@/components/home/ServiceList'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import JunkRubbishItem from '@/components/junkCollection/JunkRubbishItem'
import SecondaryNav from '@/components/layout/SecondaryNav'
import Hero from '@/components/shopStripOutClearance/Hero'
import React from 'react'

export default function page () {
  return (
    <div>
        <Hero/>
        <SecondaryNav/>
        <WhyChooseUs/>
        <ServiceList/>
        <JunkRubbishItem/>
        <QuoteForm/>
    </div>
  )
}
