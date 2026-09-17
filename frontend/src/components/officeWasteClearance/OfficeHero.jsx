import ServiceHero from '../ServiceHero';

export default function OfficeHero() {
  return (
    <ServiceHero
      image="/images/OfficeWaste.jpg"
      imageAlt="Office waste clearance service"
      eyebrow="Office clearance specialists"
      title="Office waste clearance in Chingford"
      description="Clear furniture, equipment and workplace waste with minimal disruption to your team and premises."
      points={['Flexible business collections', 'Furniture and equipment cleared']}
    />
  );
}