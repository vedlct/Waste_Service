import ServiceHero from '../ServiceHero';

export default function WaitLoadHero() {
  return (
    <ServiceHero
      image="/images/LoadHero.jpg"
      imageAlt="Junk Collection service in Chingford"
      eyebrow="Junk Collection specialists"
      title="Junk Collection in Chingford"
      description="We remove Junk Collection efficiently and without fuss, leaving you with a cleaner outdoor space to enjoy."
      points={['Green and bulky waste', 'Over 90% recycled']}
    />
  );
}
