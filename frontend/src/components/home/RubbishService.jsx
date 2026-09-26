import Image from 'next/image'
import React from 'react'
import ServiceList from './ServiceList'

export default function RubbishService () {
  return (
    <div>
        <div className='relative w-full overflow-hidden bg-brand-600'>
            <Image src={"/images/Place.webp"} alt="" fill sizes='100vw' className='object-cover object-center'/>
            <Image src={"/images/Black.png"} alt="" fill sizes='100vw' className='object-cover opacity-75'/>
            <ServiceList/>
        </div>
    </div>
  )
}
