import ServiceHero from '../ServiceHero';

export default function FlatHero() {
  return (
    <ServiceHero
      image="/images/FlatWaste.jpg"
      imageAlt="Flat clearance and rubbish removal"
      eyebrow="Flat clearance specialists"
      title="Flat clearance and rubbish removal in Chingford"
      description="A practical clearance service for flats, maisonettes and duplex properties, including lifting and removal."
      points={['Careful property access', 'Single items to full flats']}
    />
  );
}