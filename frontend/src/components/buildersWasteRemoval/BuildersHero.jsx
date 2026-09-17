import ServiceHero from '../ServiceHero';

export default function BuildersHero() {
  return (
    <ServiceHero
      image="/images/BuildersWaste.jpg"
      imageAlt="Builders waste ready for removal"
      eyebrow="Builders waste specialists"
      title="Builders waste removal in Chingford"
      description="A dependable collection service for renovation debris and building waste, handled efficiently by our local team."
      points={['Flexible load sizes', 'Responsible waste handling']}
    />
  );
}