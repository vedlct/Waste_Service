import ClearanceDirectory from '../ClearanceDirectory';

const categories = [
  {
    title: 'Green waste & planting',
    summary: 'Natural garden material and planting waste.',
    items: ['Hedges & Shrubs', 'Trees', 'Turf', 'Christmas Trees', 'Topsoil', 'Tree Stumps', 'Soil Waste'],
  },
  {
    title: 'Structures & boundaries',
    summary: 'Outdoor structures, fencing and boundary materials.',
    items: ['Sheds', 'Fence Posts', 'Fencing', 'Old Brick Walls', 'Garages', 'Outbuildings', 'Conservatories', 'Wire Fencing'],
  },
  {
    title: 'Landscaping materials',
    summary: 'Heavy material from garden improvement work.',
    items: ['Block Paving', 'Bricks', 'Old Patios', 'Stones', 'Builders Waste', 'Concrete', 'Rubble', 'Tarmac Drives'],
  },
  {
    title: 'Outdoor features',
    summary: 'Furniture, leisure pieces and garden accessories.',
    items: ['BBQs', 'Extensions', 'Water Features', 'Garden Awnings', 'Patio Umbrellas', 'Garden Furniture', 'Ponds', 'Swimming Pools', 'Garden Pots'],
  },
];

const images = [1, 2, 3, 4].map((number) => ({
  src: `/images/garden${number}.jpg`,
  alt: 'Professional waste clearance and collection service',
}));

export default function GardenRubbishItems() {
  return (
    <ClearanceDirectory
      eyebrow="Garden clearance directory"
      heading="Clear the garden."
      accent="Keep the space you enjoy."
      intro="We remove green waste, outdoor structures and heavy landscaping material across Chingford."
      note="Tell us what is in your garden and we will confirm the right collection."
      categories={categories}
      images={images}
    />
  );
}