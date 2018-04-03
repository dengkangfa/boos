import axios from 'axios'
import * as types from './../mutation-types'

export default {
  state: {
    contact_list: [],
    new_message_total: 0,
    new_contact_count: 0
  },
  mutations: {
    [types.SET_CONTACT_LIST](state, payload) {
      state.contact_list = payload.contact_list
      state.new_message_total = payload.new_message_total
      state.new_contact_count = payload.new_contact_count
    },
    [types.NEW_MESSAGE](state, payload) {
      state.contact_list.map(function(value,index){
        if (value.id === payload.user.id) {
          value.new_message_count++
          value.last_message = payload.chatMessage
        }
      })
    }
  },
  actions: {
    contactList({commit}, data) {
      const url = 'api/users/contacts?include=chat,' + data

      return axios.get(url).then(response => {
        commit({
          type: types.SET_CONTACT_LIST,
          contact_list: response.data.data
        })
      }).catch(error => {
        return Promise.reject(error.response.data)
      })
    }
  }
}