import ClearanceDirectory from '../ClearanceDirectory';

const items = [
  'Armchairs', 'Chairs', 'Dining Room Chairs', 'Kitchen Sinks', 'Rugs', 'Bath Frames',
  'Chests of Drawers', 'Dining Tables', 'Kitchen Stripping', 'Shelves', 'Bathroom Sinks',
  'Coffee Tables', 'Dishwashers', 'Kitchen Tables', 'Skirting', 'Bathroom Stripping',
  'Conservatories', 'Extractor Fans', 'Laminate Flooring', 'Sofas', 'Bed Frames',
  'Conservatory Furniture', 'Floor Tiles', 'Lamps', 'Sound Systems', 'Bedside Tables',
  'Console Tables', 'Freezers', 'Mattresses', 'Tables', 'Bikes', 'Consoles', 'Fridges',
  'Microwaves', 'TVs', 'Blinds', 'Cookers', 'Gaming Chairs', 'Mirrors', 'Video Equipment',
  'Book Cases', 'Cupboards', 'Garages', 'Office Chairs', 'Wall Tiles', 'Builders Waste',
  'Curtain Doors', 'Garden Benches', 'Office Stripping', 'Wallpaper', 'Cabinets', 'Cushions',
  'Garden Chairs', 'Ovens', 'Wardrobes', 'Carpets', 'Cycles', 'Kitchen Cupboards', 'PCs',
  'Washing Machines', 'Cars', 'Desks', 'Kitchen Lighting', 'Printers', 'Window Frames',
  'Desktop Monitors', 'Radiators', 'Windows',
];

const titles = [
  'Furniture & household items',
  'Kitchen & bathroom waste',
  'Electrical & recreational items',
  'Building & mixed materials',
];

const size = Math.ceil(items.length / 4);

const categories = titles.map((title, index) => ({
  title,
  summary: 'Common fly-tipped items our local teams can remove responsibly.',
  items: items.slice(index * size, (index + 1) * size),
}));

const images = [1, 2, 3, 4].map((number) => ({
  src: `/images/flatRubbish${number}.jpg`,
  alt: 'Professional waste clearance and collection service',
}));

export default function FlyTippedRubbishRemoval() {
  return (
    <ClearanceDirectory
      eyebrow="Fly-tipping clearance directory"
      heading="Remove dumped waste."
      accent="Restore the area quickly."
      intro="Our branded teams clear fly-tipped waste across London and the Home Counties, often with same-day availability."
      note="Send the location and waste details so we can assess access and collection needs."
      categories={categories}
      images={images}
    />
  );
}