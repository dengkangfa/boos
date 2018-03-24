<template>
    <div>
        <transition name="vertical-slide">
            <div class="options" v-show="showFlag">
                <div @click="$router.push({'name': 'select-identity'})"><span>切换为牛人身份</span><i class="icon icon-right"></i></div>
                <div @click="logout"><span>退出登录</span><i class="icon icon-right"></i></div>
            </div>
        </transition>
        <transition name="fade">
            <div class="list-mask" @click="showFlag = false" v-show="showFlag"></div>
        </transition>
    </div>
</template>

<script type="text/ecmascript-6">
  export default {
    data() {
      return {
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
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../../sass/variables"
    @import "../../../../../sass/mixin"

    .options
        position: absolute
        z-index: 50
        left: 0
        right: 0
        bottom: 0
        font-size: .5rem
        background: #ffffff
        padding: 0 20px
        div:first-child
            @include border-bottom-1px($color-text-d)
        div
            display: flex
            justify-content: space-between
            align-items: center
            padding: 20px 0
            .icon
                font-size: .3rem
                color: $color-text-d
</style>