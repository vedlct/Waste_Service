import ServiceHero from '../ServiceHero';

export default function FurnitureHero() {
  return (
    <ServiceHero
      image="/images/furnitureHero.jpg"
      imageAlt="Furniture Disposal service in Chingford"
      eyebrow="Furniture Disposal specialists"
      title="Furniture Disposal in Chingford"
      description="We make furniture disposal efficiently and without fuss, leaving you with a cleaner outdoor space to enjoy."
      points={['Green and bulky waste', 'Over 90% recycled']}
    />
  );
}