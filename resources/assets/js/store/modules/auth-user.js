import axios from 'axios'
import * as types from './../mutation-types'

export default {
  state: {
    authenticated: false,
    roles: [],
    name: '',
    avatar: '',
    email: '',
    gender: '',
    advantage: '',
    pos_name: '',
    company_id: '',
    job_date: '',
    birth_date: '',
    homepages: []
  },
  mutations: {
    [types.SET_AUTH_USER](state, payload) {
      state.authenticated = true
      state.roles = payload.user.roles
      state.avatar = payload.user.avatar
      state.name = payload.user.name
      state.gender = payload.user.gender
      state.advantage = payload.user.advantage
      state.company_id = payload.user.company_id
      state.job_date = payload.user.job_date
      state.birth_date = payload.user.birth_date
      state.homepages = payload.user.homepages
    },
    [types.UNSET_AUTH_USER](state) {
      state.authenticated = false
    },
    [types.UPDATE_USER_ROLE](state, payload) {
      state.roles = payload.roles
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
    },
    [types.SET_USER_HOMEPAGES](state, payload) {
      state.homepages = payload.homepages
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
    setAuthUserWithCompany({commit, dispatch}) {
      return axios.get('/api/user?include=company').then(response => {
        commit({
          type: types.SET_AUTH_USER,
          user: response.data.data
        })
        dispatch('setMyCompany', response.data.data.company.data)
      }).catch(() => {
        dispatch('refreshToken')
      })
    },
    cutRole({commit}, data) {
      return axios.patch('api/user/role', data).then(response => {
        commit({
          type: types.UPDATE_USER_ROLE,
          roles: response.data.roles
        })
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
      return axios.patch('/api/user/avatar', data).then(response => {
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
        commit({
          type: types.SET_USER_ADVANTAGE,
          advantage: response.data.advantage
        })
      })
    },
    updateHomepages({commit}, data) {
      return axios.patch('api/user/homepages', data).then(response => {
        commit({
          type: types.SET_USER_HOMEPAGES,
          homepages: response.data.homepages
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