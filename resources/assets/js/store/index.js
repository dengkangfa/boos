import Vue from 'vue'
import Vuex from 'vuex'

import Login from './modules/login'
import AuthUser from './modules/auth-user'
import Company from './modules/company'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    Login,
    AuthUser,
    Company
  }
})