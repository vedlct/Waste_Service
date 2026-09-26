/**
 * The outward part of a UK postcode ("PO5 1AB" -> "PO5"), matching the backend's
 * `CoverageArea::outwardCode`: a full postcode loses its last three characters.
 */
export function outwardCode(postcode) {
  const normalised = String(postcode ?? '').replace(/\s+/g, '').toUpperCase()

  return normalised.length > 4 ? normalised.slice(0, -3) : normalised
}

/**
 * The name of the covered area whose postcode district matches, or null.
 */
export function coveredArea(coverage, postcode) {
  const outward = outwardCode(postcode)
  if (!outward) return null

  for (const region of coverage ?? []) {
    const area = (region.areas ?? []).find((entry) => entry.postcode_prefix && entry.postcode_prefix.toUpperCase() === outward)
    if (area) return area.name
  }

  return null
}
