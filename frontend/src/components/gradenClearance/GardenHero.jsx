import ServiceHero from '../ServiceHero';

export default function GardenHero({ service, phone }) {
  return (
    <ServiceHero service={service} phone={phone}
      image="/images/GardenHero.jpg"
      imageAlt="Garden clearance service in Portsmouth"
      eyebrow="Garden clearance specialists"
      title="Garden clearance in Portsmouth"
      description="We remove garden waste efficiently and without fuss, leaving you with a cleaner outdoor space to enjoy."
      points={['Green and bulky waste', 'Over 90% recycled']}
    />
  );
}