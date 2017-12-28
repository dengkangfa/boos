require('./bootstrap')

import Vue from 'vue'
import axios from 'axios'
import App from './App.vue'
import router from './router'
import store from './store/index'
import fastclick from 'fastclick'
import jwtToken from './helpers/jwt'

import { Picker } from 'mint-ui'
import 'mint-ui/lib/style.css'
Vue.component('mint-picker', Picker)

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

// // 注册一个全局自定义指令 v-focus
// Vue.directive('focus', {
//   // 当绑定元素插入到 DOM 中。
//   inserted: function (el) {
//     // 聚焦元素
//     el.focus()
//   }
// })
