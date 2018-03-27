<template>
    <transition name="horizontal-slide">
        <div class="map-wrapper" v-if="showFlag">
            <dkf-header title="确定工作地点">
                <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="main">
                <el-amap vid="amap" :center="location" :zooms="zooms">
                    <el-amap-marker  :position="location"></el-amap-marker>
                </el-amap>
            </div>
            <div class="map-footer">
                <p class="prompt">地图由高德地图提供，暂不可修改</p>
                <div class="address"><span>{{ address }}</span></div>
            </div>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'

  export default {
    props: {
      address: {
        type: String,
        required: true
      },
      location: {
        type: Array,
        default: null,
        required: true
      }
    },
    data() {
      return {
        showFlag: false,
        zooms: [12, 18]
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

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../../sass/variables"
    @import "../../../../../sass/mixin"

    .map-wrapper
        @include allCover
        display: flex
        flex-flow: column nowrap
        background: $bc
        .main
            height: 100%
            flex: 1 1 auto
            overflow: auto
        .map-footer
            position: fixed
            bottom: 0
            left: 0
            right: 0
            z-index: 170
            font-size: .35rem
            background: #ffffff
            padding: 10px
            .prompt
                color: $color-text-l
                margin-top: 8px
            .address
                color: $color-text-d
                background: #eee
                border-radius: .1rem
                padding: 10px
                margin-top: 20px
</style>