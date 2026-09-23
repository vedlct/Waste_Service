import ServiceHero from '../ServiceHero';

export default function WaitLoadHero({ service, phone }) {
  return (
    <ServiceHero service={service} phone={phone}
      image="/images/LoadHero.jpg"
      imageAlt="Wait and load collection in Portsmouth"
      eyebrow="Junk Collection specialists"
      title="Wait and load collection in Portsmouth"
      description="A practical option when waste is ready to load quickly."
      points={['Green and bulky waste', 'Over 90% recycled']}
    />
  );
}
