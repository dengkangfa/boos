import axios from 'axios'
import * as types from './../mutation-types'

export default {
  state: {
    id: '',
    name: '',
    pic: '',
    abbreviation: '',
    industry_str: '',
    industry_code: '',
    stock: '',
    people: 0,
    detial: '',
    boss_count: 0
  },
  mutations: {
    [types.SET_COMPANY](state, payload) {
      state.id = payload.company.id
      state.name = payload.company.name
      state.pic = payload.company.pic
      state.abbreviation = payload.company.abbreviation
      state.industry_str = payload.company.industry_str
      state.industry_code = payload.company.industry_code
      state.stock = payload.company.stock
      state.people = payload.company.people
      state.detial = payload.company.detial
      state.boss_count = payload.company.boss_count
    }
  },
  actions: {
    setMyCompany({commit}, data) {
      commit({
        type: types.SET_COMPANY,
        company: data
      })
    },
    createCompany({commit}, data) {
      return axios.post('api/companies', data).then(response => {
        commit({
          type: types.SET_COMPANY,
          company: response.data.data
        })
      })
    }
  }
}