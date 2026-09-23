import ServiceHero from '../ServiceHero';

export default function JunkHero({ service, phone }) {
  return (
    <ServiceHero service={service} phone={phone}
      image="/images/junkHero.jpg"
      imageAlt="Junk Collection service in Portsmouth"
      eyebrow="Junk Collection specialists"
      title="Junk Collection in Portsmouth"
      description="Reliable collections for unwanted household junk."
      points={['Green and bulky waste', 'Over 90% recycled']}
    />
  );
}
