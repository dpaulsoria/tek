// resources/js/bootstrap.js
import axios from 'axios'
import { Ziggy }               from './ziggy.js'
import { route as routeHelper } from 'ziggy-js'

Ziggy.url = window.location.origin
Ziggy.absolute = false

route = (name, params = {}) =>
  routeHelper(name, params, /* absolute */ false, Ziggy)

axios.defaults.withCredentials = true
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['Accept'] = 'application/json'

// CSRF cookie de Sanctum
;(async () => {
  try {
    await axios.get('/sanctum/csrf-cookie')
  } catch (error) {
    console.error('Error al obtener la cookie CSRF:', error)
  }
})()
// Exportar global
window.axios = axios
