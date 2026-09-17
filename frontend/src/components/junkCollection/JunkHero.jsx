import ServiceHero from '../ServiceHero';

export default function JunkHero() {
  return (
    <ServiceHero
      image="/images/junkHero.jpg"
      imageAlt="Junk Collection service in Chingford"
      eyebrow="Junk Collection specialists"
      title="Junk Collection in Chingford"
      description="We remove Junk Collection efficiently and without fuss, leaving you with a cleaner outdoor space to enjoy."
      points={['Green and bulky waste', 'Over 90% recycled']}
    />
  );
}
