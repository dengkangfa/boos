// 手机正则表达式
const phoneRegex = /^1(3[\d]|4[57]|5[0-35-9]|7[01678]|8[\d])[\d]{8}$/

export const loginMixin = {
  data() {
    return {
      userules: {
        mobile: ''
      },
      closeIconShow: false
    }
  },
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
  },
  methods: {
    close() {
      this.userules.mobile = ''
    },
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
    }
  }
}