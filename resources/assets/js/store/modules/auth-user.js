import axios from 'axios'
import * as types from './../mutation-types'

export default {
  state: {
    authenticated: false,
    avatar: ''
  },
  mutations: {
    [types.SET_AUTH_USER](state, payload) {
      state.authenticated = true
    },
    [types.UNSET_AUTH_USER](state) {
      state.authenticated = false
    },
    [types.SET_USER_AVATAR](state, payload) {
      state.avatar = payload.avatar
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
    uploadAvatar({commit}, data) {
      return axios.post('/api/user/crop/avatar', data).then(response => {
        commit({
          type: types.SET_USER_AVATAR,
          avatar: response.data.url
        })
      })
    },
    unsetAuthUser({commit}) {
      commit({
        type: types.UNSET_AUTH_USER
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