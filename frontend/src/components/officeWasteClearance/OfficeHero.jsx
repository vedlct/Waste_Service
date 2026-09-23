import ServiceHero from '../ServiceHero';

export default function OfficeHero({ service, phone }) {
  return (
    <ServiceHero service={service} phone={phone}
      image="/images/OfficeWaste.jpg"
      imageAlt="Office waste clearance service"
      eyebrow="Office clearance specialists"
      title="Office waste clearance in Portsmouth"
      description="Clear furniture, equipment and workplace waste with minimal disruption to your team and premises."
      points={['Flexible business collections', 'Furniture and equipment cleared']}
    />
  );
}