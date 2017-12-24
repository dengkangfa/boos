<template>
    <button @click.prevent="run" :disabled="disabled || time > 0" class="btn-link" :class="theme">
        {{ text }}
    </button>
</template>

<script type="text/ecmascript-6">
  export default {
    props: {
      theme: {
        type: String,
        default: 'white'
      },
      second: {
        type: Number,
        default: 60
      },
      disabled: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        time: 0
      }
    },
    methods: {
      run() {
        this.$emit('run')
      },
      start() {
        this.time = this.second
        this.timer()
      },
      reset() {
        this.time = 0
      },
      timer() {
        if (this.time > 0) {
          this.time--
          setTimeout(this.timer, 1000)
        } else {
          this.disabled = false
        }
      }
    },
    computed: {
      text() {
        return this.time > 0 ? this.time + '秒后重试' : this.$emit('end') && '获取验证码'
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    .btn-link
        display: block
        width: 2.3rem
        height: .6rem
        padding: 0 .3rem
        line-height: .6rem
        border-radius: .3rem
        font-weight: 100
        font-size: .3rem
        background: rgba(0,0,0,0)
    .white
        color: #FFFFFF
        border: 1px solid #FFFFFF
    .vue
        color: #53CAC3
        border: 1px solid #53CAC3
</style>