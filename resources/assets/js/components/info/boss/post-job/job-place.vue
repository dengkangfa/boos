<template>
    <transition name="horizontal-slide">
        <div class="dispicker-wrapper" is-fixed>
            <dkf-header title="工作地点">
                <div slot="left" @click="$emit('hide')"><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="main" ref="main">
                <div class="form" ref="form">
                    <div class="province">
                        <span class="name">城市</span>
                        <div class="value" @click="$refs.distPicker.show()"><span :class="{'color-d': currentCity}">{{ currentCity ? currentCity : '请选择' }}</span><i class="icon icon-right"></i></div>
                    </div>
                    <div class="area" v-show="currentCity" ref="searchBoxWrapper">
                        <div class="search-box">
                            <div class="input-group-wrapper first-input-group">
                                <label>街道/写字楼</label>
                                <div class="input-group">
                                    <input type="text" v-model="front_road" @keyup.13="getCurrentPosition" @input="keyUpSearch" @focus="roadCloseIconShow = true, top = true, roadInputBlur = false" @blur="roadCloseIconShow = false, top = false" placeholder="请输入办公大楼,例:国际大厦" v-blur="roadInputBlur">
                                    <i @click="front_road = ''" class="icon icon-circle-with-cross" v-show="roadCloseIconShow && front_road"></i>
                                </div>
                            </div>
                            <div class="search-results-wrapper" v-show="front_road && roadCloseIconShow && searchData.length" ref="searchResultsWrapper">
                                <ul class="search-results">
                                    <li @click="locate(item.location, item.name)" v-for="item in searchData">
                                        <p><i class="icon icon-position"></i><span>{{ item.name }}</span></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--<div class="line"></div>-->
                        <div class="input-group-wrapper">
                            <label>门牌(选填)</label>
                            <div class="input-group">
                                <input type="text" v-model="front_detial" @focus="detialCloseIconShow = true, top = true" @blur="detialCloseIconShow = false, top = false" placeholder="请输入楼号/门牌号等,例:3楼512室">
                                <i @click="front_detial = ''" class="icon icon-circle-with-cross" v-show="detialCloseIconShow && front_detial"></i>
                            </div>
                        </div>
                    </div>
                    <div class="location" v-show="address && location_status >= 0">
                        <div class="map">
                            <span>你的地址将对牛人展示为:</span>
                            <span class="color-theme" @click="showMap">{{ location_status ? '无法定位' : '地图' }} <i class="icon icon-position"></i></span>
                        </div>
                        <p class="name">{{ address }}</p>
                    </div>
                </div>
                <div @click="confirm" class="dispicker-footer" :class="{'bc-theme': location_status === 0}">
                    <span>确定</span>
                </div>
            </div>
            <dist-picker title="请选择该职位的工作城市" :province="province" :city="city" :area="area" @selected="distPickerSelected" :depth="3" ref="distPicker"></dist-picker>
            <v-map :address="address" :location="location" ref="map"></v-map>
            <spinner text="加载中" v-show="spinnerShowFlag"></spinner>
            <message message="街道/写字楼不能为空" ref="message"></message>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import distPicker from 'Base/picker/distpicker'
  import {prefixStyle} from 'Helpers/dom'
  import vMap from './map'
  import message from 'Base/message/message'
  import spinner from 'Base/spinner/spinner'

  const TRANSFORM = prefixStyle('transform')

  export default {
    data() {
      return {
        province: '',
        city: '',
        area: '',
        front_road: '',
        front_detial: '',
        currentCity: '',
        top: false,
        searchData: [],
        location: [],
        location_status: -1,
        roadInputBlur: false,
        roadCloseIconShow: false,
        detialCloseIconShow: false,
        spinnerShowFlag: false
      }
    },
    directives: {
      blur: {
        update: function (el, {value}) {
          if (value) {
            el.blur()
          }
        }
      }
    },
    created() {
      // 定位当前位置
      let self = this
      new AMap.Geolocation().getCurrentPosition((status, result) => {
        if (result && result.position) {
          var geocoder = new AMap.Geocoder({
            radius: 1000,
            extensions: 'all'
          })
          geocoder.getAddress([result.position.lng, result.position.lat], function (status, result) {
            if (status === 'complete' && result.info === 'OK') {
              if (result && result.regeocode) {
                self.province = result.regeocode.addressComponent.province
                self.city = result.regeocode.addressComponent.city
                self.area = result.regeocode.addressComponent.district
                self.$nextTick(() => {
                  self.$refs.distPicker.show()
                })
              }
            }
          })
        }
      })
    },
    computed: {
      address() {
        if (this.currentCity && this.front_road) {
          return this.currentCity + this.front_road + this.front_detial
        }
        return ''
      }
    },
    methods: {
      showMap() {
        if (!this.front_road) {
          this.$refs.message.show()
          return
        }
        if (this.location_status === 0) {
          this.$refs.map.show()
        }
      },
      distPickerSelected(values) {
        this.currentCity = values[1] + values[2]
      },
      locate(location, name) {
        this.location = [location.lat, location.lng]
        this.front_road = name
        this.location_status = 0
        this.searchData = []
        this.top = false
      },
      // 获取当前位置经纬度
      getCurrentPosition() {
        let self = this
        this.searchData = []
        this.roadInputBlur = true
        this.spinnerShowFlag = true
        var geocoder = new AMap.Geocoder({
          radius: 1000,
          extensions: 'all'
        })
        geocoder.getLocation(this.front_road, function(status, result) {
          self.spinnerShowFlag = false
          if (status === 'complete' && result.info === 'OK') {
            let locationTemp = result.geocodes[0].location
            self.location = [locationTemp.lat, locationTemp.lng]
            self.location_status = 0
          } else {
            self.location_status = 1
            self.location = []
          }
        })
      },
      keyUpSearch () {
        clearTimeout(this.t)
        this.t = setTimeout(() => {
          this.search()
        }, 200)
      },
      search() {
        let _this = this
        AMap.service(['AMap.PlaceSearch'], function () {
          let placeSearch = new AMap.PlaceSearch({
            pageSize: 20, pageIndex: 1, city: _this.city, cityLimit: true
          })
          placeSearch.search(_this.front_road, function (status, result) {
            if (status == 'complete' && result.info == 'OK') {
              _this.searchData = result.poiList.pois
            }
          })
        })
      },
      confirm() {
        if (this.location_status !== 0) {
          return
        }
        this.$emit('save', this.address)
      }
    },
    components: {
      dkfHeader,
      distPicker,
      message,
      vMap,
      spinner
    },
    watch: {
      top(newValue) {
        if (newValue && (this.roadCloseIconShow | this.detialCloseIconShow)) {
          this.$refs.form.style.transition = 'all .3s'
          this.$refs.form.style[TRANSFORM] = `translate3d(0, -75px, 0)`
        } else {
          this.$refs.form.style.transition = 'all .3s'
          this.$refs.form.style[TRANSFORM] = 'translate3d(0, 0, 0)'
        }
      },
      roadCloseIconShow() {
        this.$refs.searchResultsWrapper.style.height = `${document.documentElement.clientHeight - 135}px`
      }
    }
}
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../../sass/variables"
    @import "../../../../../sass/mixin"

    .dispicker-wrapper
        @include allCover
        background: $bc
        .main
            .province
                display: flex
                justify-content: space-between
                align-items: center
                font-size: .4rem
                background: #fff
                padding: 15px
                margin: 15px 0
                .value
                    color: $color-theme
                    span
                        margin-right: 5px
                        &.color-d
                            color: $color-text-d
                    .icon
                        font-size: .3rem
            .area
                background: #ffffff
                div.line
                    margin: 0 15px
                    @include border-bottom-1px($color-text-d)
                .search-box
                    position: relative
                    .search-results-wrapper
                        position: absolute
                        top: 100%
                        width: 100%
                        overflow-y: scroll
                        background: #ffffff
                        .search-results
                            padding-left: 15px
                            li
                                @include border-bottom-1px($color-text-d)
                                padding: 15px 0
                                .icon
                                    color: $color-theme
                                    padding-right: 5px
                .input-group-wrapper
                    display: flex
                    padding: 15px
                    &.first-input-group
                        @include border-bottom-1px($color-text-d)
                        padding: 15px 0
                        margin: 0 15px
                    label
                        font-size: .4rem
                        display: inline-block
                        width: 90px
                    .input-group
                        display: flex
                        align-items: center
                        flex: 1
                        input
                            flex: 1
                        .icon
                            color: $color-text-d
                            margin-left: 10px
            .location
                line-height: .6rem
                background: #fff
                border-radius: .1rem
                padding: 10px
                margin: 10px
                .map
                    display: flex
                    justify-content: space-between
                    .color-theme
                        color: $color-theme
                .name
                    line-height: .45rem
                    color: $color-text-d
            .dispicker-footer
                position: absolute
                bottom: 0
                width: 100%
                height: 40px
                line-height: 40px
                color: #ffffff
                background: $color-text-d
                text-align: center
                &.bc-theme
                    background: $color-theme

</style>