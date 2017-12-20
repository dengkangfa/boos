<template>
    <transition name="confirm-fade">
        <div class="confirm" v-show="showFlag" @click.stop>
            <div class="confirm-wrapper">
                <div class="confirm-content">
                    <p class="text">{{ message }}</p>
                    <div class="operate">
                        <div @click="cancel" class="operate-btn left" v-show="showCancelButton">{{ cancelButtonText }}</div>
                        <div @click="confirm" class="operate-btn" v-show="showConfirmButton">{{ confirmButtonText }}</div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
    export default {
      data() {
        return {
          showFlag: false
        }
      },
      props: {
        message: {
          type: String,
          default: ''
        },
        confirmButtonText: {
          type: String,
          default: '确定'
        },
        cancelButtonText: {
          type: String,
          default: '取消'
        },
        showCancelButton: {
          type: Boolean,
          default: false
        },
        showConfirmButton: {
          type: Boolean,
          default: true
        }
      },
      methods: {
        show() {
          this.showFlag = true
        },
        hide() {
          this.showFlag = false
        },
        cancel() {
          this.hide()
          this.$emit('cancel')
        },
        confirm() {
          this.hide()
          this.$emit('confirm')
        }
      }
    }
</script>

<style scoped lang="sass" rel="stylesheet/sass">
    .confirm
        position: fixed
        left: 0
        right: 0
        top: 0
        bottom: 0
        z-index: 998
        &.confirm-fade-enter-active
            animation: confirm-fadein 0.3s
            .confirm-content
                animation: confirm-zoom 0.3s
        .confirm-wrapper
            position: absolute
            top: 50%
            left: 50%
            transform: translate(-50%, -50%)
            z-index: 999
            .confirm-content
                width: 270px
                border-radius: 13px
                background: #fff
                .text
                    padding: 19px 15px
                    line-height: 22px
                    text-align: center
                    font-size: .45rem
                    font-weight: 700
                    color: #000000
                .operate
                    display: flex
                    align-items: center
                    text-align: center
                    font-size: .45rem
                    .operate-btn
                        flex: 1
                        line-height: 22px
                        padding: 10px 0
                        border-top: 1px solid rgba(52, 159, 217, 0.5)
                        color: #59A6FA
                        &.left
                            border-right: 1px solid rgba(52, 159, 217, 0.5)
    @keyframes confirm-fadein
        0%
            opacity: 0
        100%
            opacity: 1
    @keyframes confirm-zoom
        0%
            transform: scale(0)
        50%
            transform: scale(1.1)
        100%
            transform: scale(1)
</style>