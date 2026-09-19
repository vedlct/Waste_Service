import ServiceHero from '../ServiceHero';

export default function FlatHero({ service, phone }) {
  return (
    <ServiceHero service={service} phone={phone}
      image="/images/FlatWaste.jpg"
      imageAlt="Flat clearance and rubbish removal"
      eyebrow="Flat clearance specialists"
      title="Flat clearance and rubbish removal in Portsmouth"
      description="A practical clearance service for flats, maisonettes and duplex properties, including lifting and removal."
      points={['Careful property access', 'Single items to full flats']}
    />
  );
}