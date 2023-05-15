// Composables
import { createRouter, createWebHistory } from 'vue-router'
import { useAppStore } from '@/store/app'
import jwtDecode from 'jwt-decode'

const routes = [
  {
    path: '/',
    component: () => import('@/layouts/default/Default.vue'),
    children: [
      {
        path: '',
        name: 'Home',
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import(/* webpackChunkName: "home" */ '@/views/HomeView.vue'),
      },
      {
        path: '/login',
        name: 'Login',
        component: () => import(/* webpackChunkName: "login" */ '@/views/LoginView.vue'),
      },
      {
        path: '/register',
        name: 'Register',
        component: () => import(/* webpackChunkName: "register" */ '@/views/RegisterView.vue'),
      },
      {
        path: '/assignment/:id',
        name: 'AssignmentView',
        props: true,
        component: () => import(/* webpackChunkName: "assignment" */ '@/views/Student/AssignmentView'),
      }
    ],
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
})

const allowList = ['Login', 'Register']

router.beforeEach((to) => {
  const store = useAppStore()

  if (!allowList.includes(to.name) && !store.user) {
    return { name: 'Login' }
  }

  if (to.name === 'Login' && store.user) {
    return { name: 'Home' }
  }
})

export default router
