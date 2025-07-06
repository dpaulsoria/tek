import { Cite } from "../Citas/type"
import { Service } from "../Servicios/type"

export interface Attention {
  id: number
  date: string
  cite_id: number
  service_id: number
  price_service: number
  // para mostrar nombre de cita y servicio en los selects o en la tabla:
  cite?: Cite
  service?: Service
}
