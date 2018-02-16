import axios from 'axios'

export function getCurrentUserFirstEducationExperience() {
  const url = 'api/me/education_info'

  return axios.get(url).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function getCurrentUserAllEducationExperience() {
  const url = 'api/me/education_infos'

  return axios.get(url).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function createdEducationExperience(requestData) {
  const url = 'api/education_infos'

  return axios.post(url, requestData).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function updateEducationExperience(requestData) {
  const url = 'api/education_infos/' + requestData.id

  return axios.put(url, requestData).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}