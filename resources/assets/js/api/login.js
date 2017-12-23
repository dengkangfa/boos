import axios from 'axios'

export function verifycodeLogin(data) {
  const url = '/api/login/verifycode'

  return axios.post(url, data, { headers: {'access-token': data.mobile} }).then(res => {
    return Promise.resolve(res.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function passwordLogin(data) {
  const url = '/api/login/password'

  return axios.post(url, data).then(res => {
    return Promise.resolve(res.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}