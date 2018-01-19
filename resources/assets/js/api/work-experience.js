import axios from 'axios'

export function getWorkExperience() {
  const url = 'api/me/work_experience'

  return axios.get(url).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function createdWorkExperience(requestData) {
  let url = 'api/work_experiences'

  return axios.post(url, requestData).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function updateWorkExperience(requestData) {
  let url = 'api/work_experiences/' + requestData.id

  return axios.put(url, requestData).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}