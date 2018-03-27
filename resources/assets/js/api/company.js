import axios from 'axios'
import {PERPAGE} from './config'

export function searchByName(wk) {
  const url = 'api/companies/search/name/like?wk=' + wk

  return axios.get(url).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function search(data) {
  let url = 'api/companies/search?' + param(data) + '&include=user:limit(4|0)'

  return axios.get(url).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function createCompany(data) {
  let url = 'api/companies'

  return axios.post(url, data).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function companyList(perpage = PERPAGE, page = 1) {
  let url = `api/companies?perpage=${perpage}&page=${page}`

  return axios.get(url).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

function param(data) {
  let url = ''
  for (var k in data) {
    let value = data[k] !== undefined ? data[k] : ''
    url += `&${k}=${encodeURIComponent(value)}`
  }
  return url ? url.substring(1) : ''
}