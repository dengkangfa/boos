<template>
    <div class="login-bg">
      <div id="login">
        <form action="">
          <h3>BOSS直聘<span></span></h3>
          <ul class="info-login">
            <li><b>{{lable}}<i class="icon-down"></i></b><input type="text" v-model="userules.mobile" pattern="[0-9]*" placeholder="请输入您的手机号" ref="mobile" @blur="closeIconShow = false" @focus="closeIconShow = true"><i class="icon-circle-with-cross" @click.prevent="close" v-show="closeIconShow && userules.mobile"></i></li>
            <li><i class="icon-smartphone"></i><input type="number" v-model="userules.verifyCode" pattern="[0-9]*" oninput="if(value.length>6)value=value.slice(0,6)" placeholder="验证码" ref="verifyCodeInput"><timer-btn @run="sendVerifyCode" @end="end" ref="timer"></timer-btn></li>
            <li><p>长时间收不到验证码，可尝试 <a style="text-decoration: underline;color: #42b983" @click.prevent="sendVoiceVerify">语音接听验证码</a></p></li>
            <li><input @click.prevent="submit" type="submit" value="进入"></li>
          </ul>
        </form>
      </div>
      <!--底部-->
      <div class="login-footer flex_parent clearHistory">
        <div class="or">
          <div class="line"></div>
          <div class="text">OR</div>
          <div class="line"></div>
        </div>
        <router-link to="" class="protocol"><span>用户协议及隐私策略</span></router-link>
        <router-link to="/login/password" @click.native="selectPasswordLogin"><span>密码登录</span></router-link>
      </div>
      <message :message="message" ref="message"></message>
      <message-box :message="message" confirmButtonText="好" ref="messageBox"></message-box>
      <fading-circle :text="loadingText" v-show="spinning"></fading-circle>
      <router-view></router-view>
    </div>
</template>

<script type="text/ecmascript-6">
  import message from 'Base/message/message.vue'
  import fadingCircle from 'Base/spinner/fading-circle.vue'
  import timerBtn from 'Base/timer-btn/TimerBtn.vue'
  import messageBox from 'Base/message/message-box.vue'
  import {ERR_OK, ERR_REGISTER_CODE, ERR_UNPROCESSABLE_ENTITY} from 'Api/config.js'
  import {loginFooterMixin, checkMobileRegex, verifycodeMixin} from 'Mixin/mixin.js'

  export default {
    mixins: [loginFooterMixin, checkMobileRegex, verifycodeMixin],
    data () {
      return {
        spinning: false, // 是否显示loading
        message: '', // 提示消息
        lable: '+86',
        loadingText: ''
      }
    },
    mounted() {
      if (sessionStorage.getItem('logout-message')) {
        this.message = sessionStorage.getItem('logout-message')
        this.$refs.message.show()
        sessionStorage.removeItem('logout-message')
      }
    },
    methods: {
      submit() {
        if (this.checkMobileRegex() && this.checkVerifyCode()) {
          this.loadingText = '正在登录中'
          this.spinning = true
          let data = {
            'driver': 'verifycode',
            'formData': this.userules
          }
          this.$store.dispatch('loginRequest', data).then(res => {
            this.spinning = false
            if (res.code === ERR_OK) {
              this.$router.push({'name': 'index'})
            } else if (res.code === ERR_REGISTER_CODE) {
              // 该用户为新用户注册
              this.$router.push({'name': 'select-identity'})
            }
          }).catch(error => {
            let response = error.response.data
            this.spinning = false
            if (response.success === false && response.code === ERR_UNPROCESSABLE_ENTITY) {
              this.message = response.message
              if (response.message === '验证码不正确') {
                this.$refs.messageBox.show()
              } else {
                this.$refs.message.show()
              }
            } else {
              // 不可预知的错误
              this.message = '服务繁忙请稍后再试'
              this.$refs.messageBox.show()
            }
          })
        }
      },
      selectPasswordLogin() {
        sessionStorage.setItem('mobile', this.userules.mobile)
      }
    },
    components: {
      message,
      fadingCircle,
      timerBtn,
      messageBox
    }
  }

</script>

<style lang="sass" rel="stylesheet/sass" scoped>
  @import "../../../../sass/variables"
  @import "../../../../sass/mixin"
  @import "../../../../sass/login-footer"

  .login-bg
    position: fixed
    display: flex
    justify-content: center
    flex-wrap: wrap
    width: 100%
    height: 100%
    overflow: auto
    background: linear-gradient(to bottom,  #A46E92 0%,#27455D 100%)

  #login
    width: 90%
    height: 10rem
    margin-top: 1.5rem
    box-sizing: border-box
    & h3
      height: 2.2rem
      font-weight: bold
      text-align: center
      margin-bottom: 1.2rem
      @include sc(1.2rem,#fff)
      & span
        background: url('./Introduction.png') no-repeat
        background-size: 4rem
        display: block
        height: 0.6rem
        margin: auto
        width: 150px
  .info-login li:nth-child(1),
  .info-login li:nth-child(2)
    margin-top: .6rem
  .info-login li:nth-child(3)
    text-align: center
    color: #fff
  .info-login li:nth-child(4)
    margin-top: 1rem
  .info-login li
    height: 1.2rem
    line-height: 1.2rem
    position: relative
    & label
      @include sc(.3rem,#999)
    & input
      @include sc(.35rem,#fff)
      transition: all .3s
      background: rgba(154,165,181,.3)
    & input[type=text],input[type=number],input[type=password]
      width: 100%
      padding: 0.4rem 1rem 0.4rem 2rem
      border-radius: .8rem
      &::placeholder
        color: #fff
    & input[type=text]:focus,input[type=number]:focus
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
  .info-login li:nth-child(1) b
    @include ct() // 垂直局中
    display: block
    color: #fff
    font-weight: 100
    /*left: .4rem*/
    padding-left: .4rem
  .info-login li:nth-child(1) .icon-circle-with-cross
    @include ct() // 垂直局中
    font-size: .45rem
    right: .1rem
    color: #605e5e
    padding: 8px
  .info-login li:nth-child(1):before,
  .info-login li:nth-child(2):before
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
  .info-login li:nth-child(2) .icon-smartphone
    @include ct() // 垂直局中
    display: block
    color: $color-text
    font-weight: 100
    left: .4rem
    padding-left: .1rem
    font-size: .5rem
  .info-login li:nth-child(2) button
    @include ct() // 垂直局中
    right: .4rem
    z-index: 10

  @media screen and (max-height: 505px)
    #login
      margin-top: 1rem
  @media screen and (max-height: 480px)
    #login
        margin-top: .5rem
        .info-login li:nth-child(4)
          margin-top: .5rem
</style>