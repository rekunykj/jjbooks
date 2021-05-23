import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import( '../views/Home.vue')
  },
  {
    path: '/about',
    name: 'About',
    component: () => import( '../views/About.vue')
  },
  {
    path: '/books',
    name: 'Books',
    component: () => import( '../views/Books.vue')
  },
  {
    path: '/orders',
    name: 'Orders',
    component: () => import( '../views/BookOrders')
  }
]

const router = new VueRouter({
  routes
})

export default router
