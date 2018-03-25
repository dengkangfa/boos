<template>
    <div id='main'>
        <!--固定的顶部-->
        <div class='main_fixed_top'>
            <div class='job_header'>
                <div class='job_header_left'>
                    <span>web前端</span>
                    <span>HTML5</span>
                </div>
                <div class='job_header_icon'>
                    <span><i class='iconfont icon-jiahao'></i></span>
                    <b></b>
                    <span><i class='iconfont icon-sousuo'></i></span>
                </div>
            </div>
            <div class='job_nav'>
                <ul class='flex_parent'>
                    <li class='flex_child' v-for='(nav,index) in navlist'
                        :class='{ selected: nav.isSelected }'
                        @click='changeColor(nav, index)'>
                        {{nav.title}}<span class='icon-down'></span>
                    </li>
                </ul>
                <keep-alive>
                    <slideTabComp v-show='navlist[0].isSelected' @change='typeChange' @hide='hide' v-if='slideIndex === 0' :slideTemp='slideTemp'></slideTabComp>
                    <!--<selectCityComp v-show='navlist[1].isSelected' @hide='hide' v-else-if='slideIndex==1'></selectCityComp>-->
                    <!--<compRequireComp v-show='navlist[2].isSelected' @hide='hide' v-else-if='slideIndex==2' :indexArr='indexArr[0]' :slideTemp='slideTemp'></compRequireComp>-->
                    <!--<compRequireComp v-show='navlist[3].isSelected' @hide='hide' v-else='slideIndex==3' :indexArr='indexArr[1]' :slideTemp='slideTemp'></compRequireComp>-->
                </keep-alive>
            </div>
            <!--列表-->
            <div class='job_content' id='jobcontent' ref='wrapper'>
                <scroll :data="jobs" @scrollTop="request" @scrollToEnd="requestMore" :pullDown="true" :pullUp="true" ref="scroll" class="job-lists-wrapper">
                    <ul class='page-infinite-list job_lists'>
                        <div v-show="loading" class="mint-spinner">
                            <mint-spinner color="#53CAC3" type="triple-bounce"></mint-spinner>
                        </div>
                        <li v-for='job in jobs'>
                            <router-link @click='' to='home'>
                                <h4>{{job.name}}<span>{{job.low_salary !== -1 ? job.low_salary + 'k-' + job.high_salary + 'k' : '面议'}}</span>
                                </h4>
                                <p class="company-info">{{job.company.data.abbreviation}} 不需要融资</p>
                                <p class="claim">
                                    <span class='icon-position'></span><span>{{job.con_place}}</span>
                                    <span class='icon-seniority'></span><span>{{job.seniority}}</span>
                                    <span class='icon-education'></span><span>{{job.education}}</span>
                                </p>
                                <p class='title'>
                                    <img :src='job.user.data.avatar'>
                                    <span>{{job.user.data.name}}・{{job.user.data.pos_name}}</span>
                                </p>
                            </router-link>
                        </li>
                        <div v-show='loadings' class='page-infinite-loading mint-spinner'>
                            <mint-spinner type='fading-circle'></mint-spinner>正在加载更多的数据...
                        </div>
                    </ul>
                </scroll>
            </div>
        </div>
    </div>
</template>

