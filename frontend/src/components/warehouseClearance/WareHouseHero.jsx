import ServiceHero from '../ServiceHero';

export default function WareHouseHero({ service, phone }) {
  return (
    <ServiceHero service={service} phone={phone}
      image="/images/warehouse.jpg"
      imageAlt="Warehouse Clearance service in Portsmouth"
      eyebrow="Warehouse Clearance specialists"
      title="Warehouse Clearance in Portsmouth"
      description="Reliable collections for warehouse rubbish and business waste."
      points={['Green and bulky waste', 'Over 90% recycled']}
    />
  );
}


