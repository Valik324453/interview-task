import { createRouter, createWebHistory } from 'vue-router';
import Register from '../views/Register.vue';
import Login from '../views/Login.vue';
import Home from '../views/Home.vue';

import { API_HOST } from '@/config';

// Определяем маршруты
const routes = [
  { path: '/', redirect: '/login' },
  { path: '/register', component: Register },
  { path: '/login', component: Login },
  { path: '/home', component: Home },
];

// Создаем маршрутизатор
const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Функция для проверки аутентификации
async function isAuthenticated() {
  try {
    const response = await fetch(`${API_HOST}/user.php`, {
      method: 'GET',
      credentials: 'include',
    });
    const data = await response.json();
    return data.status === 'success';
  } catch (error) {
    console.error('Authentication check failed:', error);
    return false;
  }
}

// Глобальный перед-охранитель
router.beforeEach(async (to, from, next) => {
  const isAuth = await isAuthenticated();
  if (to.path === '/home' && !isAuth) {
    // Если не аутентифицирован, перенаправляем на /login
    next('/login');
  } else {
    // Если аутентифицирован или переход не на /home, разрешаем переход
    next();
  }
});

export default router;