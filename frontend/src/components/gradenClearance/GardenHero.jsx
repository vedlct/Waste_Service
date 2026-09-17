import ServiceHero from '../ServiceHero';

export default function GardenHero() {
  return (
    <ServiceHero
      image="/images/GardenHero.jpg"
      imageAlt="Garden clearance service in Chingford"
      eyebrow="Garden clearance specialists"
      title="Garden clearance in Chingford"
      description="We remove garden waste efficiently and without fuss, leaving you with a cleaner outdoor space to enjoy."
      points={['Green and bulky waste', 'Over 90% recycled']}
    />
  );
}