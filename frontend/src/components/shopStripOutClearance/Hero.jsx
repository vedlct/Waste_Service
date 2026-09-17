import ServiceHero from '../ServiceHero';

export default function Hero() {
  return (
    <ServiceHero
      image="/images/shopHero.jpg"
      imageAlt="Strip Out & Shop Clearance service in Chingford"
      eyebrow="Strip Out & Shop Clearance specialists"
      title="Strip Out & Shop Clearance in Chingford"
      description="We make Strip Out & Shop Clearance efficiently and without fuss, leaving you with a cleaner outdoor space to enjoy."
      points={['Green and bulky waste', 'Over 90% recycled']}
    />
  );
}