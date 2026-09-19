import ServiceHero from '../ServiceHero';

export default function RestaurantHero({ service, phone }) {
  return (
    <ServiceHero service={service} phone={phone}
      image="/images/RestaurantWaste.jpg"
      imageAlt="Restaurant waste clearance service"
      eyebrow="Restaurant clearance specialists"
      title="Restaurant waste clearance in Portsmouth"
      description="Five-star clearance support for dining furniture, kitchen fittings and refurbishment waste."
      imagePosition="object-cover object-bottom"
      points={['Commercial clearance team', 'Flexible collection times']}
    />
  );
}