import axios from 'axios'

export function getCurrentUserAllProjectExperience() {
  const url = 'api/me/project_experiences'

  return axios.get(url).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function createdProjectExperience(requestData) {
  const url = 'api/project_experiences'

  return axios.post(url, requestData).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function updateProjectExperience(requestData) {
  let url = 'api/project_experiences/' + requestData.id

  return axios.put(url, requestData).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function deleteProjectExperience(id) {
  let url = 'api/project_experiences/' + id

  return axios.delete(url).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}