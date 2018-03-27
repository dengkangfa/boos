<template>
    <div class="company-list">
        <div class="top">
            <div class="head">
                <h3>公司</h3>
                <div class="icon"><span class="icon-sousuo"></span></div>
            </div>
            <div class="head-tab"  ref="wrapper">
                <ul class="clear">
                    <li v-for="(item,index) in titleData" @click="">{{item.title}}<span class="icon-down"></span></li>
                </ul>
            </div>
        </div>
        <div class="content">
            <scroll :data="companies" @scrollTop="request" @scrollToEnd="requestMore" :pullDown="true" :pullUp="true" ref="scroll" class="scroll-wrapper">
                <ul>
                    <div v-show="loading" class="mint-spinner">
                        <mint-spinner color="#53CAC3" type="triple-bounce"></mint-spinner>
                    </div>
                    <router-link tag="li" v-for="item in companies" :key="item.id" :to="{path : /comdetail/+item.id}">
                        <div class="company-info-wrapper">
                            <div class="media">
                                <img class="media-figure" src="images/company.jpg" width="40" alt="">
                                <div class="media-body">
                                    <div class="company">
                                        <span class="name">{{item.name}}</span>
                                        <p class="address">广州市天河区林和</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tag">
                                <span>A轮</span>
                                <span>{{ item.people }}</span>
                                <span>{{ item.industry_str }}</span>
                            </div>
                        </div>
                        <div class="option-info">
                            <div>热招: <span class="theme-color">技术培训生</span> 等112个职位</div>
                            <span><i class="icon-right"></i></span>
                        </div>
                    </router-link>
                    <div v-show='loadings' class='page-infinite-loading mint-spinner'>
                        <mint-spinner type='fading-circle'></mint-spinner>正在加载更多的数据...
                    </div>
                </ul>
                <div v-show="!hasMore && !companies.length" class="no-result-wrapper">
                    <no-result title="抱歉，暂无搜索结果"></no-result>
                </div>
            </scroll>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
  import scroll from 'Base/scroll/scroll'
  import noResult from 'Base/no-result/no-result'
  import {companyList} from 'Api/company'
  import {ERR_OK, PERPAGE} from 'Api/config'

  export default {
    data() {
      return {
        titleData: [
          {'title': '融资'},
          {'title': '规模'},
          {'title': '行业'}
        ],
        people: ['0-20人', '20-99人', '100-499人', '500-900人', '1000-9999人', '10000人以上'],
        loading: false,
        loadings: false,
        page: 1,
        hasMore: true,
        companies: []
      }
    },
    created() {
      this.request()
    },
    methods: {
      request() {
        this.loading = true
        companyList().then(response => {
          this.loading = false
          this.companies = response.data
          this.$nextTick(() => {
            this.$refs.scroll.refresh()
          })
        }).catch(() => {
          this.loading = false
        })
      },
      requestMore() {
        if (!this.hasMore) {
          return
        }
        this.page++
        this.loadings = true
        companyList(PERPAGE, this.page).then((res) => {
          this.loadings = false
          if (res.code === ERR_OK) {
            this.companies = this.companies.concat(res.data)
            this._checkMore(res.meta.pagination)
          }
        })
      },
      _checkMore(pagination) {
        if (this.page >= pagination.total_pages || this.page * PERPAGE >= pagination.total) {
          this.hasMore = false
        }
      }
    },
    components: {
      scroll,
      noResult
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import '../../../sass/variables'
    @import '../../../sass/mixin'

    .company-list
        .top
            position: fixed
            top: 0
            width: 100%
            @include border-bottom-1px($color-text-d)
            .head
                position: relative
                font-size: 0.48rem
                padding: 0.37rem 0
                background: $color-theme
                text-align: center
                color: #FFFFFF
                .icon
                    position: absolute
                    right: 10px
                    @include ct()
            .head-tab
                background: #fff
                color: #8D8D8D
                position: relative
                ul
                    list-style: none
                    padding: 0.37037rem 0
                    li
                        float: left
                        width: 33.3%
                        box-sizing: border-box
                        text-align: center
                        border-right: 1px solid #E5E5E5
                        span
                            vertical-align: middle
        .content
            position: fixed
            top: 2.32rem
            width: 100%
            bottom: 1.30rem
            .scroll-wrapper
                height: 100%
                overflow: hidden
                li
                    background: #fff
                    padding: 0 6%
                    margin-bottom: 5px
                    .company-info-wrapper
                        background: #fff
                        @include border-bottom-1px($color-text-d)
                        padding: 20px 0
                        .media
                            display: flex
                            align-items: center
                            margin-bottom: 20px
                            img
                                margin-right: 10px
                            .media-body
                                display: flex
                                justify-content: space-between
                                align-items: center
                                flex: 1
                                line-height: .5rem
                                .company
                                    .name
                                        font-weight: bold
                                        @include sc(.4rem, $color-text-ll)
                                    .address
                                        color: $color-text-l
                        .tag
                            span
                                color: $color-text-d
                                background: rgba(216, 216, 216, 0.1)
                                border-radius: .1rem
                                padding: 2px 5px
                                margin-right: 10px
                    .option-info
                        display: flex
                        justify-content: space-between
                        font-size: .3rem
                        color: $color-text-d
                        padding: 20px 0
                        .theme-color
                            color: $color-theme
                .mint-spinner
                    height: 45px
                    line-height: 45px
                    text-align: center
                    &.page-infinite-loading
                        display: flex
                        justify-content: center
                        align-items: center
                        color: $color-text-d
                        span
                            margin-right: 8px
                .no-result-wrapper
                    position: absolute
                    width: 100%
                    top: 50%
                    transform: translateY(-50%)
</style>