import axios from 'axios'

export function createdExpectPosition(requestData) {
  const url = 'api/expect_positions'

  return axios.post(url, requestData).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function updateExpectPosition(requestData) {
  const url = 'api/expect_positions/' + requestData.id

  return axios.put(url, requestData).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}