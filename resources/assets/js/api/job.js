import axios from 'axios'
import {JOB_PREPAGE} from './config'

export function jobList(perpage = JOB_PREPAGE, page = 1) {
  const url = `api/jobs?include=user,company&perpage=${perpage}&page=${page}`

  return axios.get(url).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function job(id) {
  const url = `api/jobs/${id}?include=user,company`

  return axios.get(url).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function createJob(companyId, data) {
  const url = `api/companies/${companyId}/jobs`

  return axios.post(url, data).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}