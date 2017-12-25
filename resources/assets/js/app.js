require('./bootstrap')

import Vue from 'vue'
import axios from 'axios'
import App from './App.vue'
import router from './router'
import store from './store/index'
import fastclick from 'fastclick'
import jwtToken from './helpers/jwt'

fastclick.attach(document.body)

axios.interceptors.request.use(config => {
  if (jwtToken.getToken()) {
    config.headers['Authorization'] = 'Bearer ' + jwtToken.getToken()
  }
  return config
}, error => {
  return Promise.reject(error)
})

/* eslint-disable no-new */
new Vue({
  el: '#app',
  render: h => h(App),
  router,
  store
})
