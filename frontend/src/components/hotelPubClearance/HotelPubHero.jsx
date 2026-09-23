import ServiceHero from '../ServiceHero';

export default function HotelPubHero({ service, phone }) {
  return (
    <ServiceHero service={service} phone={phone}
      image="/images/hotelHero.jpg"
      imageAlt="Hotel & Pub Waste Clearance service in Portsmouth"
      eyebrow="Hotel & Pub Waste Clearance specialists"
      title="Hotel & Pub Waste Clearance in Portsmouth"
      description="Clearance support for refurbishing rooms, bars and hospitality spaces."
      points={['Green and bulky waste', 'Over 90% recycled']}
    />
  );
}