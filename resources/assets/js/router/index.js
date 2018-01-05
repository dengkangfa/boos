import Vue from 'vue'
import VueRouter from 'vue-router'
import Store from '../store/index'
import jwtToken from '../helpers/jwt'

import verifycodeLogin from '../components/auth/verifycodeLogin/verifycode-login'
import passwordLogin from '../components/auth/passwordLogin/password-login.vue'
import resetPassword from '../components/auth/resetPassword/resetPassword.vue'
import identitySelect from '../components/info/identity/identity-select.vue'
import basicInfo from '../components/info/job/basic-info.vue'
import educationExperience from '../components/info/job/education-experience.vue'
import Advantage from '../components/info/job/advantage.vue'
import workExperience from '../components/info/job/work-experience.vue'

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
        meta: {requiresAuth: true, autoFrom: ['select-identity', 'job-education', 'job-work-experience']},
        children: [
          {
            path: '/job/education',
            name: 'job-education',
            component: educationExperience,
            meta: {requiresAuth: true, autoFrom: ['job-basic-info', 'job-advantage']},
            children: [
              {
                path: '/job/advantage',
                name: 'job-advantage',
                component: Advantage,
                meta: {requiresAuth: true, autoFrom: ['job-education']}
              }
            ]
          },
          {
            path: '/job/work-experience',
            name: 'job-work-experience',
            component: workExperience,
            meta: {requiresAuth: true, autoFrom: ['job-basic-info']}
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
  // 路由来源限制
  if (to.meta.autoFrom) {
    if (to.meta.autoFrom.indexOf(from.name) === -1) {
      return next({'name': 'select-identity'})
    }
  }
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