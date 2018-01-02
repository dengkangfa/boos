import axios from 'axios'

export function getEducationExperience() {
  const url = 'api/me/education_infos'

  return axios.get(url).then(response => {
    console.log(response)
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

export function updateEducationExperience(responseData) {
  const url = 'api/education_infos/' + responseData.id

  return axios.put(url, responseData).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}