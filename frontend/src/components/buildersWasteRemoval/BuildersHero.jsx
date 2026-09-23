import ServiceHero from '../ServiceHero';

export default function BuildersHero({ service, phone }) {
  return (
    <ServiceHero service={service} phone={phone}
      image="/images/BuildersWaste.jpg"
      imageAlt="Builders waste ready for removal"
      eyebrow="Builders waste specialists"
      title="Builders waste removal in Portsmouth"
      description="A dependable collection service for renovation debris and building waste, handled efficiently by our local team."
      points={['Flexible load sizes', 'Responsible waste handling']}
    />
  );
}