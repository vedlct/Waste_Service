import ServiceHero from '../ServiceHero';

export default function LawnHero() {
  return (
    <ServiceHero
      image="/images/Lawn.jpg"
      imageAlt="Freshly maintained lawn in Chingford"
      eyebrow="Lawn care specialists"
      title="Lawn mowing in Chingford"
      description="Reliable lawn mowing that keeps your outdoor space looking tidy, healthy and professionally maintained."
      points={['Flexible mowing schedules', 'Clean, consistent finish']}
    />
  );
}