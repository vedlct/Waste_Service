"use client"

import React, { useEffect, useRef, useState } from 'react'
import Link from 'next/link';
import { ChevronDown, ChevronRight, Menu } from 'lucide-react';
import CartLink from '../cart/CartLink';

export default function Navbar() {

    const [navOpen, setNavOpen] = useState(null)
    const [menuOpen, setMenuOpen] = useState(false)
    const navRef = useRef(null)

    useEffect(() => {
        const closeMenusOutsideNavbar = (event) => {
            if (navRef.current && !navRef.current.contains(event.target)) {
                setNavOpen(null)
                setMenuOpen(false)
            }
        }

        const closeMenusWithEscape = (event) => {
            if (event.key === 'Escape') {
                setNavOpen(null)
                setMenuOpen(false)
            }
        }

        document.addEventListener('pointerdown', closeMenusOutsideNavbar)
        document.addEventListener('keydown', closeMenusWithEscape)

        return () => {
            document.removeEventListener('pointerdown', closeMenusOutsideNavbar)
            document.removeEventListener('keydown', closeMenusWithEscape)
        }
    }, [])

    const navItems = [
        {
            itemName: "Rubbish Removal",
            href: "/",
            subItem: [
                { name: "Prices", href: "/#prices" },
                { name: "House Clearance", href: "/houseClearance" },
                { name: "Garden Clearance", href: "/gardenClearance" },
                { name: "Flat Clearance", href: "/flatClearance" },
                { name: "Garage Clearance", href: "/garageClearance" },
                { name: "Furniture Removal & Disposal", href: "/furnitureClearance" },
                { name: "Builders Waste Removal", href: "/buildersWasteRemoval" },
                { name: "Junk Collection", href: "/junkCollection" },
                { name: "Wait & Load", href: "/waitLoad" }
            ]
        },

        {
            itemName: "Commercial Waste",
            href: "/",
            subItem: [
                { name: "Builders Waste Removal", href: "/buildersWasteRemoval" },
                { name: "Office Waste Clearance", href: "/officeWasteClearance" },
                { name: "Fly Tipping Clearance", href: "/flyTippingClearance" },
                { name: "Warehouse Rubbish Clearance", href: "/warehouseClearance" },
                { name: "Hotel & Pub Clearance", href: "/hotelPubClearance" },
                { name: "Restaurant Clearance", href: "/restaurantClearance" },
                { name: "Shop Strip Out & Clearance", href: "/shopStripOutClearance" },
                { name: "Wait & Load", href: "/waitLoad" }
            ]
        },

        {
            itemName: "Cleaning",
            href: "/",
            subItem: [
                { name: "Window Cleaning", href: "/windowCleaning" },
                { name: "Communal Area Cleaning", href: "/communalAreaCleaning" },
                { name: "Ground Maintainance", href: "/groundMaintainance" },
            ]
        },

        {
            itemName: "Garden Services",
            href: "/",
            subItem: [
                { name: "Lawn Mowing", href: "/lawnMowing" },
                { name: "Hedge Cutting", href: "/hedgeCutting" },
            ]
        },

        {
            itemName: "Areas Covered",
            href: "/area"
        },

        {
            itemName: "FAQ",
            href: "/faq"
        },

        {
            itemName: "Contact Us",
            href: "/contactUs"
        },
    ]

    return (
        <nav
            ref={navRef}
            className='order-1 w-auto shrink-0 bg-transparent text-[#11224D] lg:order-2 lg:min-w-0 lg:flex-1 lg:py-2'
            aria-label='Main navigation'
        >

            <div>

                {/* Mobile Menu Button */}
                <button
                    type='button'
                    aria-expanded={menuOpen}
                    aria-controls='main-menu'
                    onClick={() => setMenuOpen(!menuOpen)}
                    aria-label='Toggle navigation menu'
                    className='flex items-center justify-center rounded-md p-1.5 text-[#11224D] transition-colors hover:bg-[#EAF2FB] lg:hidden'
                >
                    <Menu
                        aria-hidden='true'
                        size={28}
                        strokeWidth={3}
                    />
                </button>


                {/* Main Menu */}
                <div
                    id='main-menu'
                    className={`
                        ${menuOpen ? 'flex' : 'hidden'}
                        absolute inset-x-0 top-full mt-2
                        max-h-[calc(100vh-6rem)]
                        flex-col overflow-y-auto
                        rounded-2xl
                        border border-[#11224D]/10
                        bg-white/95
                        px-4 pb-3
                        shadow-xl
                        backdrop-blur-md

                        sm:px-6

                        lg:static
                        lg:mt-0
                        lg:flex
                        lg:max-h-none
                        lg:flex-row
                        lg:items-center
                        lg:justify-center
                        lg:gap-1
                        lg:overflow-visible
                        lg:rounded-none
                        lg:border-0
                        lg:bg-transparent
                        lg:px-1
                        lg:pb-0
                        lg:shadow-none
                        lg:backdrop-blur-none

                        xl:gap-5
                        xl:px-2
                    `}
                >

                    {navItems.map((navItem, index) => (

                        <div
                            key={`${navItem.itemName}-${index}`}
                            className={`
                                group
                                relative
                                w-full
                                border-b
                                border-[#11224D]/10
                                last:border-0

                                lg:w-auto
                                lg:border-0

                                ${(index === 1 || index === 2)
                                    ? 'lg:hidden'
                                    : ''
                                }
                            `}
                        >

                            {/* Menu with Submenu */}
                            {navItem.subItem ? (

                                <button
                                    type='button'
                                    aria-expanded={navOpen === index}

                                    /* HOVER OPENS THE MENU */
                                    onMouseEnter={() => {
                                        setNavOpen(index)
                                    }}

                                    /* CLICK TO OPEN / CLOSE */
                                    onClick={() => {
                                        setNavOpen(
                                            navOpen === index
                                                ? null
                                                : index
                                        )
                                    }}

                                    className='flex w-full cursor-pointer flex-row items-center justify-between py-3 text-left font-semibold text-[#11224D] transition-colors hover:text-[#1A68A3] lg:w-auto lg:justify-center lg:whitespace-nowrap lg:py-0 lg:text-center lg:text-base'
                                >

                                    {navItem.itemName}

                                    <ChevronDown
                                        aria-hidden='true'
                                        size={16}
                                        className={`
                                            ml-1
                                            transition-transform
                                            duration-200
                                            ${navOpen === index
                                                ? 'rotate-180'
                                                : ''
                                            }
                                        `}
                                    />

                                </button>

                            ) : (

                                /* Normal Menu Item */
                                <Link
                                    href={navItem.href}
                                    onClick={() => {
                                        setMenuOpen(false)
                                        setNavOpen(null)
                                    }}
                                    className='flex w-full flex-row items-center justify-start py-3 text-left font-semibold text-[#11224D] transition-colors hover:text-[#1A68A3] lg:w-auto lg:justify-center lg:whitespace-nowrap lg:py-0 lg:text-center lg:text-base'
                                >
                                    {navItem.itemName}
                                </Link>

                            )}


                            {/* Main Submenu */}
                            {navItem.subItem && (

                                <div
                                    className={`
                                        ${navOpen === index
                                            ? 'block'
                                            : 'hidden'
                                        }

                                        pb-2
                                        text-[#11224D]

                                        lg:absolute
                                        lg:-right-10
                                        lg:top-10
                                        lg:z-50
                                        lg:rounded-md
                                        lg:bg-white/90
                                        lg:py-3
                                        lg:shadow-2xl
                                        lg:shadow-black/20
                                    `}
                                >

                                    {/* Submenu Items */}
                                    {navItem.subItem.map((subItem) => (

                                        <Link
                                            key={subItem.href}
                                            href={subItem.href}
                                            onClick={() => {
                                                setNavOpen(null)
                                                setMenuOpen(false)
                                            }}
                                            className='block cursor-pointer border-t border-[#11224D]/10 px-4 py-2 text-left text-[#11224D] transition-colors duration-150 hover:bg-[#EAF2FB] hover:text-[#11224D] focus-visible:bg-[#EAF2FB] focus-visible:text-[#11224D] focus-visible:outline-none lg:text-nowrap lg:border-b lg:border-t-0 lg:px-10 lg:text-left'
                                        >
                                            {subItem.name}
                                        </Link>

                                    ))}


                                    {/* Nested Submenus */}
                                    {index === 0 && navItems.slice(1, 3).map((nestedItem) => (

                                        <div
                                            key={nestedItem.itemName}
                                            className='group/nested relative hidden lg:block'
                                        >

                                            <button
                                                type='button'
                                                className='flex w-full cursor-pointer items-center justify-between gap-6 border-b border-[#11224D]/10 px-10 py-2 text-nowrap text-[#11224D] transition-colors duration-150 hover:bg-[#EAF2FB] focus-visible:bg-[#EAF2FB] focus-visible:outline-none'
                                            >

                                                <span>
                                                    {nestedItem.itemName}
                                                </span>

                                                <ChevronRight
                                                    aria-hidden='true'
                                                    className='size-4 transition-transform group-hover/nested:translate-x-1'
                                                />

                                            </button>


                                            {/* Nested Submenu */}
                                            <div
                                                className='absolute left-full top-0 z-50 hidden min-w-max rounded-md bg-white/95 py-3 shadow-2xl shadow-black/20 backdrop-blur-md group-hover/nested:block group-focus-within/nested:block'
                                            >

                                                {nestedItem.subItem.map((nestedSubItem) => (

                                                    <Link
                                                        key={nestedSubItem.href}
                                                        href={nestedSubItem.href}
                                                        onClick={() => {
                                                            setNavOpen(null)
                                                            setMenuOpen(false)
                                                        }}
                                                        className='block border-b border-[#11224D]/10 px-10 py-2 text-center text-nowrap text-[#11224D] transition-colors duration-150 last:border-b-0 hover:bg-[#EAF2FB] focus-visible:bg-[#EAF2FB] focus-visible:outline-none'
                                                    >
                                                        {nestedSubItem.name}
                                                    </Link>

                                                ))}

                                            </div>

                                        </div>

                                    ))}

                                </div>

                            )}

                        </div>

                    ))}

                    <div className='border-t border-[#11224D]/10 lg:hidden'>
                        <CartLink mobileMenu />
                    </div>

                </div>

            </div>

        </nav>
    )
}