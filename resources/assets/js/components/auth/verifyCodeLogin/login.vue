<template>
    <div class="login-bg">
      <div id="login">
        <form action="">
          <h3>BOSS直聘<span></span></h3>
          <ul class="info-login">
            <li><b>{{lable}}<i class="icon-down"></i></b><input type="text" v-model="userules.mobile" pattern="[0-9]*" placeholder="请输入您的手机号" ref="mobile" @focus="iconShow = true"><i class="icon-circle-with-cross" @click.prevent="close" v-show="iconShow && userules.mobile"></i></li>
            <li><span></span><input type="number" v-model="userules.verifyCode" pattern="[0-9]*" oninput="if(value.length>6)value=value.slice(0,6)" placeholder="验证码" @focus="iconShow = false"><b @click.prevent="">获取验证码</b></li>
            <li><p>长时间收不到验证码，可尝试 <a style="text-decoration: underline;color: #42b983;">语音接听验证码</a></p></li>
            <li><input @click.prevent="" type="submit" value="进入"></li>
          </ul>
        </form>
      </div>
      <!--底部-->
      <div class="login-footer flex_parent">
        <div class="or">
          <div class="line"></div>
          <div class="text">OR</div>
          <div class="line"></div>
        </div>
        <router-link to="" class="protocol"><span>用户协议及隐私策略</span></router-link>
        <router-link to="" ><span>密码登录</span></router-link>
      </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                // 用户信息
                userules:{
                  mobile: "",
                  verifyCode: ""
                },
                iconShow: false,
                lable:"+86"
            }
        },
        methods: {
          close() {
            this.userules.mobile = '';
          }
        }
    }

</script>

<style lang="scss" scoped>
  @import "../../../../sass/mixin";

  .login-bg{
    position: fixed;
    width: 100%;
    height: 100%;
    overflow:auto;
    background: linear-gradient(to bottom,  #A46E92 0%,#27455D 100%);
  }
  #login{
    position: absolute;
    top: 13%;
    left: 50%;
    transform: translateX(-50%);
    // @include center;
    width: 90%;
    box-sizing: border-box;
    & h3{
      text-align: center;
      margin-bottom: 1.2rem;
      @include sc(1.2rem,#fff);
      & span {
        background: url('./Introduction.png') no-repeat;
        background-size: 4rem;
        display: block;
        height: 0.5rem;
        margin: auto;
        width: 150px;
      }
    }
  }
  .info-login li:nth-child(1),
  .info-login li:nth-child(2){
    margin-top: .6rem;
  }
  .info-login li:nth-child(3){
    text-align: center;
    color: #fff;
  }
  .info-login li:nth-child(4){
    margin-top: 1rem;
  }
  .info-login li{
    height: 1.2rem;
    line-height: 1.2rem;
    position: relative;
    & label{
      @include sc(.3rem,#999);
    }
    & input{
      @include sc(.4rem,#fff);
      background-image: none;
      transition:all .3s;
      background: rgba(154,165,181,.3);
    }
    & input[type=text],input[type=number]{
      width: 100%;
      padding: 0.3rem 1rem 0.3rem 2rem;
      border-radius: .8rem;
      &::placeholder {
        color: #fff;
      }
    }
    & input[type=text]:focus,input[type=number]:focus{
      border-color: $vuecolor;
    }
    & input[type=submit],input[type=button]{
      width: 100%;
      height: 100%;
      cursor: pointer;
      background-color: $vuecolor;
      border: 1px solid $vuecolor;
      border-radius: .8rem;
      -webkit-appearance: none;
      white-space: nowrap;

    }
    & input[type=submit]:hover,input[type=button]:hover{
      background-color: #42AA83;
    }
  }
  .info-login li:nth-child(1) b{
    display: block;
    top: 50%;
    transform: translateY(-50%);
    position: absolute;
    color: #fff;
    font-weight: 100;
    /*left: .4rem;*/
    padding-left: .4rem;
  }
  .info-login li:nth-child(1) .icon-circle-with-cross {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: .45rem;
    right: .1rem;
    color: #605e5e;
    padding: 8px;
  }
  .info-login li:nth-child(1):before,
  .info-login li:nth-child(2):before{
    display: table;
    content: "";
    position: absolute;
    background: #fff;
    width: 1px;
    height: 100%;
    top: 50%;
    left: 1.6rem;
    -webkit-transform: scaleY(0.3) translateY(-50%);
    transform: scaleY(0.3) translateY(-50%);
    -webkit-transform-origin: 0 0;
    transform-origin: 0 0;
  }
  .info-login li:nth-child(2) span{
    display: block;
    width: .6rem;
    height: .6rem;
    background: url(./icon_phone.png) no-repeat;
    background-size: 100% 100%;
    position: absolute;
    left: .4rem;
    top: 50%;
    transform: translateY(-50%);
  }
  .info-login li:nth-child(2) b{
    display: block;
    height: .6rem;
    padding: 0 .3rem;
    line-height: .6rem;
    position: absolute;
    border: 1px solid #fff;
    border-radius: .3rem;
    top: 50%;
    transform: translateY(-50%);
    right: .4rem;
    color: #fff;
    font-weight: 100;
    font-size: .3rem;
    z-index: 10;
  }
  .login-footer{
    position: fixed;
    width: 100%;
    left: 0;
    bottom: 0;
    &.flex_parent{
      flex-wrap:wrap;
      -webkit-flex-wrap: wrap;
      -moz-flex-wrap: wrap;
      -ms-flex-wrap: wrap;
      -o-flex-wrap: wrap;
    }
    & .or {
      display: flex;
      width: 100%;
      align-items: center;
      color: #42b983;
      transform: translateY(50%);
      opacity: 0.5;
      & .line {
        position: relative;
        flex: 1;
        @include border-1px(#42b983);
      }
      & .text {
        padding: 0 12px;
        font-weight: 700;
        font-size: 14px;
      }
    }
    & a{
      line-height: 1.8rem;
      color: #fff;
      display: block;
      text-align: center;
      font-size: .35rem;
      width: 50%;
      &.protocol span{
        background: url('select.png') no-repeat;
        background-size: .40rem;
        padding-left: 20px;
      }
    }
  }
</style>