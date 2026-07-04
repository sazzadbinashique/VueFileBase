import axios from 'axios'

export async function fetchCmsPage(slug) {
  try {
    const { data } = await axios.get(`/api/pages/${slug}`)
    return data
  } catch {
    return null
  }
}

export function cmsText(page, locale, enField, bnField, fallback = '') {
  if (!page) return fallback
  const isBn = locale === 'bn'
  const val = isBn ? page[bnField] : page[enField]
  return val || fallback
}

export function cmsLayout(page, locale) {
  if (!page) return {}
  const isBn = locale === 'bn'
  const raw = isBn ? page.layout_json_bn : page.layout_json
  if (!raw) return {}
  try {
    return typeof raw === 'string' ? JSON.parse(raw) : raw
  } catch {
    return {}
  }
}
