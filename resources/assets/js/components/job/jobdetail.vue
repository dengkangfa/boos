<template>
    <transition name="horizontal-slide">
        <div class="job-detail-wrapper">
            <div class="job-detail-header" ref="header">
                <span class="job-detail-header-left" @click="$router.back()"><i class="icon-left"></i></span>
                <p style="opacity: 0" ref="topName">{{ topName }}</p>
                <div class="job-detail-header-right">
                    <span><i class="icon-star-empty"></i></span>
                    <span><i class="icon-warning-outline"></i></span>
                    <span><i class="icon-fenxiang-angle"></i></span>
                </div>
            </div>
            <div v-if="!job.name && !jobDetail.name">
                <div class="mint-spinner loading-all">
                    <mint-spinner color="#53CAC3" type="triple-bounce"></mint-spinner>
                </div>
            </div>
            <div v-else>
                <div class="main">
                    <scroll :data="jobDetail" class="scroll-wrapper" :probeType="3" :listenScroll="true" @scroll="scrollEvent">
                        <div style="overflow: hidden">
                            <div class="base-info">
                                <div class="title">
                                    <h3 class="posname">{{jobDetail.name ? jobDetail.name : job.name}}</h3>
                                    <span class="money">{{ salary }}</span>
                                </div>
                                <div class="info">
                                    <span><i class="icon-position"></i>广州 ・ 珠江新城</span>
                                    <span><i class="icon-seniority"></i>{{jobDetail.seniority ? jobDetail.seniority : job.seniority}}</span>
                                    <span><i class="icon-education"></i>{{jobDetail.education ? jobDetail.education : job.education}}</span>
                                </div>
                            </div>
                            <div v-if="jobDetail.name">
                                <div class="boss-info box">
                                    <div class="media">
                                        <img class="media-figure" style="border-radius: 50%;" :src="jobDetail.user.data.avatar ? jobDetail.user.data.avatar : 'images/default.png'" width="40" alt="">
                                        <div class="media-body">
                                            <div class="user-info">
                                                <p class="name">{{jobDetail.user.data.name}} <span class="active-period">{{jobDetail.user.data.last_actived_at}}</span></p>
                                                <p class="company">{{jobDetail.company.data.abbreviation + '・' + jobDetail.user.data.pos_name}}</p>
                                            </div>
                                            <i class="icon icon-right"></i>
                                        </div>
                                    </div>
                                    <div class="tag">
                                        <span v-for="tag in tags">{{ tag }}</span>
                                    </div>
                                </div>
                                <div class="pos-detail box">
                                    <h3 class="title">
                                        <span>职位详情</span>
                                    </h3>
                                    <div class="content-wrapper">
                                        <p class="content">
                                            {{jobDetail.description}}
                                        </p>
                                    </div>
                                </div>
                                <div class="work_emphasis-wrapper box">
                                    <h3 class="title"><span>技能要求</span></h3>
                                    <div class="work_emphasis tag">
                                        <span v-for="item in jobDetail.work_emphasis">{{ item }}</span>
                                    </div>
                                </div>
                                <div class="box">
                                    <div class="media company-info">
                                        <img class="media-figure company-pic" src="images/company.jpg" width="40" alt="">
                                        <div class="media-body">
                                            <div class="info">
                                                <p class="company-name">{{ jobDetail.company.data.name }}</p>
                                                <p class="company-info">{{ '不需要融资・' + jobDetail.company.data.people + '・' + jobDetail.company.data.industry_str }}</p>
                                            </div>
                                            <i class="icon icon-right"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="map-wrapper">
                                    <el-amap vid="amap" :dragEnable="false" :zoomEnable="false" :doubleClickZoom="false"
                                             :keyboardEnable="false" :center="location" :zooms="zooms">
                                        <el-amap-info-window
                                                :isCustom="true"
                                                :position="currentWindow.position"
                                                :content="currentWindow.content"
                                                :visible="currentWindow.visible"
                                                :events="currentWindow.events">
                                        </el-amap-info-window>
                                    </el-amap>
                                </div>
                                <div class="box">
                                    <h3 class="title warning"><i class="icon-warning-outline"></i><span>温馨提示</span></h3>
                                    <p class="prompt">该Boss承诺名下所有职位不向您收取费用，如有不实，请立即举报。</p>
                                </div>
                            </div>
                            <div class="mint-spinner" v-else>
                                <mint-spinner color="#53CAC3" type="triple-bounce"></mint-spinner>
                            </div>
                        </div>
                    </scroll>
                </div>
                <div class="theme-footer-button">
                    <div @click="communicate">立即沟通</div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import scroll from 'Base/scroll/scroll'
  import { lazyAMapApiLoaderInstance } from 'vue-amap'
  import {job} from 'Api/job'
  import {createdChat} from 'Api/communicate'

  export default {
    props: {
      job: {
        type: Object
      }
    },
    data() {
      return {
        zooms: [12, 18],
        location: [113.419482, 23.168278],
        currentWindow: {
          position: [0, 0],
          content: '',
          events: {},
          visible: false
        },
        jobDetail: {},
        y: 0,
        tags: ['领导nice', '公司气氛好', '下午茶', '人性化管理', '美女如云', '帅哥如云']
      }
    },
    created() {
      job(this.$route.params.id).then(response => {
        this.jobDetail = response.data
        this.getCurrentPosition(response.data.city + response.data.area + response.data.street)
      }).catch(error => {
        console.log(error)
      })
    },
    computed: {
      topName() {
        if (this.jobDetail.name) {
          return this.jobDetail.name.substr(0, 2) + '...' + this.jobDetail.name.substr(-2, 2)
        }
      },
      salary() {
        let lowSalary, highSalary
        if (this.jobDetail.low_salary) {
          lowSalary = this.jobDetail.low_salary
          highSalary = this.jobDetail.high_salary
        } else {
          lowSalary = this.job.low_salary
          highSalary = this.job.high_salary
        }
        return lowSalary + 'k-' + highSalary + 'k'
      }
    },
    methods: {
      jump() {
        console.log(1)
      },
      scrollEvent(pos) {
        if (pos.y >= 0) {
          this.$refs.topName.style.opacity = 0
          this.$refs.header.style.borderBottom = ''
        } else if (pos.y >= -100) {
          this.$refs.topName.style.opacity = pos.y * -1 * 0.01
          this.$refs.header.style.borderBottom = `1px solid rgba(220, 220, 220, ${pos.y * -1 * 0.01})`
        }
      },
      // 获取当前位置经纬度
      getCurrentPosition(address) {
        let self = this
        lazyAMapApiLoaderInstance.load().then(() => {
          let geocoder = new AMap.Geocoder({
            radius: 1000,
            extensions: 'all'
          })
          geocoder.getLocation(address, function(status, result) {
            if (status === 'complete' && result.info === 'OK') {
              let locationTemp = result.geocodes[0].location
              self.location = [locationTemp.lng, locationTemp.lat]
              self.currentWindow.content = `<div style="display: flex;align-items: center;color: #999;"><div style="max-width: 150px;"><b style="color: #000;
                                                font-weight: bold;">工作地点</b><br>${address}</div><div style="padding-left: 15px;"><i class="icon-right"></i></div></div>`
              self.currentWindow.position = [locationTemp.lng, locationTemp.lat]
              self.currentWindow.visible = true
              self.location_status = 0
            } else {
              self.location = []
              self.currentWindow.position = []
              self.currentWindow.visible = false
              self.location_status = 1
            }
          })
        })
      },
      communicate() {
        createdChat({'user_id': this.jobDetail.user.data.id, 'job_id': this.jobDetail.id}).then(response => {
          this.$router.push({name: 'meschatDetail', params: {'chat_uuid': response.data.uuid}})
          let data = {
            'name': this.jobDetail.user.data.name,
            'avatar': this.jobDetail.user.data.avatar,
            'pos_name': this.jobDetail.user.data.pos_name,
            'company_name': this.jobDetail.company.data.abbreviation
          }
          this.$store.commit({type: 'SET_CONTACT', data})
        })
      },
    },
    components: {
      scroll
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass">
    @import '../../../sass/variables'
    @import '../../../sass/mixin'

    .job-detail-wrapper
        @include allCover()
        z-index: 101
        background: #ffffff
        .job-detail-header
            position: fixed
            top: 0
            z-index: 10
            display: flex
            justify-content: space-between
            align-items: center
            width: 100%
            height: 45px
            line-height: 45px
            font-size: .4rem
            background: #ffffff
            padding: 0 20px
            box-sizing: border-box
            &.show-border
                @include border-bottom-1px(#dcdcdc)
            .job-detail-header-left
                width: 80px
            p
                color: $color-text-ll
                font-weight: 700
            .job-detail-header-right
                width: 80px
                font-size: .5rem
                text-align: right
                span:not(:last-child)
                    margin-right: 5px

    .main
        position: fixed
        top: 45px
        bottom: 55px
        width: 100%
        .scroll-wrapper
            height: 100%
        .media
            display: flex
            align-items: center
            img
                margin-right: 10px
            .media-body
                display: flex
                justify-content: space-between
                align-items: center
                flex: 1
                line-height: .5rem
        div.box
            padding: 20px
            @include border-bottom-1px(#dcdcdc)
            .title
                font-size: .45rem
                font-weight: 900
                margin: 15px 0
            .tag
                margin-top: 10px
                span
                    display: inline-block
                    color: $color-text-l
                    @include border-1px(#dcdcdc)
                    border-radius: .1rem
                    padding: 5px 10px
                    margin: 5px 5px 0 0
        .base-info
            padding: 20px
            @include border-bottom-1px(#dcdcdc)
            .title
                display: flex
                justify-content: space-between
                line-height: 1rem
                .posname
                    width: 180px
                    font-size: .75rem
                    font-weight: 800
                    color: $color-text-ll
                .money
                    font-size: .4rem
                    font-weight: 700
                    color: $color-theme
            .info
                font-size: .38rem
                color: $color-text-l
                margin-top: 15px
                span
                    margin-right: 10px
                    i
                        padding-right: 5px
        .boss-info
            @include border-bottom-1px(#dcdcdc)
            padding: 20px
            .media
                .user-info
                    line-height: .5rem
                    .name
                        font-size: .4rem
                        font-weight: 700
                        span
                            font-size: 12px
                            color: $color-text-d
                    .company
                        font-size: .35rem
                        color: $color-text-l
                i
                    color: $color-text-d
        .pos-detail
            height: 110px
            overflow: hidden
            text-overflow: ellipsis
            .content-wrapper
                font-size: .4rem
                line-height: .7rem
                color: $color-text-l
                .content
                    isplay: -webkit-box //必须结合的属性 ，将对象作为弹性伸缩盒子模型显示 。
                    -webkit-box-orient //必须结合的属性 ，设置或检索伸缩盒对象的子元素的排列方式 。
                    text-overflow: ellipsis //可以用来多行文本的情况下，用省略号“…”隐藏超出范围的文本 。
                    overflow: hidden
                    text-overflow: ellipsis
                    display: -webkit-box
                    -webkit-line-clamp: 2
                    -webkit-box-orient: vertical
        .company-info
            .company-pic
                border: 1px solid #eeeeee
            .info
                p:last-child
                    font-size: .3rem
                    color: $color-text-l
        .map-wrapper
            height: 240px
        .warning
            color: $red
        .prompt
            line-height: .4rem
            border-bottom: none
            color: $color-text-l

    .mint-spinner
        height: 45px
        line-height: 45px
        text-align: center
        .loading-all
            z-index: 50

</style>