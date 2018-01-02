<template>
    <transition name="slide">
        <div class="password-login-wrapper">
            <div class="back" @click="back">
                <i class="icon-left"></i>
            </div>
            <div class="password-login">
                <form action="">
                    <h3><img :src="avatar ? avatar : 'images/default.png'" class="avatar"></h3>
                    <ul class="info-login">
                        <li><b>{{lable}}<i class="icon-down"></i></b><input type="text" v-model="userules.mobile" pattern="[0-9]*" placeholder="请输入您的手机号" ref="mobile" @blur="mobileInputBlur" @input="mobileInputEvent" @focus="closeIconShow = true"><i class="icon-circle-with-cross" @click.prevent="close" v-show="closeIconShow && userules.mobile"></i></li>
                        <li><i class="icon-lock"></i><input :type="passwordShow ? 'text' : 'password'" v-model="userules.password" placeholder="6-25位字母、数字或下划线"><i class="password-show-icon" :class="passwordShow ? 'icon-eye-view' : 'icon-eye-blocked'" @click="passwordShowLogger"></i></li>
                        <li><p><router-link to="/login/password/reset" class="reset">忘记密码</router-link></p></li>
                        <li><input @click.prevent="submit" type="submit" value="登录"></li>
                    </ul>
                </form>
            </div>
            <div class="login-footer flex_parent clearHistory">
                <div class="or">
                    <div class="line"></div>
                    <div class="text">OR</div>
                    <div class="line"></div>
                </div>
                <router-link to="" class="protocol"><span>用户协议及隐私策略</span></router-link>
                <router-link to=""><span>登录遇到问题</span></router-link>
            </div>
            <message-box :message="message" cancelButtonText="好" ref="message"></message-box>
            <fading-circle :text="loadingText" v-show="spinning"></fading-circle>
            <router-view></router-view>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import messageBox from 'Base/message/message-box.vue'
  import fadingCircle from 'Base/spinner/fading-circle.vue'
  import {loginFooterMixin, checkMobileRegex} from 'Mixin/mixin.js'
  import {getAvatar} from 'Api/user'
  import avatar from 'Helpers/avatar'

  // 手机正则表达式
  const phoneRegex = /^1(3[\d]|4[57]|5[0-35-9]|7[01678]|8[\d])[\d]{8}$/

  export default {
    mixins: [loginFooterMixin, checkMobileRegex],
    data() {
      return {
        // 用户信息
        userules: {
          password: ''
        },
        passwordShow: false,
        message: '',
        lable: '+86',
        loadingText: '',
        spinning: false,
        avatar: ''
      }
    },
    created() {
      if (sessionStorage.getItem('mobile')) {
        this.userules.mobile = sessionStorage.getItem('mobile')
        this.setAvatar()
      }
    },
    methods: {
      back() {
        this.$router.back()
      },
      close() {
        this.userules.mobile = ''
      },
      passwordShowLogger() {
        this.passwordShow = !this.passwordShow
      },
      mobileInputBlur() {
        this.closeIconShow = false
        if (phoneRegex.test(this.userules.mobile)) {
          this.setAvatar()
        }
      },
      setAvatar() {
        this.avatar = avatar.getAvatar(this.userules.mobile)
        if (!this.avatar) {
          getAvatar(this.userules.mobile).then(response => {
            this.avatar = response.avatar
            if (this.avatar) {
              avatar.setAvatar(this.userules.mobile, response.avatar)
            }
          })
        }
      },
      mobileInputEvent() {
        this.avatar = null
      },
      submit() {
        // 验证手机号码格式
        if (!this.checkMobileRegex()) {
          return
        } else if (this.checkMobileRegex() && !this.userules.password) {
          this.message = '密码不能为空'
          this.$refs.message.show()
          return
        }
        this.loadingText = '正在登录中'
        this.spinning = true
        let data = {
          'driver': 'password', // 登录的方式
          'formData': this.userules
        }
        this.$store.dispatch('loginRequest', data).then(res => {
          this.spinning = false
          this.$router.push({'name': 'index'})
        }).catch(error => {
          this.spinning = false
          if (error.response.data.success === false) {
            this.message = error.response.data.message
            this.$refs.message.show()
          } else {
            // 不可预知的错误
            this.message = '服务繁忙请稍后再试~'
            this.$refs.message.show()
          }
        })
      }
    },
    components: {
      messageBox,
      fadingCircle
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"
    @import "../../../../sass/login-footer"

    .password-login-wrapper
        @include allCover()
        z-index: 100
        display: flex
        justify-content: center
        flex-wrap: wrap
        background: linear-gradient(to bottom, #A46E92 0%, #27455D 100%)
        .back
            position: absolute
            top: 14px
            left: 6px
            z-index: 100
            .icon-left
                display: block
                padding: 10px
                font-size: 0.5rem
                color: #fff
        .password-login
            width: 90%
            height: 10rem
            margin-top: 1.5rem
            box-sizing: border-box
            & h3
                height: 2.2rem
                text-align: center
                margin-bottom: 1.2rem
            .avatar
                border: 1px solid #fff
                border-radius: 100%
                width: 75px
                height: 75px
            .info-login
                li:nth-child(1),li:nth-child(2)
                    margin-top: .6rem
                li:nth-child(3)
                    text-align: center
                    color: #fff
                li:nth-child(4)
                    margin-top: 1rem
                li
                    height: 1.2rem
                    line-height: 1.2rem
                    position: relative
                input
                    @include sc(.35rem,#fff)
                    transition: all .3s
                    background: rgba(154,165,181,.3)
                input[type=text],input[type=password]
                    width: 100%
                    padding: 0.4rem 1rem 0.4rem 2rem
                    border-radius: .8rem
                    &::placeholder
                        color: #fff
                & input[type=text]:focus,input[type=password]:focus
                    border-color: $color-theme
                & input[type=submit],input[type=button]
                    width: 100%
                    height: 100%
                    cursor: pointer
                    background-color: $color-theme
                    border: 1px solid $color-theme
                    border-radius: .8rem
                    -webkit-appearance: none
                    white-space: nowrap
                & input[type=submit]:hover,input[type=button]:hover
                    background-color: $color-theme
                li:nth-child(1) b
                    @include ct() // 垂直局中
                    display: block
                    color: #fff
                    font-weight: 100
                    padding-left: .4rem
                li:nth-child(1) .icon-circle-with-cross
                    @include ct() // 垂直局中
                    font-size: .45rem
                    right: .1rem
                    color: #605e5e
                    padding: 8px
                li:nth-child(1):before,
                li:nth-child(2):before
                    display: table
                    content: ""
                    position: absolute
                    background: #fff
                    width: 1px
                    height: 100%
                    top: 50%
                    left: 1.6rem
                    transform: scaleY(0.3) translateY(-50%)
                    transform-origin: 0 0
                li:nth-child(2)
                    .icon-lock
                        @include ct()
                        display: block
                        color: #fff
                        font-weight: 100
                        left: .4rem
                        padding-left: .1rem
                        font-size: .5rem
                    i.password-show-icon
                        @include ct()
                        font-size: .45rem
                        right: .1rem
                        color: #fff
                        padding: 8px
                li:nth-child(3)
                    .reset
                        text-decoration: underline
                        color: #42b983
                        padding: 0.3rem
    .slide-enter-active, .slide-leave-active
        transition: all 0.3s

    .slide-enter, .slide-leave-to
        transform: translate3d(100%, 0, 0)

    @media screen and (max-height: 505px)
        .password-login-wrapper .password-login
            margin-top: 1rem
    @media screen and (max-height: 480px)
        .password-login-wrapper .password-login
            margin-top: .5rem
            .info-login li:nth-child(4)
                margin-top: .5rem
</style>