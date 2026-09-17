import QuoteForm from '@/components/home/QuoteForm'
import ServiceList from '@/components/home/ServiceList'
import HotelPubHero from '@/components/hotelPubClearance/HotelPubHero'
import HotelRubbishItem from '@/components/hotelPubClearance/HotelRubbishItem'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import SecondaryNav from '@/components/layout/SecondaryNav'
import React from 'react'

export default function page () {
  return (
    <div>
        <HotelPubHero/>
        <SecondaryNav/>
        <WhyChooseUs/>
        <HotelRubbishItem/>
        <ServiceList/>
        <QuoteForm/>
    </div>
  )
}
