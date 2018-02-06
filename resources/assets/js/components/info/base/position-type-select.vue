<template>
    <transition name="slide">
        <div class="position-type-select-wrapper" v-show="showFlag">
            <dkf-header title="选择职位类型">
                <div slot="left" @click="hide" fixed><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="search">
                <div class="search-label" :class="{'left': searchInputLeft}" @click="searchInputFocus"><i class="icon icon-sousuo" ref="searchIcon"></i> <span>{{ searchPlaceholder }}</span></div>
                <input id="searchInput" type="text" class="search-input" v-model="searchValue" @focus="searchInputFocusEvent" @blur="searchInputBlurEvent">
                <span class="search-icon" v-show="searchValue" @click="searchValue = ''"><i class="icon icon-circle-with-cross"></i></span>
            </div>
            <main>
                <div class="content">
                    <div class="position-type" @touchmove="searchInputBlur">
                        <div class="no-result" v-show="!searchArr.length && searchValue">暂无精确匹配职位，请在下方的类别中选择添加</div>
                        <ul class="industrys-list" v-show="!searchArr.length">
                            <li v-for="(item, index) in industrys" :class="{'active': item == currentIndustry}" @click="chooseIndustry(item, index)">{{ item }}</li>
                        </ul>
                        <ul class="search-result" v-show="searchArr.length && searchValue">
                            <li v-for="item in searchArr" @click="chooseSkill(item['skills'], item['index']), searchArr = [], searchValue = ''"><div><p class="title" v-html="searchResultFormat(item['skills'])"></p><p class="disc">{{ item['industrys'] + ' - ' + item['directions'] }}</p></div></li>
                        </ul>
                    </div>
                    <transition name="slide">
                        <div class="child-position-type" v-if="childPositionTypeShowFlag"  @touchmove.prevent>
                            <scroll :data="directions" :probeType="3" :listenScroll="true" style="width: 50%;" @scroll="searchInputBlur">
                                <ul>
                                    <li v-for="(item, index) in directions" :class="{'active': item == currentDirection}" @click="chooseDirection(item, index)">{{ item }}</li>
                                </ul>
                            </scroll>
                            <scroll :data="skills" :probeType="3" :listenScroll="true" style="width: 50%; background: #eeeeee" @scroll="searchInputBlur">
                                <ul>
                                    <li v-for="(item, index) in skills" :class="{'active': item == currentSkill}" @click="chooseSkill(item, index)">{{ item }}</li>
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
  import POSITION_TYPE from './positions'

  const DEFAULT_CODE = 999999
  // 正则无用字符
  const INVALID = /[°"§%()\[\]\*{}=\\?´`'#<>|,;.:+_-]+/g

  export default {
    data() {
      return {
        searchValue: '',
        showFlag: false,
        childPositionTypeShowFlag: false,
        searchInputLeft: false,
        searchPlaceholder: '搜索',
        industrys: '', // 行业
        directions: '', // 方向
        skills: '', // 技能
        currentIndustry: '',
        currentDirection: '',
        currentSkill: '',
        searchArr: []
      }
    },
    created() {
      this.industrys = this.getPosition()
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.childPositionTypeShowFlag = false
        this.showFlag = false
      },
      chooseIndustry(searchValue, index) {
        this.currentIndustry = searchValue
        this.getDirections(index)
        this.childPositionTypeShowFlag = true
      },
      chooseDirection(searchValue, index) {
        this.currentDirection = searchValue
        this.getSkills(index)
      },
      chooseSkill(searchValue, index) {
        this.$emit('selected', {'id': index, 'name': searchValue})
        this.hide()
      },
      hideChildPositionType() {
        this.childPositionTypeShowFlag = false
      },
      searchInputFocusEvent() {
        this.searchInputLeft = true
      },
      searchInputBlurEvent() {
        if (!this.searchValue) {
          this.searchInputLeft = false
        }
      },
      searchInputFocus() {
        document.querySelector('#searchInput').focus()
      },
      searchInputBlur() {
        document.querySelector('#searchInput').blur()
      },
      getDirections(index) {
        this.directions = this.getPosition(index)
        this.currentDirection = this.directions[Object.keys(this.directions)[0]]
        this.getSkills(Object.keys(this.directions)[0])
      },
      getSkills(index) {
        this.skills = this.getPosition(index)
      },
      getPosition(code = DEFAULT_CODE) {
        return POSITION_TYPE[code] || null
      },
      getCode(name) {
        for (let x in POSITION_TYPE) {
          for (let y in POSITION_TYPE[x]) {
            if (name === POSITION_TYPE[x][y]) {
              return y
            }
          }
        }
      },
      search(name) {
        if (!name) {
          return []
        }
        let searchArr = []
        for (let x in POSITION_TYPE) {
          for (let y in POSITION_TYPE[x]) {
            if (x % 10000 !== 0 && x != DEFAULT_CODE && POSITION_TYPE[x][y].search(new RegExp(name, 'i')) > -1) {
              searchArr.push({'index': y, 'skills': POSITION_TYPE[x][y], 'directions': POSITION_TYPE[x.slice(0, 2) + '0000'][x], 'industrys': POSITION_TYPE[DEFAULT_CODE][x.slice(0, 2) + '0000']})
            }
          }
        }
        return searchArr
      },
      searchResultFormat(searchResultItem) {
        let searchValue = this.searchValue.replace(INVALID, '')
        return searchResultItem.replace(new RegExp(searchValue, 'i'), '<span style="color: #53CAC3">' + searchResultItem.substr(searchResultItem.search(new RegExp(searchValue, 'i')), searchValue.length) + '</span>')
      }
    },
    components: {
      dkfHeader,
      scroll
    },
    watch: {
      searchValue() {
        if (this.searchValue) {
          this.searchPlaceholder = ''
          clearTimeout(this.timer)
          this.timer = setTimeout(() => {
            this.searchArr = this.search(this.searchValue.replace(INVALID, ''))
          }, 200)
        } else {
          this.searchPlaceholder = '搜索'
          this.searchArr = []
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
                padding: 5px 35px 5px 28px
            .search-icon
                @include ct()
                right: 10px
                padding: 5px
                font-size: .4rem
                color: #999
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
                    div.no-result
                        height: 50px
                        line-height: 50px
                        color: $color-text-l
                        font-size: .33rem
                        text-align: center
                        background: #fff3ad
                    li
                        height: 50px
                        color: $color-text-l
                        @include border-bottom-1px($color-text-d)
                        box-sizing: border-box
                        &.active
                            color: $color-theme
                    .industrys-list
                        padding: 0 20px 0
                        li
                            line-height: 50px
                    .search-result
                        padding: 0 10px 0
                        li:last-child
                            border-bottom: none
                        li
                            display: flex
                            align-items: center
                            p.disc
                                font-size: .3rem
                                padding-top: 5px
                .child-position-type
                    position: fixed
                    display: flex
                    top: 95px
                    right: 0
                    bottom: 0
                    width: 80%
                    z-index: 50
                    background: #ffffff
                    overflow: hidden
                    ul
                        text-align: center
                        padding: 0 20px 0
                        li
                            height: 50px
                            display: flex
                            justify-content: center
                            align-items: center
                            color: $color-text-l
                            &.active
                                color: $color-theme
                .list-mask
                    position: fixed
                    top: 95px
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