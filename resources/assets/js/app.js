require('./bootstrap');

window.Vue = require('vue');

import App from './App.vue'
import router from './router'

/* eslint-disable no-new */
new Vue({
  el: '#app',
  render: h => h(App),
  router
});
