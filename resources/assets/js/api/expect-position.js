import axios from 'axios'

export function createdExpectPosition(requestData) {
  let url = 'api/expect_positions'

  return axios.post(url, requestData).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}