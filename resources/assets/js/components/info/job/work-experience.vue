<template>
    <transition name="slide">
        <div class="work-experience-wrapper">
            <dkf-header title="创建微简历" nextIcon="icon-correct" @left="showFriendlyReminderMessage" @right="" class="header-wrapper"></dkf-header>
            <main>
                <div class="work-experience">
                    <div class="recent-work-experience">
                        <h3>最近一份工作经历</h3>
                        <ul class="recent-work-experience-items cell">
                            <li @click="showCompanyNameInput" class="active"><label>公司名称</label><span class="item-value">{{ workExperienceData.company_name }} <i class="icon icon-right"></i></span></li>
                            <li class="period"><label>时间段</label><div class="item-value"><span class="start-time" @click="showStartTimePicker">{{ workExperienceData.start_time ? workExperienceData.start_time : '请选择'}}</span>至<span @click="showEndTimePicker">{{ workExperienceData.end_time ? workExperienceData.end_time : '至今' }}</span></div></li>
                            <li @click="showPositionTypeSelect" class="active"><label>职位类型</label><span class="item-value"><i class="icon icon-right"></i></span></li>
                            <li class="active"><label>技能标签</label><span class="item-value"><i class="icon icon-right"></i></span></li>
                        </ul>
                    </div>
                    <div class="work-content">
                        <dkf-textarea title="工作内容" :placeholder="placeholder" :max-length="1600" :examples="examples"></dkf-textarea>
                    </div>
                    <div class="theme-button">下一步</div>
                </div>
            </main>

            <message-box message="友情提示" description="离高薪职位只差一步，你确定放弃？" confirmButtonText="放弃" cancelButtonText="点错了" :showConfirmButton="true" @cancel="hideFriendlyReminderMessage" @confirm="back" ref="friendlyReminderMessage"></message-box>
            <full-screen-input v-model="workExperienceData.company_name" @saveValue="saveCompanyName" title="公司名称" ref="companyNameInput"></full-screen-input>
            <picker title="时间段" :slots="startTimePickerSlots" :showToolbar="true" ref="startTimePicker" @click="startOnValuesChange" @onValuesChange="startOnValuesChange" @confirm="startPickerConfirm"></picker>
            <picker title="时间段" :slots="endTimePickerSlots" :showToolbar="true" ref="endTimePicker" @click="endOnValuesChange" @onValuesChange="endOnValuesChange" @confirm="endPickerConfirm"></picker>
            <position-type-select ref="positionTypeSelect"></position-type-select>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import dkfTextarea from '../base/dkf-textarea'
  import fullScreenInput from '../base/full-screen-input'
  import messageBox from 'Base/message/message-box'
  import picker from 'Base/picker/picker'
  import positionTypeSelect from '../base/position-type-select'

  export default {
    data() {
      return {
        workExperienceData: {
          company_name: '', // 公司名称
          start_time: '', // 开始时间
          end_time: '', // 结束时间
          position_type: '', // 技能标签
          work_content: '' // 工作内容
        },
        startTimePickerSlots: [
          {
            flex: 1,
            values: [],
            defaultIndex: 0
          },
          {
            flex: 1,
            values: [12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1],
            defaultIndex: 0
          }
        ],
        endTimePickerSlots: [
          {
            flex: 1,
            values: [],
            defaultIndex: 0
          },
          {
            flex: 1,
            values: [],
            defaultIndex: 0
          }
        ],
        monthListDate: [12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1],
        placeholder: '1、主要负责新员工入职培训；                          2、分析制定员工每月个人销售业绩；                                    3、帮助员工提高每日客单价，整体店面管理等工作。',
        examples: [ // 我的优势例子
          {
            'avatar': 'images/avatar_1.png',
            'position': '产品助理',
            'advantage': '具备一定的产品设计与规划经验，可运用Axure、visio、PS等工具来工作，善于分享市场及用户需求，实事求是，不会夸夸其谈，热爱产品，喜爱阅读与思考。'
          },
          {
            'avatar': 'images/avatar_2.png',
            'position': 'Android开发',
            'advantage': '4年Android开发经验，参与过多款 App开发，涉及到教育，电商，资讯多方面。为人谦虚谨慎，求知欲望强烈，对待工作一丝不苟'
          },
          {
            'avatar': 'images/avatar_3.png',
            'position': '资深UI设计师',
            'advantage': '3年UI设计经验，曾混在景观，游戏，教育行业。上可出原型，下可做UI，动画，插画，原画。经重重磨练，具备各方综合能力'
          },
          {
            'avatar': 'images/avatar_4.png',
            'position': 'PHP工程师',
            'advantage': '3年PHP开发经验，逻辑思维强悍，沟通能力好。对需求分析从无理解偏差，并能更好完善需求！'
          },
          {
            'avatar': 'images/avatar_5.png',
            'position': '销售经理',
            'advantage': '2年销售管理经验，在担任区域负责人期间，带领区域同事做到移动业务量全省第一。口齿伶俐、思维灵敏、管理组织能力强，精通各种营销手段。'
          }
        ]
      }
    },
    created() {
      this.init()
    },
    methods: {
      init() {
        this.initPeriodSlots()
      },
      initPeriodSlots() {
        let startTimeList = []
        let endTimeList = ['至今']
        let date = new Date()
        let currentYear = date.getFullYear()
        for (let i = currentYear, j = 1990; i >= j; i--) {
          startTimeList.push(i)
          endTimeList.push(i)
        }
        startTimeList.push('1990年以前')
        this.startTimePickerSlots[0].values = startTimeList
        this.endTimePickerSlots[0].values = endTimeList
      },
      back() {
        this.$router.back()
      },
      showFriendlyReminderMessage() {
        this.$refs.friendlyReminderMessage.show()
      },
      hideFriendlyReminderMessage() {
        this.$refs.friendlyReminderMessage.hide()
      },
      showCompanyNameInput() {
        this.$refs.companyNameInput.show()
      },
      saveCompanyName(value) {
        this.workExperienceData.company_name = value
      },
      showStartTimePicker() {
        this.$refs.startTimePicker.show()
      },
      showEndTimePicker() {
        this.$refs.endTimePicker.show()
      },
      showPositionTypeSelect() {
        this.$refs.positionTypeSelect.show()
      },
      startOnValuesChange(picker, values) {
        if (values[0] === '1990年以前') {
          picker.setSlotValues(1, [])
        } else {
          picker.setSlotValues(1, this.monthListDate)
        }
      },
      endOnValuesChange(picker, values) {
        if (values[0] === '至今') {
          picker.setSlotValues(1, [])
        } else {
          picker.setSlotValues(1, this.monthListDate)
        }
      },
      startPickerConfirm(values) {
        if (!values[1]) {
          this.workExperienceData.start_time = values[0]
        } else {
          this.workExperienceData.start_time = values[0] + '.' + (values[1] < 10 ? '0' + values[1] : values[1])
        }
      },
      endPickerConfirm(values) {
        if (!values[1]) {
          this.workExperienceData.end_time = values[0]
        } else {
          this.workExperienceData.end_time = values[0] + '.' + (values[1] < 10 ? '0' + values[1] : values[1])
        }
      }
    },
    components: {
      dkfHeader,
      dkfTextarea,
      fullScreenInput,
      messageBox,
      picker,
      positionTypeSelect
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .work-experience-wrapper
        @include allCover()
        background: $bc
        .header-wrapper
            position: fixed
            width: 100%
            height: 50px
            right: 0
            top: 0
        main
            position: absolute
            top: 50px
            bottom: 0
            width: 100%
            overflow-y: scroll
            -webkit-overflow-scrolling: touch
            h3
                height: 40px
                line-height: 40px
                padding-left: 10px
                font-size: .3rem
                color: rgba(0,0,0,.5)
            .work-experience
                height: 100%
                .recent-work-experience .recent-work-experience-items
                    li
                        display: flex
                        justify-content: space-between
                        height: 45px
                        line-height: 45px
                        @include border-top-1px($bc)
                        padding: 0 0.3rem
                        background: #ffffff
                        &.active:active
                            background: #d9d9d9
                        &.period
                            .item-value span
                                display: inline-block
                                width: 50px
                                padding: 0 10px
                                &.start-time
                                    width: 70px
                                    text-align: right
                        .item-value
                            color: $color-text-l
                            &.placeholder
                                color: $color-text-d
                        i.icon
                            font-size: .3rem
                            color: $color-theme
                div.theme-button
                    margin-top: 20px

    /*.slide-enter-active, .slide-leave-active*/
        /*transition: all .3s*/
    /*.slide-enter, .slide-leave-to*/
        /*transform: translate3d(100%, 0, 0)*/
</style>