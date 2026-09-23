import ServiceHero from '../ServiceHero';

export default function LawnHero({ service, phone }) {
  return (
    <ServiceHero service={service} phone={phone}
      image="/images/Lawn.jpg"
      imageAlt="Freshly maintained lawn in Portsmouth"
      eyebrow="Lawn care specialists"
      title="Lawn mowing in Portsmouth"
      description="Reliable lawn mowing that keeps your outdoor space looking tidy, healthy and professionally maintained."
      points={['Flexible mowing schedules', 'Clean, consistent finish']}
    />
  );
}