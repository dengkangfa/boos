<template>
    <div id="home">
        <transition>
            <keep-alive>
                <router-view></router-view>
            </keep-alive>
        </transition>
        <!--组件的应用-->
        <div class="navbar flex_parent" v-if="user.roles.indexOf('recruiter') !== -1">
            <router-link to="/home" class="flex_child"><span class="icon-earth"></span><span>牛人</span></router-link>
            <router-link to="/message" class="flex_child"><span class="icon-message"></span><span>消息</span></router-link>
            <router-link to="/aboutme" class="flex_child"><span class="icon-me"></span><span>我的</span></router-link>
        </div>
        <div class="navbar flex_parent" v-else>
            <router-link to="/home" class="flex_child"><span class="icon-earth"></span><span>职位</span></router-link>
            <router-link to="/company" class="flex_child"><span class="icon-company"></span><span>公司</span></router-link>
            <router-link to="/message" class="flex_child"><span class="icon-message"></span><span>消息</span></router-link>
            <router-link to="/aboutme" class="flex_child"><span class="icon-me"></span><span>我的</span></router-link>
        </div>
    </div>
</template>

<script>
  import {mapState} from 'vuex'
  export default {
    data () {
      return {
        roles: []
      }
    },
    created() {
      if (!this.user.authenticated) {
        this.$store.dispatch('setAuthUser').then(response => {
          echo.channel(`user.${this.user.id}`)
            .listen('newContacts', function (response) {
              console.log(response)
            })
        })
      }
    },
    computed: {
      ...mapState({
        user: state => state.AuthUser
      })
    },
    watch: {
      user() {
        this.roles = this.user.roles
        console.log(this.roles)
      }
    }
//    watch: {
//      '$route' (to, from) {
//        const toDepth = to.path.split('/').length
//        const fromDepth = from.path.split('/').length
//        this.transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left'
//      }
//    }
  }

</script>

<style lang="sass" scoped scoped>
    @import "../../../sass/variables"
    @import "../../../sass/mixin"

    #home
        @include allCover()
        background: #f7f7f7
        .navbar
            width: 100%
            position: fixed
            left: 0
            bottom: 0
            background: #fff
            z-index: 10
            @include border-top-1px($color-text-d)
            box-sizing: border-box
            a
                color: #808080
                padding: .18rem 0
                transition: all .5s
                text-align: center
                span
                    display: block
                [class^="icon-"], [class*=" icon-"]
                    font-size: .6rem
                &.router-link-active
                    color: $color-theme
</style>