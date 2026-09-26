// Site navigation, shared by the header's services menu and the footer.

export const SERVICE_GROUPS = [
  {
    title: 'Rubbish Removal',
    links: [
      { name: 'House Clearance', href: '/houseClearance' },
      { name: 'Garden Clearance', href: '/gardenClearance' },
      { name: 'Flat Clearance', href: '/flatClearance' },
      { name: 'Garage Clearance', href: '/garageClearance' },
      { name: 'Furniture Removal', href: '/furnitureClearance' },
      { name: 'Builders Waste', href: '/buildersWasteRemoval' },
      { name: 'Junk Collection', href: '/junkCollection' },
      { name: 'Wait & Load', href: '/waitLoad' },
    ],
  },
  {
    title: 'Commercial Waste',
    links: [
      { name: 'Office Waste Clearance', href: '/officeWasteClearance' },
      { name: 'Fly-Tipping Clearance', href: '/flyTippingClearance' },
      { name: 'Warehouse Clearance', href: '/warehouseClearance' },
      { name: 'Hotel & Pub Clearance', href: '/hotelPubClearance' },
      { name: 'Restaurant Clearance', href: '/restaurantClearance' },
      { name: 'Shop Strip Out', href: '/shopStripOutClearance' },
    ],
  },
  {
    title: 'Cleaning',
    links: [
      { name: 'Window Cleaning', href: '/windowCleaning' },
      { name: 'Communal Area Cleaning', href: '/communalAreaCleaning' },
      { name: 'Grounds Maintenance', href: '/groundMaintainance' },
    ],
  },
  {
    title: 'Garden Services',
    links: [
      { name: 'Lawn Mowing', href: '/lawnMowing' },
      { name: 'Hedge Cutting', href: '/hedgeCutting' },
    ],
  },
]

export const MAIN_LINKS = [
  { name: 'Prices', href: '/prices' },
  { name: 'Areas', href: '/area' },
  { name: 'FAQ', href: '/faq' },
  { name: 'Contact', href: '/contactUs' },
]
