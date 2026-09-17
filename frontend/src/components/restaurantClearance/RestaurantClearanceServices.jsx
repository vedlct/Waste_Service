import ClearanceDirectory from '../ClearanceDirectory';

const categories = [
  {
    title: 'Dining & customer areas',
    summary: 'Furniture and fittings from front-of-house spaces.',
    items: ['Bars', 'Restaurant Furniture', 'Tables & Chairs', 'Stairs'],
  },
  {
    title: 'Kitchen & storage',
    summary: 'Back-of-house fixtures and storage installations.',
    items: ['Cupboards', 'Kitchens', 'Storage Units'],
  },
  {
    title: 'Fixtures & interiors',
    summary: 'Built-in finishes removed during a strip-out.',
    items: ['Ceilings', 'Flooring', 'Bathrooms', 'Doors'],
  },
  {
    title: 'Refurbishment material',
    summary: 'Mixed material produced by improvement work.',
    items: ['Building Waste', 'Refurb Waste'],
  },
];

const images = [1, 2, 3, 4].map((number) => ({
  src: `/images/restaurantRubbish${number}.jpg`,
  alt: 'Professional waste clearance and collection service',
}));

export default function RestaurantClearanceServices() {
  return (
    <ClearanceDirectory
      eyebrow="Restaurant clearance directory"
      heading="Clear the premises."
      accent="Prepare for what is next."
      intro="From dining furniture to kitchen fittings, we organise restaurant clearances around your schedule."
      note="Share your inventory or refurbishment plan and we will confirm collection options."
      categories={categories}
      images={images}
    />
  );
}