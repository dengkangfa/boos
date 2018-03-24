<template>
    <transition name="horizontal-slide">
        <div v-if="showFlag" class="my-company-wrapper">
            <dkf-header title="输入公司全称">
                <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                <div slot="right" style="color: #cccccc" v-if="currentValue.length < 5">下一步</div>
                <div slot="right" @click="searchCompany(currentValue)" v-else>下一步</div>
            </dkf-header>
            <div class="main">
                <div class="input-wrapper">
                    <input v-model="currentValue" @input="search" type="text" class="company-name-input" v-focus>
                    <div class="work-count-wrapper"><span class="work-count" :class="{'exceed': isExceed}">{{ currentValue.length }}</span>/{{ maxLength }}</div>
                </div>
                <div class="prompt" v-if="!searchResult.length">
                    <div class="media">
                        <img class="media-figure" src="./bulb.jpg" width="40" alt="">
                        <div class="media-body">
                            <p style="font-size: .35rem;line-height: .5rem;"><span style="color: #bdbdbd">公司全称是 </span>营业执照名称 / 劳动合同的雇主名称 / 企业报销发票抬头</p>
                            <p style="padding: 25px 0; color: #bdbdbd">示例: 北京华品博睿网络技术有限公司</p>
                        </div>
                    </div>
                </div>
                <div class="search-result" v-else>
                    <ul class="cell">
                        <li v-for="item in searchResult">
                            <div class="cell-title" @click="searchCompany(item.name)" v-html="searchResultFormat(item.name)"></div>
                        </li>
                    </ul>
                </div>
            </div>
            <create-company :companyName="currentValue" @hide="hide" ref="createCompany"></create-company>
            <select-company :companies="companies" @hide="hide" ref="selectCompany"></select-company>
            <spinner text="加载中" v-show="spinnerShowFlag"></spinner>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import createCompany from './create-company'
  import selectCompany from './select-company'
  import {search, searchByName} from 'Api/company.js'
  import spinner from 'Base/spinner/spinner'

  // 正则无用字符
  const INVALID = /[°"§%()\[\]\*{}=\\?´`'#<>|,;.:+_-]+/g

  export default {
    props: {
      value: {
        type: String,
        default: '1'
      },
      maxLength: {
        type: Number,
        default: 10
      }
    },
    data() {
      return {
        showFlag: false,
        currentValue: '',
        searchResult: [],
        companies: [],
        spinnerShowFlag: false
      }
    },
    directives: {
      focus: {
        inserted (el) {
          setTimeout(() => {
            el.focus()
          }, 350)
        }
      }
    },
    computed: {
      isExceed() {
        return this.currentValue.length > this.maxLength
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      search() {
        clearTimeout(this.t)
        this.t = setTimeout(() => {
          if (this.currentValue !== '') {
            searchByName(this.currentValue).then(response => {
              this.searchResult = response.data
            })
          } else {
            this.searchResult = []
          }
        }, 200)
      },
      searchResultFormat(searchResultItem) {
        let searchValue = this.currentValue.replace(INVALID, '')
        return searchResultItem.replace(new RegExp(searchValue, 'i'), '<span style="color: #53CAC3">' + searchResultItem.substr(searchResultItem.search(new RegExp(searchValue, 'i')), searchValue.length) + '</span>')
      },
      searchCompany(wk) {
        this.spinnerShowFlag = true
        let searchField = {'name': wk}
        search(searchField).then(response => {
          this.spinnerShowFlag = false
          this.companies = response.data
          if (this.companies.length) {
            this.$refs.selectCompany.show()
          } else {
            this.$refs.createCompany.show()
          }
        })
      }
    },
    components: {
      dkfHeader,
      createCompany,
      selectCompany,
      spinner
    },
    watch: {
      value(newValue) {
        this.currentValue = newValue
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../../sass/variables"
    @import "../../../../../sass/mixin"

    .my-company-wrapper
        @include allCover
        background: $bc
        .input-wrapper
            position: relative
            margin-top: 20px
            .company-name-input
                width: 100%
                padding: 15px 50px 15px 10px
            .work-count-wrapper
                @include ct
                right: 0
                width: 40px
                text-align: right
                padding-right: 10px
                .work-count
                    color: $color-theme
                    &.exceed
                        color: red
        .prompt
            background: #f8f5fa
            margin: 20px 10px
            border-radius: 5px
            &:before
                position: absolute
                display: block
                width: 0
                height: 0
                border: 10px solid transparent
                pointer-events: none
                content: ""
                border-bottom-color: #f8f5fa
                transform: translate3d(15px, -100%, 0)
            .media
                display: flex
                align-items: flex-start
                img
                    margin-top: 5px
                .media-body
                    flex: 1
                    padding: 10px 20px 0 0
        .search-result
            li:first-child
                @include border-top-1px($color-text-d)
</style>