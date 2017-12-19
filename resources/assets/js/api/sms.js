import axios from 'axios'

export function sendVerifyCode(mobile) {

  const url = '/laravel-sms/verify-code'

  return axios.post(url, {
    mobile,
  },{
    headers: {
      'access-token': mobile
    }
  }).then(res => {
    return Promise.resolve(res.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function sendVoiceVerify(mobile) {

  const url = '/laravel-sms/voice-verify'

  return axios.post(url, {
    mobile,
  },{
    headers: {
      'access-token': mobile
    }
  }).then(res => {
    return Promise.resolve(res.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function delVerifyCode(mobile) {
  const url = '/api/delVerifyCode'

  return axios.post(url, {
    mobile
  }).then(res => {
    return Promise.resolve(res.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}
