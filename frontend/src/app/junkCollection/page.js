import WhyChooseOurService from '@/components/buildersWasteRemoval/WhyChooseOurService'
import QuoteForm from '@/components/home/QuoteForm'
import ServiceList from '@/components/home/ServiceList'
import JunkHero from '@/components/junkCollection/JunkHero'
import JunkRubbishItem from '@/components/junkCollection/JunkRubbishItem'
import SecondaryNav from '@/components/layout/SecondaryNav'
import React from 'react'

export default function page () {
  return (
    <div>
        <JunkHero/>
        <SecondaryNav/>
        <WhyChooseOurService/>
        <ServiceList/>
        <JunkRubbishItem/>
        <QuoteForm/>
    </div>
  )
}
