import axios from 'axios'

export function getPositionSkill(position) {
  const url = 'api/positionSkill'

  return axios.get(url, {
    params: {
      'positonLv2': position
    }
  }).then(response => {
    return Promise.resolve(response.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}