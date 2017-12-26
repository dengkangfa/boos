<template>
    <transition name="slide">
        <div class="name-wrapper" v-if="showFlag" @click.stop.prevent="">
            <dkf-header title="姓名" nextIcon="icon-correct" backIcon="icon-close"  @left="cancel" @right="confirm"></dkf-header>
            <div>
                <input type="text" v-model="nameValue" class="name-input" ref="nameInput" @keyup.13="confirm" v-focus>
                <div style="text-align: right;padding: 0.4rem 0.2rem;"><span class="word-count"><p class="current-length" :class="{'exceed': isExceed}">{{ nameLength }}</p>/{{ length }}</span></div>
            </div>
            <message-box confirmButtonText="放弃"  :cancelButtonText="cancelButtonText" :showConfirmButton="showConfirmButton" :message="message" @confirm="messageBoxConfirm" ref="messageBox"></message-box>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import messageBox from 'Base/message/message-box'

  export default {
    props: {
      name: {
        type: String,
        default: ''
      }
    },
    data() {
      return {
        showFlag: false,
        nameValue: '', // 旧姓名
        message: '',
        length: 12,
        showConfirmButton: false,
        cancelButtonText: '取消'
      }
    },
    created() {
      this.nameValue = this.name
    },
    directives: {
      focus: {
        inserted (el) {
          setTimeout(() => {
            el.focus()
          }, 350)
        }
      }
    },
    computed: {
      nameLength() {
        return this.nameValue.length
      },
      isExceed() {
        return this.nameValue.length > this.length
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
        if (this.nameValue === this.name) {
          this.hide()
          return
        }
        this.message = '内容尚未保存，确定放弃？'
        this.cancelButtonText = '取消'
        this.showConfirmButton = true
        this.$refs.messageBox.show()
      },
      confirm() {
        this.$refs.nameInput.blur()
        if (this.nameValue.length > this.length) {
          this.message = '超过字数限制'
          this.cancelButtonText = '好'
          this.showConfirmButton = false
          this.$refs.messageBox.show()
          return
        }
        this.$emit('saveName', this.nameValue)
        this.hide()
      },
      messageBoxConfirm() {
        this.nameValue = this.name
        this.hide()
      }
    },
    components: {
      dkfHeader,
      messageBox
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .name-wrapper
        @include allCover
        background: rgb(238, 238, 238)
        .name-input
            width: 100%
            padding: 0.4rem
            margin-top: 0.5rem
        .word-count
            display: block
            width: 100%
            color: $color-text-d
            .current-length
                display: inline
                color: $color-theme
                &.exceed
                    color: red

    .slide-enter-active, .slide-leave-active
        transition: all 0.3s
    .slide-enter, .slide-leave-to
        transform: translate3d(0, 100%, 0)
</style>