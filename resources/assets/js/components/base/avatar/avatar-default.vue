<template>
    <div>
        <transition name="vertical-slide">
            <div class="avatar-default-wrapper" v-show="showFlag">
                <div class="avatar-default-items">
                    <div class="box">
                        <div class="row">
                            <div class="item" @click="selectItem(1)"><div class="item-active"></div><i class="icon icon-correct-filling" :class="{active: currentAvatar === 'images/avatar_' + (1 + (type * 8)) + '.png'}"></i><img :src="'images/avatar_' + (1 + (type * 8)) + '.png'"></div>
                            <div class="item" @click="selectItem(2)"><div class="item-active"></div><i class="icon icon-correct-filling" :class="{active: currentAvatar === 'images/avatar_' + (2 + (type * 8)) + '.png'}"></i><img :src="'images/avatar_' + (2 + (type * 8)) + '.png'"></div>
                            <div class="item" @click="selectItem(3)"><div class="item-active"></div><i class="icon icon-correct-filling" :class="{active: currentAvatar === 'images/avatar_' + (3 + (type * 8)) + '.png'}"></i><img :src="'images/avatar_' + (3 + (type * 8)) + '.png'"></div>
                            <div class="item" @click="selectItem(4)"><div class="item-active"></div><i class="icon icon-correct-filling" :class="{active: currentAvatar === 'images/avatar_' + (4 + (type * 8)) + '.png'}"></i><img :src="'images/avatar_' + (4 + (type * 8)) + '.png'"></div>
                        </div>
                        <div class="row">
                            <div class="item" @click="selectItem(5)"><div class="item-active"></div><i class="icon icon-correct-filling" :class="{active: currentAvatar === 'images/avatar_' + (5 + (type * 8)) + '.png'}"></i><img :src="'images/avatar_' + (5 + (type * 8)) + '.png'"></div>
                            <div class="item" @click="selectItem(6)"><div class="item-active"></div><i class="icon icon-correct-filling" :class="{active: currentAvatar === 'images/avatar_' + (6 + (type * 8)) + '.png'}"></i><img :src="'images/avatar_' + (6 + (type * 8)) + '.png'"></div>
                            <div class="item" @click="selectItem(7)"><div class="item-active"></div><i class="icon icon-correct-filling" :class="{active: currentAvatar === 'images/avatar_' + (7 + (type * 8)) + '.png'}"></i><img :src="'images/avatar_' + (7 + (type * 8)) + '.png'"></div>
                            <div class="item" @click="selectItem(8)"><div class="item-active"></div><i class="icon icon-correct-filling" :class="{active: currentAvatar === 'images/avatar_' + (8 + (type * 8)) + '.png'}"></i><img :src="'images/avatar_' + (8 + (type * 8)) + '.png'"></div>
                        </div>
                    </div>
                    <div class="cancel" @click="hide">取消</div>
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div class="list-mask" @click="hide" v-show="showFlag"></div>
        </transition>
    </div>
</template>

<script type="text/ecmascript-6">
  export default {
    props: {
      type: {
        type: Number,
        default: 1
      },
      currentAvatar: {
        type: String,
        default: ''
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
      selectItem(item) {
        let avatar = 'images/avatar_' + (item + (this.type * 8)) + '.png'
        if (this.currentAvatar === avatar) {
          return
        }
        this.$emit('selectDefaultAvatar', {'avatar': avatar})
        this.hide()
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass">
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .avatar-default-wrapper
        position: fixed
        left: 0
        bottom: 0
        z-index: 50
        width: 100%
        background: #ffffff
        .avatar-default-items
            width: 100%
            .box
                .row
                    display: flex
                    flex-wrap: wrap
                    align-content: space-between
                    justify-content: space-around
                    margin: 0.55rem 0
                    .item
                        position: relative
                        .item-active
                            position: absolute
                            width: 100%
                            height: 100%
                            border-radius: 50%
                            &:active
                                opacity: 0.5
                                background: #000
                        i
                            position: absolute
                            top: 0
                            right: 0
                            color: $color-theme
                            font-size: .5rem
                            background: #fff
                            border-radius: 50%
                            transform: translate(25%, -25%)
                            opacity: 0
                            &.active
                                opacity: 1
                        img
                            width: 50px
                            height: 50px
                            border-radius: 50%
            .cancel
                width: 60%
                color: #ffffff
                text-align: center
                border-radius: .1rem
                background: $color-theme
                padding: .35rem
                margin: .8rem auto .55rem
    .list-mask
        position: fixed
        top: 0
        left: 0
        width: 100%
        height: 100%
        z-index: 40
        opacity: 1
        background: $color-text-d
        &.fade-enter-active, &.fade-leave-active
            transition: all 0.5s
        &.fade-enter, &.fade-leave-to
            opacity: 0
</style>