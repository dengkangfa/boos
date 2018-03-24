<template>
    <transition name="vertical-slide">
        <div class="full-screen-textarea-wrapper" v-if="showFlag">
            <dkf-header :title="title">
                <div slot="left" @click="cancel"><i class="icon-close" style="padding: 0.3rem;"></i></div>
                <div slot="right" @click="confirm"><i class="icon-correct" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="main">
                <div class="textarea-wrapper">
                    <textarea v-model="currentValue"  mixlength="2" ref="textareaInput" :placeholder="placeholder" v-focus></textarea>
                </div>
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
      value: String,
      placeholder: String,
      title: {type: String, default: ''}
    },
    data() {
      return {
        showFlag: false,
        showConfirmButton: false,
        cancelButtonText: '取消',
        oldValue: '',
        message: ''
      }
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
      currentValue: {
        get() {
          return this.value
        },
        set(val) {
          this.$emit('input', val)
        }
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      confirm() {
        if (this.currentValue.length === 0) {
          this.message = '内容不能为空'
          this.cancelButtonText = '好'
          this.showConfirmButton = false
          this.$refs.messageBox.show()
          return
        }
        this.hide()
      },
      cancel() {
        if (this.oldValue === this.value) {
          this.hide()
          return
        }
        this.message = '内容尚未保存，确定放弃？'
        this.cancelButtonText = '取消'
        this.showConfirmButton = true
        this.$refs.messageBox.show()
      },
      messageBoxConfirm() {
        this.currentValue = this.oldValue
        this.hide()
      }
    },
    components: {
      dkfHeader,
      messageBox
    },
    watch: {
      showFlag() {
        this.oldValue = this.value
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .full-screen-textarea-wrapper
        @include allCover
        background: $bc
        .main
            height: 100%
            .textarea-wrapper
                height: 100%
                margin-top: 20px
                textarea
                    width: 100%
                    height: 100%
                    font-size: .37rem
                    color: $color-text-l
                    border: none
                    outline: none
                    resize: none
                    box-sizing: border-box
                    padding: 10px
</style>