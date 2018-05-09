<template>
    <transition name="horizontal-slide">
        <div class="setting-wrapper">
            <dkf-header title="设置" fixed>
                <div slot="left" @click="$router.back()"><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="main">
                <ul>
                    <li>
                        <div><span>提醒设置</span></div>
                        <div><span><i class="icon-right"></i></span></div>
                    </li>
                    <li>
                        <div><span>直接接收直直红包</span></div>
                        <div><span><i class="icon-right"></i></span></div>
                    </li>
                    <li class="m-b">
                        <div><span>设置打招呼语</span></div>
                        <div><span><i class="icon-right"></i></span></div>
                    </li>
                    <li>
                        <div><span>修改手机号</span></div>
                        <div><span><i class="icon-right"></i></span></div>
                    </li>
                    <li class="m-b">
                        <div><span>修改密码</span></div>
                        <div><span><i class="icon-right"></i></span></div>
                    </li>
                    <li>
                        <div><span>我要评价</span></div>
                        <div><span><i class="icon-right"></i></span></div>
                    </li>
                    <li>
                        <div><span>关于我们</span></div>
                        <div><span><i class="icon-right"></i></span></div>
                    </li>
                    <li @click="$refs.curRoleCom.show()" class="m-b">
                        <div><span>切换身份</span></div>
                        <div><span style="margin-right: 5px;color: rgb(152, 151, 151)">{{role}}</span><span><i class="icon-right"></i></span></div>
                    </li>
                    <li @click="logout" class="logout">
                        <span>退出登录</span>
                    </li>
                </ul>
            </div>
            <cur-role :role="role" ref="curRoleCom"></cur-role>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import {mapState} from 'vuex'
  import curRole from './cur-role'

  export default {
    computed: {
      ...mapState({
        user: state => state.AuthUser
      }),
      role() {
        return this.user.roles.indexOf('applicant') === 0 ? '牛人' : 'Boss'
      }
    },
    methods: {
      logout() {
        this.$store.dispatch('logoutRequest').then(response => {
          sessionStorage.setItem('logout-message', '已退出登录')
          this.$router.push({'name': 'login'})
        })
      }
    },
    components: {
      dkfHeader,
      curRole
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../sass/variables"
    @import "../../../sass/mixin"

    .setting-wrapper
        @include allCover()
        background: $bc
        z-index: 100
        .main
            position: absolute
            top: 50px
            bottom: 0
            width: 100%
            overflow-y: scroll
            -webkit-overflow-scrolling: touch
            ul
                li
                    display: flex
                    justify-content: space-between
                    height: 45px
                    line-height: 45px
                    color: $color-text-ll
                    background: #FFFFFF
                    padding: 0 10px
                    i
                        color: $color-theme
                    &.m-b
                        margin-bottom: 20px
                    &.logout
                        justify-content: center
                        color: $red
                        text-align: center
</style>