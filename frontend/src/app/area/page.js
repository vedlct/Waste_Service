import AreaIntro from '@/components/area/AreaIntro'
import AreasWeCoverSection from '@/components/area/AreasWeCoverSection'
import { apiGet } from '@/lib/api'
import { metadataFor } from '@/lib/seo'

// Title and description come from the SEO fields in the admin, with this fallback
// when the backend is unreachable.
export function generateMetadata() {
  return metadataFor('/area', { title: 'Areas We Cover | MR. TEE Removals' })
}

export default async function AreaPage() {
  const regions = await apiGet('coverage')

  const areas = regions
    ?.map((region) => ({
      division: region.name,
      places: (region.areas ?? []).map((area) => area.name),
    }))
    .filter((region) => region.places.length > 0)

  return (
    <div>
      <AreaIntro />
      <AreasWeCoverSection areas={areas} />
    </div>
  )
}
