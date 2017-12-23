import axios from 'axios'
import jwtToken from './../../helpers/jwt'

export default {
  actions: {
    // 登录请求操作
    loginRequest({dispatch}, data) {
      return axios.post(`api/login/${data.driver}`, data.formData, {headers: {
        'access-token': data.driver === 'verifycode' ? data.formData.mobile : ''
      }}).then(response => {
        dispatch('loginSuccess', response.data)
      })
    },
    // 登录成功操作
    loginSuccess({dispatch}, tokenResponse) {
      jwtToken.setToken(tokenResponse.token)
    }
  }
}