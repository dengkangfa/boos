<template>
    <transition name="message-fade">
        <div class="message" @click.stop v-show="showFlag">
            <p class="text">{{ message }}</p>
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
      }
    },
    methods: {
      show() {
        this.showFlag = true
        clearTimeout(this.timer)
        this.timer = setTimeout(() => {
          this.hide()
        }, 2000)
      },
      hide() {
        this.showFlag = false;
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    .message
        position: absolute
        z-index: 190
        top: 42%
        left: 50%
        padding: 0.4rem 0
        width: 60%
        transform: translate(-50%, -50%)
        background: rgba(0, 0, 0, 0.7)
        border-radius: .8rem
        color: #ffffff
        font-size: 0.4rem
        p
            text-align: center
</style>