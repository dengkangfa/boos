<template>
    <div class="login-bg">
      <div id="login">
        <form action="">
          <h3>BOSS直聘<span></span></h3>
          <ul class="info-login">
            <li><b>{{lable}}<i class="icon-down"></i></b><input type="text" v-model="userules.mobile" pattern="[0-9]*" placeholder="请输入您的手机号" ref="mobile" @focus="closeIconShow = true"><i class="icon-circle-with-cross" @click.prevent="close" v-show="closeIconShow && userules.mobile"></i></li>
            <li><span></span><input type="number" v-model="userules.verifyCode" pattern="[0-9]*" oninput="if(value.length>6)value=value.slice(0,6)" placeholder="验证码" @focus="closeIconShow = false" ref="verifyCodeInput"><timer-btn @run="sendVerifyCode" @end="end" ref="timer"></timer-btn></li>
            <li><p>长时间收不到验证码，可尝试 <a style="text-decoration: underline;color: #42b983" @click.prevent="sendVoiceVerify">语音接听验证码</a></p></li>
            <li><input @click.prevent="" type="submit" value="进入"></li>
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
        <router-link to="/login/password"><span>密码登录</span></router-link>
      </div>
      <message :message="message" ref="message"></message>
      <fading-circle text="正在发送中" v-show="spinning"></fading-circle>
      <router-view></router-view>
    </div>
</template>

<script type="text/ecmascript-6">
  import message from 'Base/message/message.vue'
  import fadingCircle from 'Base/spinner/fading-circle.vue'
  import timerBtn from 'Base/timer-btn/TimerBtn.vue'
  import {sendVerifyCode, sendVoiceVerify} from 'Api/sms.js'
  import {loginMixin} from 'Mixin/mixin.js'

  export default {
    mixins: [loginMixin],
    data () {
      return {
        // 用户信息
        userules: {
          verifyCode: ''
        },
        spinning: false, // 是否显示loading
        disabledVoiceVerify: false, // 语音验证码服务是否可用
        message: '', // 提示消息
        lable: '+86'
      }
    },
    methods: {
      sendVerifyCode() {
        this.disabledVoiceVerify = true // 禁止获取语音验证码发送服务
        if (this.checkMobileRegex()) {
          this.spinning = true // 显示正在发送的loading
          sendVerifyCode(this.userules.mobile).then(response => {
            this.spinning = false // 隐藏正在发送的loading
            this.$refs.timer.start() // 可重复获取验证码按钮进入倒计时
            this.$refs.verifyCodeInput.focus() // 让验证码输入框获取焦点(提高用户体验)
            console.log(response)
          })
        }
      },
      sendVoiceVerify() {
        if (!this.disabledVoiceVerify && this.checkMobileRegex()) {
          this.disabledVoiceVerify = true // 禁止重复获取语音验证码发送服务
          this.spinning = true // 显示正在发送的loading
          sendVoiceVerify(this.userules.mobile).then(response => {
            this.spinning = false  // 隐藏正在发送的loading
            this.$refs.timer.start() // 可重复获取验证码按钮进入倒计时
            this.$refs.verifyCodeInput.focus() // 让验证码输入框获取焦点(提高用户体验)
            console.log(response)
          })
        }
      },
      end() {
        this.disabledVoiceVerify = false
      },
    },
    components: {
      message,
      fadingCircle,
      timerBtn
    }
  }

</script>

<style lang="sass" rel="stylesheet/sass" scoped>
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
      border-color: $vuecolor
    & input[type=submit],input[type=button]
      width: 100%
      height: 100%
      cursor: pointer
      background-color: $vuecolor
      border: 1px solid $vuecolor
      border-radius: .8rem
      -webkit-appearance: none
      white-space: nowrap
    & input[type=submit]:hover,input[type=button]:hover
      background-color: #42AA83
  .info-login li:nth-child(1) b
    position: absolute
    display: block
    top: 50%
    transform: translateY(-50%)
    color: #fff
    font-weight: 100
    /*left: .4rem*/
    padding-left: .4rem
  .info-login li:nth-child(1) .icon-circle-with-cross
    position: absolute
    top: 50%
    transform: translateY(-50%)
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
  .info-login li:nth-child(2) span
    display: block
    width: .6rem
    height: .6rem
    background: url(./icon_phone.png) no-repeat
    background-size: 100% 100%
    position: absolute
    left: .4rem
    top: 50%
    transform: translateY(-50%)
  .info-login li:nth-child(2) button
    position: absolute
    top: 50%
    transform: translateY(-50%)
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