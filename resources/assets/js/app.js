require('./bootstrap')

import Vue from 'vue'
import axios from 'axios'
import App from './App.vue'
import router from './router'
import store from './store/index'
import fastclick from 'fastclick'
import jwtToken from './helpers/jwt'

import { Picker, Switch } from 'mint-ui'
import 'mint-ui/lib/style.css'
Vue.component('mint-picker', Picker)
Vue.component('mint-switch', Switch)

// 引入vue-amap
import AMap from 'vue-amap'
Vue.use(AMap)

fastclick.attach(document.body)

axios.interceptors.request.use(config => {
  if (jwtToken.getToken()) {
    config.headers['Authorization'] = 'Bearer ' + jwtToken.getToken()
  }
  return config
})

axios.interceptors.response.use(
  response => {
    return response
  },
  error => {
    if ((error.response.statusText === 'Unauthorized') && (error.response.status === 400 || error.response.status === 401)) {
      store.dispatch('refreshToken')
    }
    return Promise.reject(error)
  })

/* eslint-disable no-new */
new Vue({
  el: '#app',
  render: h => h(App),
  router,
  store
})
