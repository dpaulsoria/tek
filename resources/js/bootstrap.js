// resources/js/bootstrap.js
import axios from 'axios'


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
