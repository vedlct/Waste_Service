import ServiceHero from '../ServiceHero';

export default function GarageHero() {
  return (
    <ServiceHero
      image="/images/garage.jpg"
      imageAlt="Garage ready for professional clearance"
      eyebrow="Garage clearance specialists"
      title="Garage clearance and junk removal in Chingford"
      description="Clear accumulated furniture, equipment and rubbish from garages and sheds with help from our removal team."
      points={['Heavy lifting included', 'Garages and sheds cleared']}
    />
  );
}