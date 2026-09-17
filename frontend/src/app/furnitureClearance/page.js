import FurnitureHero from '@/components/furnitureClearance/FurnitureHero'
import FurnitureRubbishItems from '@/components/furnitureClearance/FurnitureRubbishItems'
import QuoteForm from '@/components/home/QuoteForm'
import ServiceList from '@/components/home/ServiceList'
import WhyChooseUs from '@/components/houseClearance/WhyChooseUs'
import SecondaryNav from '@/components/layout/SecondaryNav'
import React from 'react'

export default function page () {
  return (
    <div>
        <FurnitureHero/>
        <SecondaryNav/>
        <ServiceList/>
        <WhyChooseUs/>
        <FurnitureRubbishItems/>
        <QuoteForm/>
    </div>
  )
}
