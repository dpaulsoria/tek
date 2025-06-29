// resources/js/ziggy-shim.js
import { route as routeOriginal } from 'ziggy-js'
import { Ziggy } from './ziggy.js'

// Este route siempre ignora absolute=true
export function route(name, params = {}) {
  return routeOriginal(name, params, /* absolute */ false, Ziggy)
}
