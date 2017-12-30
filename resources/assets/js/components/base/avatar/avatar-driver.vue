<template>
        <div>
            <transition name="slide">
                <div class="avatar-driver" v-show="showFlag">
                    <div class="avatar-driver-items">
                        <div>
                            <a href="javascript:;" class="file" @click="hide()">
                                <span class="icon-wrapper"><i class="icon-camera-outline"></i></span>
                                <input type="file" accept="image/*" capture="camera" @change="uploadChange">
                            </a>
                            <span class="desc">拍摄照片</span>
                        </div>
                        <div>
                            <a href="javascript:;" class="file" @click="hide()">
                                <span class="icon-wrapper"><i class="icon-image"></i></span>
                                <input type="file" accept="image/*" @change="uploadChange" multiple>
                            </a>
                            <span class="desc">相册照片</span>
                        </div>
                        <div><span class="icon-wrapper" @click="showDefaultAvatarDriver"><i class="icon-portrait"></i></span><span class="desc">默认头像</span></div>
                    </div>
                    <div class="avatar-driver-cancel" @click="hide">取消</div>
                </div>
            </transition>
            <transition name="fade">
                <div class="list-mask" @click="hide" v-show="showFlag"></div>
            </transition>
        </div>
</template>

<script type="text/ecmascript-6">
  import axios from 'axios'

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
      clickCamera() {
        return this.$refs['file'].click()
      },
      uploadChange(e) {
        let files = e.target.files
        let formData = new FormData()

        formData.append('file', files[0])

        axios.post('api/user/avatar', formData).then((response) => {
          this.$emit('succeed', response.data)
        })
      },
      showDefaultAvatarDriver() {
        this.hide()
        this.$emit('showDefaultAvatarDriver')
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .avatar-driver
        position: fixed
        left: 0
        bottom: 0
        z-index: 50
        width: 100%
        background: #ffffff
        .avatar-driver-items
            display: flex
            justify-content: space-around
            padding-top: 0.7rem
            margin-bottom: 0.5rem
            span
                display: block
            .file
                position: relative
                display: block
                width: 50px
                height: 50px
                color: #000000
                span
                    position: absolute
                    display: block
                    top: 0
                    left: 0
                    right: 0
                input
                    position: absolute
                    left: 0
                    right: 0
                    top: 0
                    width: 50px
                    height: 50px
                    opacity: 0
            .icon-wrapper
                @include border-1px(#000000)
                border-radius: 50%
                font-size: 0.7rem
                padding: 0.3rem
            .desc
                margin-top: 0.3rem
        .avatar-driver-cancel
            width: 100%
            @include border-top-1px(#000000)
            text-align: center
            font-size: 0.4rem
            padding: 0.5rem 0

        &.slide-enter-active, &.slide-leave-active
            transition: all 0.3s
        &.slide-enter, &.slide-leave-to
            transform: translate3d(0, 100%, 0)

    .list-mask
        position: fixed
        top: 0
        left: 0
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