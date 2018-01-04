import axios from 'axios'
import * as types from './../mutation-types'

export default {
  state: {
    authenticated: false,
    name: '',
    avatar: '',
    gender: '',
    advantage: '',
    job_date: '',
    birth_date: ''
  },
  mutations: {
    [types.SET_AUTH_USER](state, payload) {
      state.authenticated = true
      state.avatar = payload.user.avatar
      state.name = payload.user.name
      state.gender = payload.user.gender
      state.advantage = payload.user.advantage
      state.job_date = payload.user.job_date
      state.birth_date = payload.user.birth_date
    },
    [types.UNSET_AUTH_USER](state) {
      state.authenticated = false
    },
    [types.SET_USER_AVATAR](state, payload) {
      state.avatar = payload.avatar
    },
    [types.UPDATE_USER_DEFAULT_AVATAR](state, payload) {
      state.avatar = payload.avatar
    },
    [types.SET_USER_PROFILE](state, payload) {
      state.avatar = payload.user.avatar
      state.name = payload.user.name
      state.gender = payload.user.gender
      state.job_date = payload.user.job_date
      state.birth_date = payload.user.birth_date
    },
    [types.SET_USER_ADVANTAGE](state, payload) {
      state.advantage = payload.advantage
    }
  },
  actions: {
    setAuthUser({commit, dispatch}) {
      return axios.get('/api/user').then(response => {
        commit({
          type: types.SET_AUTH_USER,
          user: response.data.data
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
    updateDefaultAvatar({commit}, data) {
      return axios.put('/api/user/avatar', data).then(response => {
        commit({
          type: types.UPDATE_USER_DEFAULT_AVATAR,
          avatar: response.data.url
        })
      })
    },
    updateProflie({commit}, data) {
      return axios.patch('/api/user/profile', data).then(response => {
        commit({
          type: types.SET_USER_PROFILE,
          user: response.data.data
        })
      }).catch(error => {
        return Promise.reject(error.response.data)
      })
    },
    updateAdvantage({commit}, data) {
      return axios.patch('api/user/advantage', data).then(response => {
        console.log(response)
        commit({
          type: types.SET_USER_ADVANTAGE,
          advantage: response.data.advantage
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