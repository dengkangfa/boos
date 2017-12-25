import axios from 'axios'
import * as types from './../mutation-types'

export default {
  state: {
    authenticated: false
  },
  mutations: {
    [types.SET_AUTH_USER](state, paylpad) {
      state.authenticated = true
    },
    [types.UNSET_AUTH_USER](state) {
      state.authenticated = false
    }
  },
  actions: {
    setAuthUser({commit, dispatch}) {
      return axios.get('/api/user').then(response => {
        commit({
          type: types.SET_AUTH_USER,
          user: response.data
        })
      }).catch(() => {
        dispatch('refreshToken')
      })
    },
    unsetAuthUser({commit}) {
      commit({
        type: types.UNSET_AUTH_USER,
      })
    },
    refreshToken({commit, dispatch}) {
      return axios.post('/api/token/refresh').then(response => {
        dispatch('loginSuccess', response.data)
      }).catch(() => {
        dispatch('logoutRequest')
      })
    }
  }
}