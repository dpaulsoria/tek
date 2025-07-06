import { Attention } from "../Atenciones/type"
import { Person } from "../Clientes/type"

export interface Cite {
  id: number
  date: string
  time_arrival: string
  cliente_id: number
  amount_attention: number
  total_service: number
  status: 'pendiente' | 'confirmada' | 'cancelada'
  person: Person
  attentions: Attention[]
}

export enum CITE_STATE {
    PENDIENTE = 'PENDIENTE',
    CONFIRMADA = 'CONFIRMADA',
    CANCELADA = 'CANCELADA',
}
