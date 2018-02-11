import axios from 'axios'

export function getIndustry() {
  const url = 'api/industry'

  return axios.get(url).then((response) => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.resolve(error.response.data)
  })
}