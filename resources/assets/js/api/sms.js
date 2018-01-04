import axios from 'axios'

export function sendVerifyCode(requestData) {
  const url = '/laravel-sms/verify-code'

  return axios.post(url, requestData, {
    headers: {
      'access-token': requestData.mobile
    }
  }).then(res => {
    return Promise.resolve(res.data)
  }).catch(error => {
    return Promise.reject(error.response.data)
  })
}

export function sendVoiceVerify(requestData) {

  const url = '/laravel-sms/voice-verify'

  return axios.post(url, requestData, {
    headers: {
      'access-token': requestData.mobile
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
