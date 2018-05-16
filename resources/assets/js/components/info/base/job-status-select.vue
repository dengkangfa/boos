<template>
    <div>
        <transition name="vertical-slide">
            <div class="apply-status-wrapper" v-show="showFlag">
                <ul class="apply-status-list">
                    <li class="apply-status-list-header">求职状态</li>
                    <li @click="select(0)">离职-随时到岗</li>
                    <li @click="select(1)">在职-暂不考虑</li>
                    <li @click="select(2)">在职-考虑机会</li>
                    <li @click="select(3)">在职-月内到岗</li>
                </ul>
                <div class="cancel-button" @click="hide">取消</div>
            </div>
        </transition>
        <transition name="fade">
            <div class="list-mask" @click="hide" v-show="showFlag"></div>
        </transition>
    </div>
</template>

<script type="text/ecmascript-6">
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
      },
      select(value) {
        this.$emit('select', value)
        this.hide()
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .apply-status-wrapper
        position: fixed
        left: 0
        right: 0
        bottom: 0
        z-index: 50
        font-size: .55rem
        text-align: center
        color: #1885ff
        margin: 10px
        .apply-status-list
            border-radius: .3rem
            background: #ffffff
            li:first-child
                height: 1.2rem
                line-height: 1.2rem
                color: $color-text-l
                font-size: .35rem
            li:not(:first-child)
                height: 1.5rem
                line-height: 1.5rem
                @include border-top-1px($color-text-d)
        .cancel-button
            height: 1.5rem
            line-height: 1.5rem
            font-weight: 700
            background: #fff
            border-radius: .3rem
            margin-top: 8px
    .list-mask
        @include allCover()
        z-index: 40
        opacity: 1
        background: $color-text-d
        &.fade-enter-active, &.fade-leave-active
            transition: all .5s
        &.fade-enter, &.fade-leave-to
            opacity: 0
</style>