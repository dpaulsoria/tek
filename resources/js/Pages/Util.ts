/**
 * Dado un ISO-string como "1986-07-09T00:00:00.000000Z",
 * devuelve "YYYY-MM-DD HH:mm", p.ej. "1986-07-09 00:00".
 * Si el input es HH:mm:ss, devuelve el input.
 */
export function formatDate(input: string): string {
  if (/^\d{2}:\d{2}:\d{2}$/.test(input)) {
    return input;
  }
  if (/^\d{4}-\d{2}-\d{2}T/.test(input)) {
    const [datePart, timeWithZone] = input.split('T');
    const timeWithoutMs = timeWithZone.split('.')[0];   // "00:00:00"
    const [hh, mm]      = timeWithoutMs.split(':');     // ["00","00","00"]
    return `${datePart} ${hh}:${mm}`;
  }
  return input;

}
