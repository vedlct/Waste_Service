import ServiceHero from '../ServiceHero';

export default function FurnitureHero({ service, phone }) {
  return (
    <ServiceHero service={service} phone={phone}
      image="/images/furnitureHero.jpg"
      imageAlt="Furniture Disposal service in Portsmouth"
      eyebrow="Furniture Disposal specialists"
      title="Furniture Disposal in Portsmouth"
      description="Clear promises. Reliable collections."
      points={['Green and bulky waste', 'Over 90% recycled']}
    />
  );
}