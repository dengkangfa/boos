import axios from 'axios'

export function createJob(companyId, data) {
  const url = `api/companies/${companyId}/jobs`

  return axios.post(url, data).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}