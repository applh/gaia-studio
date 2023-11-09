import { createRouter, createWebHistory } from 'vue-router'

let base_url = import.meta.env.BASE_URL;
console.log('base_url', base_url);
base_url = window.location.pathname;
console.log('base_url', base_url);

const router = createRouter({
  history: createWebHistory(base_url),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/HomeView.vue')

    },
    {
      path: '/settings',
      name: 'settings',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/SettingsView.vue')
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    },
  ]
})

// set home as default route
// router.push({ name: 'home', replace: true })

export default router
