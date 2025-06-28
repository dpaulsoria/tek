export interface Cite {
  id: number
  date: string
  time_arrival: string
  cliente_id: number
  amount_attention: number
  total_service: number
  status: 'pendiente' | 'confirmada' | 'cancelada'
  // si quieres acceso al nombre del cliente:
  person?: { id: number; name: string }
}
