// resources/js/bootstrap.js
import axios from 'axios'


axios.defaults.baseURL = import.meta.env.VITE_APP_URL || 'http://localhost:8000'
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
window.axios = axios"SQLSTATE[HY000] [2002] Cannot connect to MySQL using SSL (Connection: mysql, SQL: select * from `sessions` where `id` = eTsnwhSa7Pxlgn85UvyCCgXuSSAsJuNnyZJDVZsx limit 1)"
