import * as types from './../mutation-types'

export default {
  state: {
    name: '',
    avatar: '',
    pos_name: '',
    company_name: ''
  },
  mutations: {
    [types.SET_CONTACT](state, payload) {
      console.log(payload)
      state.name = payload.data.name
      state.avatar = payload.data.avatar
      state.pos_name = payload.data.pos_name
      state.company_name = payload.data.company_name
    }
  }
}