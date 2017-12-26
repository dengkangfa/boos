<template>
        <div>
            <transition name="slide">
                <div class="avatar-driver" v-show="showFlag">
                    <div class="avatar-driver-items">
                        <div><span class="icon-wrapper" @click="invokingCarera"><i class="icon-camera-outline"></i></span> <span class="desc">拍摄照片</span></div>
                        <div><span class="icon-wrapper"><i class="icon-image"></i></span><span class="desc">相册照片</span></div>
                        <div><span class="icon-wrapper"><i class="icon-portrait"></i></span><span class="desc">默认头像</span></div>
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
      invokingCarera() {
        var p = navigator.mediaDevices.getUserMedia({ audio: true, video: true })

        p.then(function(mediaStream) {
          var video = document.querySelector('video');
          video.src = window.URL.createObjectURL(mediaStream);
          video.onloadedmetadata = function(e) {
            // Do something with the video here.
          };
        });

        p.catch(function(err) { console.log(err.name); }); // always check for errors at the end.
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass">
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