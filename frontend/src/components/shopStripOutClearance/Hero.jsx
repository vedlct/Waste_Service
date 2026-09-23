import ServiceHero from '../ServiceHero';

export default function Hero({ service, phone }) {
  return (
    <ServiceHero service={service} phone={phone}
      image="/images/shopHero.jpg"
      imageAlt="Strip Out & Shop Clearance service in Portsmouth"
      eyebrow="Strip Out & Shop Clearance specialists"
      title="Strip Out & Shop Clearance in Portsmouth"
      description="A practical service for retail strip-outs and shop clearances."
      points={['Green and bulky waste', 'Over 90% recycled']}
    />
  );
}