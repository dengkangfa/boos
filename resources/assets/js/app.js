require('./bootstrap')

window.Pusher = require('pusher-js')

import Vue from 'vue'
import axios from 'axios'
import App from './App.vue'
import router from './router'
import store from './store/index'
import fastclick from 'fastclick'
import jwtToken from './helpers/jwt'
import Echo from 'laravel-echo'

import { Picker, Switch, Spinner } from 'mint-ui'
import 'mint-ui/lib/style.css'
Vue.component('mint-picker', Picker)
Vue.component('mint-switch', Switch)
Vue.component('mint-spinner', Spinner)

// 引入vue-amap
import VMap from 'vue-amap'
Vue.use(VMap)

// 初始化vue-amap
VMap.initAMapApiLoader({
  // 高德的key
  key: '4929bb0beb0dd7cbe45c4b1f84dc1930',
  // 插件集合
  plugin: ['AMap.Geolocation', 'AMap.Geocoder', 'AMap.Autocomplete'],

  v: '1.4.4'
})

fastclick.attach(document.body)

window.echo = new Echo({
  broadcaster: 'pusher',
  key: 'a22b95e60164be5a4e00',
  cluster: 'ap1',
  encrypted: true
})

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