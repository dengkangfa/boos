import axios from 'axios'
import {PERPAGE} from './config'

export function createdChat(data) {
  const url = `api/chats`

  return axios.post(url, data).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function contactList() {
  const url = 'api/users/contacts?include=company,chat'

  return axios.get(url).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function messageDetail(uuid) {
  const url = `api/chats/${uuid}?include=contact,job.company,message.user`

  return axios.get(url).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function seedMessage(uuid, data) {
  const url = `api/chats/${uuid}/messages?include=user`

  return axios.post(url, data).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}