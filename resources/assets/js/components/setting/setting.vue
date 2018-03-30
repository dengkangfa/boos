<template>
    <transition name="horizontal-slide">
        <div class="setting-wrapper" v-show="showFlag">
            <dkf-header title="设置" class="_effect" :class="{'_effect--50': decline}" fixed>
                <div slot="left" @click="hide()"><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="main">
                <ul>
                    <li @click="logout" class="logout">
                        <span>退出登录</span>
                    </li>
                </ul>
            </div>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'

  export default {
    data() {
      return {
        decline: false,
        showFlag: false
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      logout() {
        this.$store.dispatch('logoutRequest').then(response => {
          sessionStorage.setItem('logout-message', '已退出登录')
          this.$router.push({'name': 'login'})
        })
      }
    },
    components: {
      dkfHeader
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass">
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
            .logout
                height: 45px
                line-height: 45px
                color: $red
                background: #FFFFFF
                text-align: center
</style>