import Vue from 'vue'
import Router from 'vue-router'

import verifycodeLogin from '../components/auth/verifyCodeLogin/verifycode-login.vue'
import passwordLogin from '../components/auth/passwordLogin/password-login.vue'
import resetPassword from '../components/auth/resetPassword/resetPassword.vue'

Vue.use(Router)

let routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    component: verifycodeLogin,
    children: [
      {
        path: 'password',
        component: passwordLogin,
        children: [
          {
            path: 'reset',
            component: resetPassword
          }
        ]
      }
    ]

  }
]

export default new Router({
  routes
})