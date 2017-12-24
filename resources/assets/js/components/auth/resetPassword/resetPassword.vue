<template>
    <transition name="slide">
        <div class="reset-wrapper">
            <div class="sider">
                <img src="https://img2.bosszhipin.com/boss/avatar/avatar_15.png" class="avatar">
                <div class="left-triangle" ref="left_triangle" :style="{borderRight: triangleWidth + 'px solid transparent'}"></div>
                <div class="right-triangle" ref="right_triangle"  :style="{borderLeft: triangleWidth + 'px solid transparent'}"></div>
            </div>
            <div class="reset">
                <form action="" class="info-reset">
                    <ul>
                        <li><b>{{lable}}<i class="icon-down"></i></b><input type="text" v-model="userules.mobile" pattern="[0-9]*" placeholder="请输入您的手机号" @blur="closeIconShow = false" @focus="closeIconShow = true"><i class="icon-circle-with-cross" @click.prevent="close" v-show="closeIconShow && userules.mobile"></i></li>
                        <li><i class="icon-lock"></i><input :type="passwordShow ? 'text' : 'password'" v-model="userules.password" placeholder="新密码"><i class="password-show-icon" :class="passwordShow ? 'icon-eye-view' : 'icon-eye-blocked'" @click="passwordShowLogger"></i></li>
                        <li><i class="icon-smartphone"></i><input type="number" v-model="userules.verifyCode" pattern="[0-9]*" oninput="if(value.length>6)value=value.slice(0,6)" placeholder="验证码" ref="verifyCodeInput"><timer-btn @run="sendVerifyCode" @end="end" theme="vue" ref="timer"></timer-btn></li>
                        <li class="voice-verify"><p>长时间收不到验证码，可尝试<br><a @click.prevent="sendVoiceVerify">语音接听验证码</a></p></li>
                        <li><input type="submit" value="重置密码" class="reset-submit" @click.prevent="submit"></li>
                    </ul>
                </form>
            </div>
            <div class="back" @click="back">
                <i class="icon-left"></i>
            </div>
            <message-box :message="message" confirmButtonText="好" @confirm="confirm" ref="message"></message-box>
            <fading-circle :text="loadingText" v-show="spinning"></fading-circle>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import {checkMobileRegex, verifycodeMixin} from 'Mixin/mixin.js'
  import timerBtn from 'Base/timer-btn/TimerBtn.vue'
  import messageBox from 'Base/message/message-box.vue'
  import fadingCircle from 'Base/spinner/fading-circle.vue'
  import {resetPassword} from 'Api/user.js'
  import {ERR_OK, ERR_UNPROCESSABLE_ENTITY} from 'Api/config.js'

  export default {
    mixins: [checkMobileRegex, verifycodeMixin],
    data() {
      return {
        passwordShow: false,
        lable: '+86',
        message: '',
        loadingText: '',
        spinning: false,
        mobileRule: 'mobile_exists',
        route: '',
        triangleWidth: ''
      }
    },
    created() {
      this.userules.mobile = sessionStorage.getItem('mobile')
    },
    mounted() {
      this.triangleWidth = document.body.clientWidth / 2
    },
    methods: {
      passwordShowLogger() {
        this.passwordShow = !this.passwordShow
      },
      end() {
        this.disabledVoiceVerify = false
      },
      back() {
        this.$router.back()
      },
      checkPassword() {
        if (!this.userules.password) {
          this.message = '密码不能为空'
          this.$refs.message.show()
          return false
        }
        return true
      },
      submit() {
        if (this.checkMobileRegex() && this.checkPassword() && this.checkVerifyCode()) {
          this.loadingText = '正在重置密码'
          this.spinning = true
          resetPassword(this.userules).then(response => {
            this.spinning = false
            if (response.success && response.code === ERR_OK) {
              this.message = response.message
              this.$refs.message.show()
              this.route = '/login'
            }
          }).catch(error => {
            this.spinning = false
            if (error.success === false && error.code === ERR_UNPROCESSABLE_ENTITY) {
              this.message = error.message
              this.$refs.message.show()
            } else {
              // 不可预知的错误
              this.message = '服务繁忙请稍后再试'
              this.$refs.message.show()
            }
          })
        }
      },
      confirm() {
        this.route ? this.$router.push(this.route) : ''
      }
    },
    components: {
      timerBtn,
      messageBox,
      fadingCircle
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .reset-wrapper
        @include allCover()
        z-index: 200
        background-color: #eeeeee
        .sider
            position: relative
            display: flex
            justify-content: center
            align-items: center
            width: 100%
            height: 27%
            background-color: #A46E92
            .avatar
                box-sizing: border-box
                border: 5px solid rgba(170, 170, 170, 0.5)
                border-radius: 100%
                width: 75px
                height: 75px
                margin-top: 15px
            .left-triangle
                position: absolute
                left: 0
                bottom: 0
                width: 0
                height: 0
                border-bottom: 25px solid #eee
                border-right: 160px solid transparent
            .right-triangle
                position: absolute
                right: 0
                bottom: 0
                width: 0
                height: 0
                border-bottom: 25px solid #eee
                border-left: 160px solid transparent
        .reset
            width: 85%
            margin: 1.2rem auto 0px
            .info-reset
                li
                    position: relative
                    height: 1.2rem
                    line-height: 1.2rem
                    margin-top: 15px
                    input[type=text],input[type=password],input[type=number]
                        width: 100%
                        border-radius: 0.1rem
                        padding: 0.4rem 1rem 0.4rem 2rem
                    input.reset-submit
                        width: 100%
                        height: 100%
                        cursor: pointer
                        color: #FFFFFF
                        text-align: center
                        font-weight: bold
                        background-color: $color-theme
                        border: 1px solid $color-theme
                        border-radius: 0.1rem
                        white-space: nowrap
                        -webkit-appearance: none
                        &:focus
                         border-color: $color-theme
                li:nth-child(1):before,
                li:nth-child(2):before,
                li:nth-child(3):before
                    display: table
                    content: ""
                    position: absolute
                    background: $color-text-d
                    width: 1px
                    height: 100%
                    top: 50%
                    left: 1.6rem
                    transform: scaleY(0.3) translateY(-50%)
                    transform-origin: 0 0
                li:nth-child(1) b
                    @include ct() // 垂直局中
                    display: block
                    color: $color-theme
                    font-weight: 100
                    padding-left: .4rem
                li:nth-child(1) .icon-circle-with-cross
                    @include ct() // 垂直局中
                    font-size: .45rem
                    right: .1rem
                    color: $color-text-d
                    padding: 8px
                li:nth-child(2)
                    .icon-lock
                        @include ct() // 垂直局中
                        display: block
                        color: $color-text-d
                        font-weight: 100
                        left: .4rem
                        padding-left: .1rem
                        font-size: .5rem
                    .password-show-icon
                        @include ct()
                        font-size: .45rem
                        right: .1rem
                        color: $color-text-d
                        padding: 8px
                li:nth-child(3)
                    .icon-smartphone
                        @include ct() // 垂直局中
                        display: block
                        color: $color-text-d
                        font-weight: 100
                        left: .4rem
                        padding-left: .1rem
                        font-size: .5rem
                    button
                        @include ct() // 垂直局中
                        right: .4rem
                        z-index: 10
                .voice-verify
                    text-align: center
                    font-size: 0.4rem
                    line-height: 0.6rem
                    color: $color-text-d
                    a
                        color: $color-theme
        .back
            position: absolute
            top: 14px
            left: 6px
            z-index: 200
            .icon-left
                display: block
                padding: 10px
                font-size: 0.5rem
                color: #fff

    .side-enter-active, .side-leave-active
        transition: all 0.3s
    .side-enter, .side-leave-to
        transform: translate3d(100%, 0, 0)
</style>