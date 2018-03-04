<template>
    <transition name="vertical-slide">
        <div class="name-wrapper" v-if="showFlag" @click.stop.prevent>
            <dkf-header :title="title">
                <div slot="left" @click="cancel"><i class="icon-close" style="padding: 0.3rem;"></i></div>
                <div slot="right" @click="confirm"><i class="icon-correct" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div>
                <div v-if="wordCountPosition === 'top'" class="top-word-count-wrapper" v-show="showValueLength"><span class="word-count"><p class="current-length" :class="{'exceed': isExceed}">{{ valueLength }}</p>/{{ maxLength }}</span></div>
                <input v-if="type === 'input'" type="text" v-model="newValue" class="name-input" ref="nameInput" @keyup.13="confirm" v-focus>
                <div class="textarea-wrapper" v-else>
                    <textarea v-model="newValue"  :maxlength="maxLength !== -1 ? maxLength : ''" mixlength="2" ref="nameInput" @keyup.13="confirm" :placeholder="placeholder" v-focus></textarea>
                </div>
                <div v-if="wordCountPosition === 'bottom'" style="text-align: right;padding: 0.4rem 0.2rem;" v-show="showValueLength"><span class="word-count"><p class="current-length" :class="{'exceed': isExceed}">{{ valueLength }}</p>/{{ maxLength }}</span></div>
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
      value: '',
      type: {
        type: String,
        default: 'input'
      },
      title: {
        type: String,
        default: ''
      },
      placeholder: {
        type: String,
        default: ''
      },
      showValueLength: {
        type: Boolean,
        default: true
      },
      allowEmpty: {
        type: Boolean,
        default: false
      },
      maxLength: {
        type: Number,
        default: 20
      },
      wordCountPosition: {
        type: String,
        default: 'bottom'
      }
    },
    data() {
      return {
        showFlag: false,
        newValue: '', // 旧姓名
        message: '',
        showConfirmButton: false,
        cancelButtonText: '取消'
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
      valueLength() {
        return this.newValue.length
      },
      isExceed() {
        return this.newValue.length > this.maxLength
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
        if (this.newValue === this.value) {
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
        if (this.newValue.length === 0 && !this.allowEmpty) {
          this.message = '内容不能为空'
          this.cancelButtonText = '好'
          this.showConfirmButton = false
          this.$refs.messageBox.show()
          return
        }
        if (this.newValue.length > this.maxLength) {
          this.message = '超过字数限制'
          this.cancelButtonText = '好'
          this.showConfirmButton = false
          this.$refs.messageBox.show()
          return
        }
        this.$emit('saveValue', this.newValue)
        this.hide()
      },
      messageBoxConfirm() {
        this.newValue = this.value
        this.hide()
      }
    },
    watch: {
      showFlag() {
        this.newValue = this.value
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
        .top-word-count-wrapper
            position: absolute
            top: 50px
            right: .2rem
            height: .5rem
            line-height: .5rem
        .name-input
            width: 100%
            padding: .4rem
            margin-top: .5rem
        .textarea-wrapper
            padding: 10px 10px 0
            background: #ffffff
            margin-top: 0.5rem
            textarea
                width: 100%
                height: 150px
                font-size: .37rem
                color: $color-text-l
                border: none
                outline: none
                resize: none
                padding: 0
                @include border-bottom-1px($bc)
                &::placeholder
                    color: #bdbdbd
        .word-count
            display: block
            width: 100%
            color: $color-text-d
            .current-length
                display: inline
                color: $color-theme
                &.exceed
                    color: red
</style>