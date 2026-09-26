import ServiceHero from '../ServiceHero';

export default function Hero({ service, phone }) {
  return (
    <ServiceHero
      service={service}
      phone={phone}
      image='/images/waste4.jpg'
      imageAlt='House clearance and rubbish removal service in Portsmouth'
      eyebrow='House clearance specialists'
      title='House clearance and rubbish removal in Portsmouth'
      description='Save time, heavy lifting and trips to the local tip. Our experienced team clears single items, rooms and complete properties with care.'
      points={['Flexible collection times', 'Responsible waste handling']}
    />
  );
}