<script>
  import {jobList} from 'Api/job'
  import slideTabComp from './slideTabComp'
  import scroll from 'Base/scroll/scroll'
  import {ERR_OK, JOB_PREPAGE} from 'Api/config'

  export default {
    data() {
      return {
        navlist: [
          {
            title: '推荐',
            isSelected: false
          },
          {
            title: '上海',
            isSelected: false
          },
          {
            title: '公司',
            isSelected: false
          },
          {
            title: '要求',
            isSelected: false
          }
        ],
        jobs: [],
        slideIndex: -1,
        slideData: [
          [
            {
              name: '推荐',
              hadSelested: true
            },
            {
              name: '最新',
              hadSelested: false
            }
          ],
          // 城市选择这项数据太多没用父子通信传递数据
          [0, 1],
          [
            {
              title: '融资规模',
              isCheckbox: true,
              list:['全部','未融资','天使轮','A轮','B轮','C轮','D轮及以上','已上市','不需要融资']
            },
            {
              title:'团队规模',
              isCheckbox:true,
              list:['全部','0-20人','20-99人','100-499人','500-999人','1000-9999人','10000人以上']
            },
            {
              title:'行业',
              isCheckbox:true,
              list:['全部','非互联网行业','健康医疗','生活服务','旅游','金融','信息安全','广告营销','数据服务','智能硬件','文化娱乐','网络招聘',
                '分类信息','电子商务','移动互联网','企业服务','社交网络','教育培训','O2O','游戏','互联网','媒体','IT软件']
            }
          ],
          [
            {
              title:'最低学历',
              isCheckbox:true,
              list:['全部','中专及以下','高中','大专','本科','硕士','博士']
            },
            {
              title:'经验',
              isCheckbox:true,
              list:['全部','应届生','1年以内','1-3年','3-5年','5-10年','10年以上']
            },
            {
              title:'薪资(单选)',
              isCheckbox:false,
              list:['全部','3k以下','3k-5k','5k-10k','10k-20k','20k-50k','50k以上']
            },
          ]
        ],
        loading: false,
        loadings: false,
        page: 1,
        hasMore: true
      }
    },
    created() {
      this.request()
    },
    methods: {
      hide() {
        this.navlist.filter(value => {
          value.isSelected = false
        })
      },
      typeChange(value) {
        this.navlist[0].title = value
        this.hide()
      },
      changeColor(nav, index) {
        this.slideIndex = index
        this.slideTemp = this.slideData[index]
        if (nav.isSelected) {
          nav.isSelected = false
        } else {
          // 先清空所有选中效果
          this.navlist.filter(value => {
            value.isSelected = false
          })
          // 设置当前选中效果
          nav.isSelected = true
        }
      },
      request() {
        this.loading = true
        jobList().then(response => {
          this.loading = false
          this.jobs = response.data
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
        jobList(JOB_PREPAGE, this.page).then((res) => {
          this.loadings = false
          if (res.code === ERR_OK) {
            this.jobs = this.jobs.concat(res.data)
            this._checkMore(res.meta.pagination)
          }
        })
      },
      _checkMore(pagination) {
        if (this.page >= pagination.total_pages || this.page * JOB_PREPAGE >= pagination.total) {
          this.hasMore = false
        }
      }
    },
    components: {
      slideTabComp,
      scroll
    }
  }
</script>

<style lang='sass' rel='stylesheet/sass' scoped>
    @import '../../../sass/variables'
    @import '../../../sass/mixin'
    
    #main
        .main_fixed_top
            position: fixed
            width: 100%
            left: 0
            top: 0
            z-index: 100
            .job_header
                display: flex
                justify-content: space-between
                align-items: center
                padding: 0.37rem 0
                background: $color-theme
                .job_header_left
                    color: #FFF
                    padding-left: .4rem
                    font-size: .4rem
                    span+span
                        margin-left: .3rem
                .job_header_icon
                    margin-right: .3rem
                    position: relative
                    overflow: hidden
                    span
                        @include sc(.48rem,#fff)
                        margin-left: .6rem
                    b
                        display: block
                        position: absolute
                        width: 1px
                        height: .48rem
                        background: #fff
                        margin: 0 .15rem
                        top: 50%
                        transform: translateY(-50%)
                        left: 1.2rem
            .job_nav
                position: relative
                background: #fff
                @include border-bottom-1px($color-text-d)
                box-sizing: border-box
                ul
                    height: 1rem
                    line-height: 1rem
                    li
                        text-align: center
                        color: #8D8D8D
                        position: relative
                        &:after
                            display: table
                            content: ''
                            height: 60%
                            border-right: 1px solid #E5E5E5
                            position: absolute
                            right: 0
                            top: 50%
                            transform: translateY(-50%)
                        &.selected
                            color: $color-theme
            .job_content
                position: fixed
                top: 2.26rem
                bottom: 1.34rem
                width: 100%
                .job-lists-wrapper
                    height: 100%
                    overflow: hidden
                    .job_lists
                        overflow: hidden
                        li
                            padding: 3% 6%
                            background: #fff
                            &+li
                                margin-top: 3%
                            a
                                display: block
                                color: #808080
                                h4
                                    display: flex
                                    justify-content: space-between
                                    font-weight: bold
                                    @include sc(.4rem, $color-text-ll)
                                    span
                                        font-weight: 700
                                        color: $color-theme
                                p
                                    margin-top: .15rem
                                    [class^='icon-'], [class*=' icon-']
                                        margin-right: .1rem
                                        margin-left: .3rem
                                    span:nth-child(1)
                                        margin-left: 0
                                    &.claim
                                        color: $color-text-d
                                        padding: .15rem 0
                                    &.title
                                        display: flex
                                        span
                                            color: $color-text-l
                                            height: .8rem
                                            line-height: .8rem
                                            margin-left: .3rem
                                        img
                                            width: .8rem
                                            height: .8rem
                                            border-radius: 50%
                        .mint-spinner
                            height: 45px
                            line-height: 45px
                            text-align: center
                        .page-infinite-loading
                            display: flex
                            justify-content: center
                            align-items: center
                            color: $color-text-d
                            span
                                margin-right: 8px
</style>