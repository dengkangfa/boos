<template>
    <div @click.stop @touchmove.prevent>
        <transition name="slide">
            <mint-picker :slots="slots" :showToolbar="showToolbar" v-show="showFlag" class="picker" @change="onValuesChange" ref="picker">
                <div class="header">
                    <div class="cancel" @click.stop="cancel"><i class="icon icon-close"></i></div>
                    <span class="title">{{ title }}</span>
                    <div class="confirm" @click.stop="confirm" style="text-align: right"><i class="icon icon-correct"></i></div>
                </div>
            </mint-picker>
        </transition>
        <transition name="fade">
            <div class="list-mask" @click="hide" v-show="showFlag"></div>
        </transition>
    </div>
</template>

<script type="text/ecmascript-6">
  export default {
    props: {
      title: {
        type: String,
        default: ''
      },
      showToolbar: {
        type: Boolean,
        default: true
      },
      slots: {
        type: Array,
        default: []
      }
    },
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
      cancel() {
        this.hide()
      },
      confirm() {
        this.$emit('confirm')
      },
      onValuesChange(picker, values) {
        this.$emit('onValuesChange', picker, values)
      },
      setValues(values) {
        this.$refs.picker.setValues(values)
      },
      setSlotValues(index, value) {
        this.$refs.picker.setSlotValues(index, value)
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .picker
        position: fixed
        width: 100%
        bottom: 0
        background: #ffffff
        z-index: 50
        .header
            display: flex
            justify-content: center
            font-size: 0.4rem
            align-items: center
            height: 1rem
            color: $color-text-d
            background: #ffffff
            @include border-bottom-1px($color-text-d)
            .title
                flex: 1
                text-align: center
            .cancel
                width: 1.5rem
            [class^="icon-"], [class*=" icon-"]
                color: $color-theme
                padding: 0.3rem 0.5rem
            .icon-correct
                font-size: 0.5rem
            .confirm
                width: 1.5rem
        &.slide-enter-active, .slide-leave-active
            transition: all 0.3s
        &.slide-enter, .slide-leave-to
            transform: translate3d(0, 100%, 0)
    .list-mask
        position: absolute
        /*top: 0*/
        /*left: 0*/
        width: 100%
        height: 100%
        z-index: 40
        /*backdrop-filter: blur(10px)*/
        opacity: 1
        background: $color-text-d
        &.fade-enter-active, &.fade-leave-active
            transition: all 0.5s
        &.fade-enter, &.fade-leave-to
            opacity: 0
</style>