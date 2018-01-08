<template>
    <transition name="slide">
        <div class="position-type-select-wrapper" v-show="showFlag">
            <dkf-header title="选择职位类型" @left="hide" class="header-wrapper"></dkf-header>
            <div class="search">
                <div class="search-label" :class="{'left': searchInputLeft}" @click="searchInputFocus"><i class="icon icon-sousuo" ref="searchIcon"></i> <span>{{ searchPlaceholder }}</span></div>
                <input id="searchInput" type="text" class="search-input" v-model="value" @focus="searchInputFocusEvent" @blur="searchInputBlurEvent">
            </div>
            <main>
                <div class="content">
                    <div class="position-type" @touchmove="searchInputBlur">
                        <ul>
                            <li class="active" @click="showChildPositionType">技术</li>
                            <li>产品</li>
                            <li>设计</li>
                            <li>运营</li>
                            <li>市场</li>
                            <li>职能/高级管理</li>
                            <li>销售</li>
                            <li>传媒</li>
                            <li>金融</li>
                            <li>汽车</li>
                            <li>教育培训</li>
                            <li>医疗健康</li>
                            <li>采购/贸易</li>
                            <li>供应链/物流</li>
                            <li>房地产/建筑</li>
                            <li>咨询/翻译/法律</li>
                            <li>实习生/管培生</li>
                            <li>旅游</li>
                            <li>酒店/餐饮/零售</li>
                            <li>生产制造</li>
                            <li>其他</li>
                        </ul>
                    </div>
                    <transition name="slide">
                        <div class="child-position-type" v-if="childPositionTypeShowFlag"  @touchmove.prevent>
                            <scroll :probeType="3" :listenScroll="true" style="width: 50%;" @scroll="searchInputBlur">
                                <ul>
                                    <li>后端开发</li>
                                    <li>移动开发</li>
                                    <li>前端开发</li>
                                    <li>测试</li>
                                    <li>运维/技术支持</li>
                                    <li>项目管理</li>
                                    <li>数据</li>
                                    <li>硬件开发</li>
                                    <li>高端技术职位</li>
                                    <li>其他技术职位</li>
                                    <li>软件销售支持</li>
                                    <li>人工智能</li>
                                    <li>通信</li>
                                </ul>
                            </scroll>
                            <scroll :probeType="3" :listenScroll="true" style="width: 50%; background: #eeeeee" @scroll="searchInputBlur">
                                <ul>
                                    <li>后端开发</li>
                                    <li>移动开发</li>
                                    <li>前端开发</li>
                                    <li>测试</li>
                                    <li>运维/技术支持</li>
                                    <li>项目管理</li>
                                    <li>数据</li>
                                    <li>硬件开发</li>
                                    <li>高端技术职位</li>
                                    <li>其他技术职位</li>
                                    <li>软件销售支持</li>
                                    <li>人工智能</li>
                                    <li>通信</li>
                                </ul>
                            </scroll>
                        </div>
                    </transition>
                    <transition name="fade">
                        <div class="list-mask" @click="hideChildPositionType" v-show="childPositionTypeShowFlag" @touchmove.prevent></div>
                    </transition>
                </div>
            </main>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import scroll from 'Base/scroll/scroll'

  export default {
    data() {
      return {
        value: '',
        showFlag: false,
        childPositionTypeShowFlag: false,
        searchInputLeft: false,
        searchPlaceholder: '搜索'
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      showChildPositionType() {
        this.childPositionTypeShowFlag = true
      },
      hideChildPositionType() {
        this.childPositionTypeShowFlag = false
      },
      searchInputFocusEvent() {
        this.searchInputLeft = true
      },
      searchInputBlurEvent() {
        if (!this.value) {
          this.searchInputLeft = false
        }
      },
      searchInputFocus() {
        document.querySelector('#searchInput').focus()
      },
      searchInputBlur() {
        document.querySelector('#searchInput').blur()
      }
    },
    components: {
      dkfHeader,
      scroll
    },
    watch: {
      value() {
        if (this.value) {
          this.searchPlaceholder = ''
        } else {
          this.searchPlaceholder = '搜索'
        }
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .position-type-select-wrapper
        @include allCover
        background: #ffffff
        .header-wrapper
            position: fixed
            width: 100%
            height: 50px
            right: 0
            top: 0
        .search
            position: fixed
            top: 50px
            width: 100%
            @include border-bottom-1px($bc)
            box-sizing: border-box
            padding: 8px
            .search-label
                @include ct() // 垂直局中
                /*left: 15px*/
                width: 50px
                color: #999
                left: 50%
                transform: translate(-50%, -50%)
                transition: all .3s
                &.left
                    left: 15px
                    transform: translate(0, -50%)
                .icon
                    padding-left: 5px
            .search-input
                width: 100%
                height: 28px
                line-height: 28px
                background: #eeeeee
                border-radius: .1rem
                padding: 5px 25px 5px 28px
        main
            position: absolute
            top: 95px
            bottom: 0
            width: 100%
            overflow-y: auto
            -webkit-overflow-scrolling: touch
            .content
                height: 100%
                .position-type
                    ul
                        padding: 0 20px 0
                    li
                        height: 50px
                        line-height: 50px
                        color: $color-text-l
                        @include border-bottom-1px($color-text-d)
                        box-sizing: border-box
                        &.active
                            color: $color-theme
                .child-position-type
                    position: absolute
                    display: flex
                    top: 0
                    right: 0
                    bottom: 0
                    width: 80%
                    height: 100%
                    z-index: 50
                    background: #ffffff
                    overflow: hidden
                    ul
                        text-align: center
                        li
                            height: 50px
                            line-height: 50px
                            color: $color-text-l
                        &.active
                            color: $color-theme
                .list-mask
                    position: absolute
                    width: 100%
                    height: 100%
                    z-index: 40
                    opacity: 1
                    background: $color-text-ll
                    &.fade-enter-active, &.fade-leave-active
                        transition: all 0.5s
                    &.fade-enter, &.fade-leave-to
                        opacity: 0


    .slide-enter-active, .slide-leave-active
        transition: all .3s
    .slide-enter, .slide-leave-to
        transform: translate3d(100%, 0, 0)
</style>