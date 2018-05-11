import {sendVerifyCode, sendVoiceVerify} from 'Api/sms.js'
import avatar from './avatarMixin.js'

export const loginFooterMixin = {
  mounted() {
    var clientHeight = window.innerHeight
    window.addEventListener('resize', () => {
      var nowClientHeight = window.innerHeight
      if (clientHeight > nowClientHeight) {
        document.querySelectorAll('.login-footer')[0].style.position = 'static'
        document.querySelectorAll('.login-footer')[1].style.position = 'static'
      } else {
        document.querySelectorAll('.login-footer')[0].style.position = 'fixed'
        document.querySelectorAll('.login-footer')[1].style.position = 'fixed'
      }
    })
  }
}

// 手机正则表达式
const phoneRegex = /^1(3[\d]|4[57]|5[0-35-9]|7[01678]|8[\d])[\d]{8}$/

export const checkMobileRegex = {
  data() {
    return {
      userules: {
        mobile: ''
      },
      closeIconShow: false
    }
  },
  methods: {
    checkMobileRegex() {
      if (!this.userules.mobile) {
        this.message = '请填写手机号'
      } else if (!phoneRegex.test(this.userules.mobile)) {
        this.message = '输入号码与归属地不匹配'
      } else {
        return true
      }
      this.$refs.message.show()
      return false
    },
    close() {
      this.userules.mobile = ''
    }
  }
}

export const verifycodeMixin = {
  data() {
    return {
      // 用户信息
      userules: {
        verifyCode: ''
      },
      disabledVoiceVerify: false, // 语音验证码服务是否可用
      captchaImageContent: null,
      captcha_key: null,
      showCaptcha: false
    }
  },
  methods: {
    checkVerifyCode() {
      if (!this.userules.verifyCode) {
        this.message = '验证码不能为空'
        this.$refs.message.show()
        return false
      }
      return true
    },
    checkCaptcha() {
      if (this.showCaptcha && this.captcha_code.length !== 4) {
        this.message = '请输入正确的图片验证码'
        this.$refs.message.show()
        return false
      }
      return true
    },
    // 发送短信验证
    sendVerifyCode() {
      if (this.checkMobileRegex() && this.checkCaptcha()) {
        this.disabledVoiceVerify = true // 禁止获取语音验证码发送服务
        this.spinnerText = '正在发送中'
        this.spinning = true // 显示正在发送的loading
        let formData = new FormData()
        formData.append('mobile', this.userules.mobile)
        formData.append('mobile_rule', this.mobileRule)
        if (this.showCaptcha) {
          formData.append('captcha_key', this.captcha_key)
          formData.append('captcha_code', this.captcha_code)
        }
        sendVerifyCode(formData).then(response => {
          this.spinning = false // 隐藏正在发送的loading
          // 数据格式有误
          if (response.success === false) {
            if (response.code === 10005) {
              this.captchaImageContent = response.captcha_image_content
              this.captcha_key = response.captcha_key
              this.showCaptcha = true
              return
            } else if (response.code === 10006) {
              this.captchaImageContent = response.captcha_image_content
              this.captcha_key = response.captcha_key
              this.captcha_code = ''
              this.showCaptcha = true;
              this.message = '请输入正确的图片验证码'
              this.$refs.message.show()
              return
            } else {
              this.disabledVoiceVerify = false
              this.message = response.message
              this.$refs.message.show()
              return
            }
          }
          this.$refs.timer.start() // 可重复获取验证码按钮进入倒计时
          this.userules.verifyCode = ''
          this.$refs.verifyCodeInput.focus() // 让验证码输入框获取焦点(提高用户体验)
        }).catch((error) => {
          this.spinning = false // 隐藏正在发送的loading
          this.disabledVoiceVerify = false
          // 不可预知的错误
          this.message = error.message ? error.message : '服务繁忙请稍后再试~'
          this.$refs.message.show()
        })
      }
    },
    // 发送语音验证
    sendVoiceVerify() {
      if (!this.disabledVoiceVerify && this.checkMobileRegex()) {
        this.disabledVoiceVerify = true // 禁止重复获取语音验证码发送服务
        this.loadingText = '正在发送中'
        this.spinning = true // 显示正在发送的loading
        sendVoiceVerify({'mobile': this.userules.mobile, 'mobile_rule': this.mobileRule}).then(response => {
          this.spinning = false  // 隐藏正在发送的loading
          // 数据格式有误
          if (response.success === false) {
            this.disabledVoiceVerify = false
            this.message = response.message
            this.$refs.message.show()
            return
          }
          this.$refs.timer.start() // 可重复获取验证码按钮进入倒计时
          this.userules.verifyCode = '' // 清空验证码输入框内容
          this.$refs.verifyCodeInput.focus() // 让验证码输入框获取焦点(提高用户体验)
        }).catch(() => {
          this.spinning = false // 隐藏正在发送的loading
          this.disabledVoiceVerify = false // 禁止重复获取语音验证码发送服务
          // 不可预知的错误
          this.message = '服务繁忙请稍后再试~'
          this.$refs.message.show()
        })
      }
    },
    end() {
      this.disabledVoiceVerify = false
    }
  }
}

export const avatarMixin = avatar

// export const avatarMixin = require('./avatarMixin')

