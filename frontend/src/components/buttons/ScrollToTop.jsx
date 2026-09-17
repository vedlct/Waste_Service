"use client"
import React, { useState, useEffect } from 'react'
import { ArrowUp } from 'lucide-react';

export default function ScrollToTop () {
    const[showButton, setShowButton]= useState(false);

    useEffect(() => {
        function handleScroll() {
            if (window.scrollY > 300) {
                setShowButton(true);
            } else {
                setShowButton(false);
            }
        }
    
    window.addEventListener('scroll', handleScroll);
    }, []);

    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  return (
    <div>
        <button onClick={scrollToTop} className={`p-5 rounded-full bg-sky-500/50 hover:bg-[#11224D] hover:text-white ${showButton? 'opacity-100': 'opacity-0 pointer-events-none'}`}>
            <ArrowUp size={30}/>
        </button>
    </div>
  )
}
