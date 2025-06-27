import { createRouter, createWebHistory } from 'vue-router';
import Login     from '@/Pages/Login.vue';
// import Register  from '@/Pages/Register.vue';
// import Dashboard from '@/Pages/Dashboard.vue';

const routes = [
  { path: '/login',    component: Login },
//   { path: '/register', component: Register },
//   { path: '/dashboard',component: Dashboard },
  // si quieres redirigir / a /login:
  { path: '/', redirect: '/login' },
];

export default createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});
