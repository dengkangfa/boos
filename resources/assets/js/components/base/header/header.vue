<template>
    <header class="dkf-header" :class="{ 'is-fixed': fixed }">
        <div class="dkf-header-button left">
            <slot name="left"></slot>
        </div>
        <h1 class="dkf-header-title" v-text="title"></h1>
        <div class="dkf-header-button right">
            <slot name="right"></slot>
        </div>
    </header>
</template>

<script type="text/ecmascript-6">
  export default {
    props: {
      fixed: Boolean,
      title: String
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    header
        display: flex
        justify-content: center
        align-items: center
        width: 100%
        height: 50px
        font-size: 0.4rem
        color: $color-text
        background: $color-theme
        text-align: center
        overflow: hidden
        white-space: nowrap
        text-overflow: ellipsis
        box-sizing: border-box
        padding: 0.35rem 0.2rem 0 0.2rem
        &.is-fixed
            position: fixed
            top: 0
            left: 0
            right: 0
        .dkf-header-title
            font-size: inherit
            font-weight: normal
            flex: 1
        .dkf-header-button
            flex: .5
            &.left
                text-align: left
            &.right
                text-align: right
</style>