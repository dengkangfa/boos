<template>
    <transition name="slide">
        <div class="industry-checkbox-wrapper" v-show="showFlag">
            <dkf-header title="选择行业" fixed>
                <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                <div slot="right"><span>保存</span></div>
            </dkf-header>
            <div class="selected">
                <div class="selected-top">
                    <div class="title">已选行业</div>
                    <div><span class="count">0</span>/3</div>
                </div>
                <div class="selected-industry">
                    <span class="placeholder">请选择行业，最多3个</span>
                </div>
            </div>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'

  export default {
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
    },
    components: {
      dkfHeader
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass">
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .industry-checkbox-wrapper
        @include allCover
        background: #ffffff
        .selected
            box-shadow: 0 5px 5px #eee
            padding: 20px 20px 1px 20px
            .selected-top
                display: flex
                justify-content: space-between
                font-size: .4rem
                .title
                    font-weight: 700
                    color: $color-text-l
                .count
                    color: $color-theme
            .selected-industry
                margin: 40px 0
                .placeholder
                    color: #999999

    .slide-enter-active, .slide-leave-active
        transition: all .3s
    .slide-enter, .slide-leave-to
        transform: translate3d(100%, 0, 0)
</style>