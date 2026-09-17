import ServiceHero from '../ServiceHero';

export default function HotelPubHero() {
  return (
    <ServiceHero
      image="/images/hotelHero.jpg"
      imageAlt="Hotel & Pub Waste Clearance service in Chingford"
      eyebrow="Hotel & Pub Waste Clearance specialists"
      title="Hotel & Pub Waste Clearance in Chingford"
      description="We make Hotel & Pub Waste Clearance efficiently and without fuss, leaving you with a cleaner outdoor space to enjoy."
      points={['Green and bulky waste', 'Over 90% recycled']}
    />
  );
}