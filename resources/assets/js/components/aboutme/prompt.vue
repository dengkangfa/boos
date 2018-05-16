<template>
    <div class="prompt-wrapper" @click.stop @touchmove.prevent v-show="showFlag">
        <div class="prompt-main">
            <div class="cancel-btn" @click="hide"><span><i class="icon icon-close"></i></span></div>
            <div class="prompt">
                <div class="prompt-message"><p v-html="message"></p></div>
                <div class="prompt-btn" @click="showFlag = false;$emit('confirm')">
                    {{ btnText }}
                </div>
            </div>
        </div>
        <transition name="fade">
            <div class="list-mask" @click="hide"></div>
        </transition>
    </div>
</template>

<script type="text/ecmascript-6">
  export default {
    props: {
      message: {
        type: String
      },
      btnText: {
        type: String
      }
    },
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
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../sass/variables"
    @import "../../../sass/mixin"

    .prompt-wrapper
        @include allCover
        .prompt-main
            position: relative
            @include center()
            z-index: 50
            width: 70%
            background: #FFFFFF
            border-radius: .15rem
            font-size: .45rem
            padding: 5px
            .cancel-btn
                position: absolute
                top: 0
                right: 0
                transform: translate(50%, -50%)
                font-size: .3rem
                color: $color-theme
                background: #FFFFFF
                border: 1px solid #eeeeee
                border-radius: 50%
                padding: 5px
            .prompt-message
                color: $color-text-l
                line-height: .8rem
                text-align: center
                margin: 10px 0
            .prompt-btn
                height: 40px
                line-height: 40px
                color: #FFFFFF
                background: $color-theme
                text-align: center
                border-radius: .1rem
                margin: 15px
        .list-mask
            position: absolute
            top: 0
            left: 0
            width: 100%
            height: 100%
            z-index: 40
            /*backdrop-filter: blur(10px)*/
            opacity: 1
            background: $color-text-d
            &.fade-enter-active, &.fade-leave-active
                transition: all 0.5s
            &.fade-enter, &.fade-leave-to
                opacity: 0
</style>