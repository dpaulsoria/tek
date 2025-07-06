/**
 * Dado un ISO-string como "1986-07-09T00:00:00.000000Z",
 * devuelve "YYYY-MM-DD HH:mm", p.ej. "1986-07-09 00:00".
 */
export function formatDate(input: string): string {
  const d = new Date(input)
  const yyyy = d.getFullYear()
  const MM   = String(d.getMonth() + 1).padStart(2, '0')
  const dd   = String(d.getDate()).padStart(2, '0')
  const hh   = String(d.getHours()).padStart(2, '0')
  const mm   = String(d.getMinutes()).padStart(2, '0')
  return `${yyyy}-${MM}-${dd} ${hh}:${mm}`
}
