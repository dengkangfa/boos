<template>
    <transition name="horizontal-slide">
        <div class="map-wrapper" v-if="showFlag">
            <dkf-header title="确定工作地点">
                <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="main">
                <el-amap vid="amap" :center="locationArr" :zooms="zooms" v-if="locationArr.length === 2">
                    <el-amap-marker  :position="locationArr"></el-amap-marker>
                </el-amap>
            </div>
            <div class="map-footer">
                <p class="prompt">{{ edit ? '当前地址' : '地图由高德地图提供，暂不可修改' }}</p>
                <div class="address" :class="{'edit': edit}"><span>{{ address }}</span></div>
                <div class="button-group" v-if="edit">
                    <div class="edit-button">编辑</div>
                    <div class="update-button" @click="jobPlaceShowFlag = true">更换</div>
                </div>
            </div>
            <job-place v-if="jobPlaceShowFlag"></job-place>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import jobPlace from './job-place'

  export default {
    props: {
      address: {
        type: String,
        required: true
      },
      location: {
        type: Array,
        default: null
      },
      edit: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        showFlag: false,
        zooms: [12, 18],
        locationArr: [],
        jobPlaceShowFlag: false
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
      dkfHeader,
      jobPlace
    },
    watch: {
      location(newValue) {
        this.locationArr = newValue
      },
      showFlag(newValue) {
        if (newValue && this.edit) {
          let self = this
          var geocoder = new AMap.Geocoder({
            radius: 1000,
            extensions: 'all'
          })
          geocoder.getLocation(this.address, function(status, result) {
            if (status === 'complete' && result.info === 'OK') {
              let locationTemp = result.geocodes[0].location
              self.locationArr = [locationTemp.lng, locationTemp.lat]
            } else {
              self.locationArr = []
            }
          })
        }
      }
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
                &.edit
                    padding: 20px 10px
            .button-group
                display: flex
                justify-content: space-between
                div
                    flex: 1
                    height: 40px
                    line-height: 40px
                    font-size: 0.45rem
                    text-align: center
                    background: $color-theme
                    border-radius: 0.15rem
                    color: $color-text
                    margin-top: 10px
                    box-sizing: border-box
                    &:first-child
                        margin-right: 10px
                .edit-button
                    background: $color-text
                    color: $color-theme
                    border: 1px solid $color-theme

</style>