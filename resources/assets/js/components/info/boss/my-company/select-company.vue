<template>
    <transition name="horizontal-slide">
        <div class="select-company-wrapper" v-if="showFlag">
            <dkf-header title="选择公司" class="header">
                <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="main">
                <div class="media-wrapper">
                    <div class="media" v-for="company in companies" @click="currentCompany = company, $refs.joinCompany.show()">
                        <img class="pic" :src="company.pic ? company.pic : '/images/company.jpg'">
                        <div class="media-body">
                            <h3>{{ company.abbreviation }} | {{ company.industry_str }}</h3>
                            <p class="company-name">{{ company.name }}</p>
                            <div class="boss-list"><img :src="item.avatar" width="24" height="24" class="avatar" v-for="item in company.user.data" v-if="company.user"><span class="boss-count">{{company.boss_count}}个Boss</span></div>
                        </div>
                    </div>
                </div>
                <div class="no-company-prompt"><span>以上都不是我的公司</span></div>
                <div class="no-company-li" @click="$refs.createCompany.show()">
                    <i class="icon icon-jiahao"></i><span class="content">创建我的公司</span><i class="color-d icon-right"></i>
                </div>
            </div>
            <create-company :companyName="companies[0].name" ref="createCompany"></create-company>
            <join-company :company="currentCompany" @hide="hide(), $emit('hide')" ref="joinCompany"></join-company>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import createCompany from './create-company'
  import joinCompany from './join-company'

  export default {
    props: {
      companies: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        showFlag: false,
        currentCompany: {}
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
      createCompany,
      joinCompany
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../../sass/variables"
    @import "../../../../../sass/mixin"

    .select-company-wrapper
        @include allCover
        background: $bc
        .header
            margin-bottom: 20px
        .main
            position: absolute
            top: 70px
            bottom: 0
            width: 100%
            overflow-y: auto
            -webkit-overflow-scrolling: touch
            .media-wrapper
                background: #fff
                div.media:not(:first-child)
                    @include border-top-1px($color-text-d)
                .media
                    display: flex
                    align-items: flex-start
                    padding: 15px
                    img.pic
                        width: 70px
                        height: 70px
                        border-radius: .3rem
                        margin-right: 15px
                    .media-body
                        flex: 1
                        line-height: .65rem
                        color: $color-text-d
                        h3
                            font-size: .4rem
                            color: $color-text-ll
                        .company-name
                            font-size: .35rem
                        .boss-list
                            display: flex
                            font-size: .3rem
                            align-items: center
                            .avatar
                                width: 15px
                                height: 15px
                                border-radius: 50%
                                margin-right: .2rem
            .no-company-prompt
                color: $color-text-d
                padding: 20px
            .no-company-li
                display: flex
                align-items: center
                color: #000000
                background: #ffffff
                padding: 15px 20px
                .icon
                    border: 1px solid #000000
                    border-radius: 3px
                    padding: 1px
                    margin-right: .3rem
                .content
                    flex: 1
                    font-size: .4rem
                    color: $color-text-l
                .color-d
                    color: $color-text-d
</style>