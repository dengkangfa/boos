import axios from 'axios'

export function createdExpectPosition(requestData) {
  const url = 'api/expect_positions'

  return axios.post(url, requestData).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function whetherToRepeat(requestData) {
  const url = 'api/me/expect_positions/whether_to_repeat'

  return axios.get(url, {
    params: {
      id: requestData.id,
      position_name: requestData.position_name,
      location_name: requestData.location_name
    }
  }).then(response => {
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

export function deleteExpectPosition(id) {
  const url = 'api/expect_positions/' + id

  return axios.delete(url).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}