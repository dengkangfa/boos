import Vue from 'vue'
import VueRouter from 'vue-router'
import Store from '../store/index'
import jwtToken from '../helpers/jwt'

import verifycodeLogin from '../components/auth/verifyCodeLogin/verifycode-login.vue'
import passwordLogin from '../components/auth/passwordLogin/password-login.vue'
import resetPassword from '../components/auth/resetPassword/resetPassword.vue'
import identitySelect from '../components/info/identity/identity-select.vue'
import basicInfo from '../components/info/job/basic-info.vue'
import educationExperience from '../components/info/job/education-experience.vue'
import Advantage from '../components/info/job/advantage.vue'

Vue.use(VueRouter)

let routes = [
  {
    path: '/',
    name: 'index',
    redirect: {'name': 'select-identity'},
    meta: {requiresAuth: true}
  },
  {
    path: '/login',
    name: 'login',
    component: verifycodeLogin,
    children: [
      {
        path: 'password',
        component: passwordLogin,
        children: [
          {
            path: 'reset',
            component: resetPassword,
            meta: {requiresGuest: true}
          }
        ],
        meta: {requiresGuest: true}
      }
    ],
    meta: {requiresGuest: true}
  },
  {
    path: '/identity',
    name: 'select-identity',
    component: identitySelect,
    meta: {requiresAuth: true},
    children: [
      {
        path: '/job/basicinfo',
        name: 'job-basic-info',
        component: basicInfo,
        meta: {requiresAuth: true},
        children: [
          {
            path: '/job/education',
            name: 'job-education',
            component: educationExperience,
            meta: {requiresAuth: true},
            children: [
              {
                path: '/job/advantage',
                name: 'job-advantage',
                component: Advantage,
                meta: {requiresAuth: true}
              }
            ]
          }
        ]
      }
    ]
  },
  {
    path: '*',
    redirect: '/'
  }
]

const router = new VueRouter({
  routes
})

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth) {
    if (Store.state.AuthUser.authenticated || jwtToken.getToken()) {
      return next()
    } else {
      return next({'name': 'login'})
    }
  }
  if (to.meta.requiresGuest) {
    if (Store.state.AuthUser.authenticated || jwtToken.getToken()) {
      return next({'name': 'index'})
    } else {
      return next()
    }
  }
  next()
})

export default router