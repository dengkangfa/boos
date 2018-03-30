import * as types from './../mutation-types'

export default {
  state: {
    contact_list: [],
    new_message_count: 0,
    new_contact_count: 0
  },
  mutations: {
    [types.SET_CONTACT_LIST](state, payload) {
      state.contact_list = payload.contact_list
      state.new_message_count = payload.new_message_count
      state.new_contact_count = payload.new_contact_count
    }
  }
}