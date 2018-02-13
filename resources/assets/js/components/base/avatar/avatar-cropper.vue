<template>
    <transition name="vertical-slide">
        <div class="cropper-wrapper">
            <dkf-header title="选择显示区域">
                <div slot="left" @click="cancel"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                <div slot="right" @click="save" style="padding-right: .3rem;">保存</div>
            </dkf-header>
            <div class="cropper">
                <img id="cropImage" width="100%" :src="image.url" ref="cropperImg">
            </div>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import 'cropperjs/dist/cropper.min.css'
  import Cropper from 'cropperjs'
  import dkfHeader from 'Base/header/header'

  export default {
    props: {
      image: {
        type: Object,
        default() {
          return {}
        }
      }
    },
    data() {
      return {
        cropper: {}
      }
    },
    mounted () {
      this.init()
    },
    methods: {
      // 初始化裁剪插件
      init () {
        let image = document.getElementById('cropImage')
        this.cropper = new Cropper(image, {
          aspectRatio: 10 / 7,
          viewMode: 3,
          autoCropArea: 1,
          movable: false,
          zoomable: false,
//          modal: false, // 在图像上方和裁切框下方显示黑色模式。
          guides: false // 在裁剪框上方显示虚线。
        })
      },
      cancel() {
        this.$emit('cancel')
      },
      save() {
        let formData = {
          'image': this.image,
          'data': this.cropper.getData()
        }
        this.$emit('save', formData)
      }
    },
    components: {
      dkfHeader,
      Cropper
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .cropper-wrapper
        @include allCover
        background-color: #ffffff
        .cropper
            margin-top: 1.5rem
            .cropper-drag-box
                opacity: 0.5
</style>