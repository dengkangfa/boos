<template>
    <div>
        <header class="header">
            <div class="back" @click.stop="left"><p><i class="icon" :class="backIcon"></i></p></div>
            <span class="title">{{ title }}</span>
            <div class="next" @click.stop="right" style="text-align: right"><p><i class="icon" :class="nextIcon"></i>{{ nextText }}</p></div>
        </header>
    </div>
</template>

<script type="text/ecmascript-6">
  export default {
    props: {
      title: {
        type: String,
        default: ''
      },
      backIcon: {
        type: String,
        default: 'icon-left'
      },
      nextText: {
        type: String,
        default: ''
      },
      nextIcon: {
        type: String,
        default: ''
      }
    },
    methods: {
      left() {
        this.$emit('left')
      },
      right() {
        this.$emit('right')
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .header
        display: flex
        justify-content: center
        font-size: 0.4rem
        align-items: center
        height: 1rem
        color: $color-text
        background: $color-theme
        padding: 0.35rem 0.2rem 0 0.2rem
        div
            width: 2rem
            p
                padding: 0.3rem
        .title
            flex: 1
            text-align: center
        [class^="icon-"], [class*=" icon-"]
            font-weight: bold
        .icon-correct
            font-size: 0.5rem
</style>