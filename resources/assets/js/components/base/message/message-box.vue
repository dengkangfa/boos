<template>
    <transition name="confirm-fade">
        <div class="confirm" v-show="showFlag" @click.stop @touchmove.prevent>
            <div class="confirm-wrapper">
                <div class="confirm-content">
                    <p class="text" :class="{lessenPadding: description}">{{ message }}</p>
                    <div class="description" v-if="description">{{ description }}</div>
                    <div class="input" v-show="showInput">
                        <input v-model="inputValue" :placeholder="inputPlaceholder" ref="input">
                    </div>
                    <div class="operate">
                        <div @click="cancel" class="operate-btn" :class="{'border-radius-left': showConfirmButton}" v-show="showCancelButton">{{ cancelButtonText }}</div>
                        <div @click="confirm" class="operate-btn left" :class="{'border-radius-right': showConfirmButton}" v-show="showConfirmButton">{{ confirmButtonText }}</div>
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
          inputValue: '',
          showFlag: false
        }
      },
      props: {
        message: {
          type: String,
          default: ''
        },
        description: {
          type: String,
          default: ''
        },
        showInput: {
          type: Boolean,
          default: false
        },
        inputPlaceholder: {
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
          default: true
        },
        showConfirmButton: {
          type: Boolean,
          default: false
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
          this.inputValue = ''
        },
        confirm() {
          this.hide()
          this.$emit('confirm')
          this.inputValue = ''
        }
      },
      watch: {
        inputValue(value) {
          if (this.showInput) {
            this.$emit('inputValueChange', value)
          }
        },
        showFlag() {
          setTimeout(() => {
            if (this.$refs.input) {
              this.$refs.input.focus()
            }
          }, 350)
        }
      }
    }
</script>

<style scoped lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/mixin"

    .confirm
        position: fixed
        left: 0
        right: 0
        top: 0
        bottom: 0
        z-index: 998
        background: rgba(0,0,0,0.3)
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
                    &.lessenPadding
                        padding-bottom: 5px
                .description
                    padding: 0 .5rem .5rem
                    text-align: center
                .input
                    padding: 0 .5rem .5rem
                    input
                        width: 100%
                        border: 1px solid #999
                        padding: 5px
                .operate
                    display: flex
                    align-items: center
                    text-align: center
                    font-size: .45rem
                    border-radius: 0 0 13px 13px
                    .operate-btn:active
                        background: #d9d9d9
                    .operate-btn
                        flex: 1
                        line-height: 22px
                        padding: 10px 0
                        border-top: 1px solid #cccccc
                        color: #59A6FA
                        border-radius: 0 0 13px 13px
                        &.border-radius-left
                            border-radius: 0 0 0 13px
                        &.border-radius-right
                            border-radius: 0 0 13px 0
                        &.left
                            border-left: 1px solid #cccccc
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