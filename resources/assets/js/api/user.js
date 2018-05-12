import axios from 'axios'

export function updateField() {

}

export function resetPassword(requestData) {
  const url = 'api/password/reset'

  return axios.post(url, requestData, {
    headers: {
      'access-token': requestData.mobile
    }
  }).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function getAvatar(mobile) {
  const url = 'api/user/avatar'

  return axios.get(url, {
    params: {
      mobile
    }
  }).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}