import Image from 'next/image'
import React, { useEffect, useState } from 'react'
import { ArrowRight } from 'lucide-react';
import Navbar from './Navbar';
import Link from 'next/link';
import CartLink from '../cart/CartLink';

export default function Header () {
    const [isScrolled, setIsScrolled] = useState(false)

    useEffect(()=>{
        const handleScroll = () => {
            if (window.scrollY > 10) {
                setIsScrolled(true);
            } else {
                setIsScrolled(false);
            }
            
        };

        window.addEventListener('scroll', handleScroll);

        return ()=> {
            window.removeEventListener('scroll', handleScroll);
        }
    }, []);
  return (
    <header className={`fixed inset-x-0 z-50 mx-auto w-[calc(100%-1.5rem)] max-w-7xl rounded-2xl bg-white/80 shadow-lg backdrop-blur-md lg:rounded-full transition-all duration-300 ${isScrolled? 'top-0 lg:top-0' : 'top-3 lg:top-5'}`}>
        <div className='flex w-full flex-nowrap items-center justify-start gap-3 px-3 py-2 sm:gap-4 sm:px-6 lg:gap-5 lg:px-8'>
            <Link href="/" className='order-2 flex shrink-0 items-center lg:order-1'>
               <Image
                className='mx-auto h-auto w-14 sm:w-16 lg:mx-0 lg:w-16'
                src={"/images/MainLogo.png"}
                alt="Waste Services"
                width={60}
                height={40}
                priority
               />
            </Link>

            <Navbar/>

            <div className='order-3 ml-auto flex shrink-0 items-center justify-center gap-3 lg:ml-0'>
                <div className='hidden lg:block'>
                    <CartLink />
                </div>
                <a href='tel:02082266477' className='hidden whitespace-nowrap text-lg font-bold leading-none text-[#0398E9] xl:block'>020 8226 6477</a>
                <Link href="/#prices" className='group flex items-center justify-center gap-1.5 whitespace-nowrap rounded-full border border-[#11224D] bg-[#11224D] px-2 py-1 text-xs font-semibold text-white transition-colors hover:bg-white hover:text-[#11224D] sm:px-2.5 md:gap-2 md:px-5 md:py-1.5 md:text-sm lg:px-4 xl:px-6'>
                    Prices &amp; Book
                    <ArrowRight className='size-4 rounded-full bg-white p-0.5 text-[#11224D] transition duration-200 group-hover:translate-x-1 md:size-5 md:p-1'/>
                </Link >
            </div>
        </div>
    </header>
  )
}
