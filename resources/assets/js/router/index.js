import Vue from 'vue'
import Router from 'vue-router'

import Login from '../components/auth/verifyCodeLogin/login.vue'

Vue.use(Router)

let routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    component: Login
  }
]

export default new Router({
  routes
})