// resources/js/bootstrap.js

import axios from 'axios'

// Base URL para tus peticiones a Laravel
axios.defaults.baseURL = import.meta.env.VITE_APP_URL || 'http://localhost:8000'

// Incluir cookies y CSRF token
axios.defaults.withCredentials = true

// Extra: extrae el token CSRF de la meta tag en tu Blade
const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}