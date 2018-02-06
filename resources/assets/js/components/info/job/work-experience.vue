<template>
    <transition name="slide">
        <div class="work-experience-wrapper">
            <dkf-header title="创建微简历" fixed>
                <div slot="left" @click="showFriendlyReminderMessage"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                <div slot="right" @click="save"><i class="icon-correct" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <main ref="main">
                <div class="work-experience">
                    <div class="recent-work-experience">
                        <h3>最近一份工作经历</h3>
                        <ul class="recent-work-experience-items cell">
                            <li @click="showCompanyNameInput" class="active"><label>公司名称</label><span class="item-value">{{ workExperienceData.company_name }} <i class="icon icon-right"></i></span></li>
                            <li class="period"><label>时间段</label><div class="item-value"><span class="start-time" @click="showStartTimePicker">{{ workExperienceData.start_time ? workExperienceData.start_time.replace(/-/, '.') : '请选择'}}</span>至<span @click="showEndTimePicker">{{ workExperienceData.end_time != -1 ? workExperienceData.end_time.replace(/-/, '.') : '至今' }}</span></div></li>
                            <li @click="showPositionTypeSelect" class="active"><label>职位类型</label><span class="item-value">{{ position }} <i class="icon icon-right"></i></span></li>
                            <li @click="positionSkillClick" class="active"><label>技能标签</label><span class="item-value">{{ workEmphasisArr.length ? workEmphasisArr.length + '个标签' : '' }}<i class="icon icon-right"></i></span></li>
                        </ul>
                    </div>
                    <div class="work-content">
                        <dkf-textarea v-model="workExperienceData.responsibility" @onValueChange="responsibilityOnValueChange" @exampleShowFlagChange="exampleShowFlagChange" title="工作内容" :placeholder="placeholder" :max-length="1600" :examples="examples" ref="responsibilityTextarea"></dkf-textarea>
                    </div>
                    <div class="theme-button" @click="save">下一步</div>
                </div>
            </main>
            <message :message="message" ref="message"></message>
            <message-box :message="messageBoxText" ref="messageBox"></message-box>
            <message-box message="友情提示" description="离高薪职位只差一步，你确定放弃？" confirmButtonText="放弃" cancelButtonText="点错了" :showConfirmButton="true" @cancel="hideFriendlyReminderMessage" @confirm="back" ref="friendlyReminderMessage"></message-box>
            <message-box message="直聘君建议" description="清晰有条理的工作内容，可获得更多的高薪职位！" confirmButtonText="再改改" cancelButtonText="就这样" :showConfirmButton="true" @cancel="submit" @confirm="responsibilityTextareaFocus" ref="responsibilityMessage"></message-box>
            <full-screen-input v-model="workExperienceData.company_name" @saveValue="saveCompanyName" title="公司名称" ref="companyNameInput"></full-screen-input>
            <picker title="时间段" :slots="startTimePickerSlots" :showToolbar="true" ref="startTimePicker" @onValuesChange="startOnValuesChange" @confirm="startPickerConfirm"></picker>
            <picker title="时间段" :slots="endTimePickerSlots" :showToolbar="true" ref="endTimePicker" @onValuesChange="endOnValuesChange" @confirm="endPickerConfirm"></picker>
            <position-type-select v-model="workExperienceData.position_type" @selected="positionSelected" ref="positionTypeSelect"></position-type-select>
            <position-skill-checkbox @save="savePositionSkill" v-model="workEmphasisArr" :data="positionSkills" ref="positionSkillCheckbox"></position-skill-checkbox>
            <spinner text="保存中" v-show="spinner"></spinner>
            <router-view></router-view>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import dkfTextarea from '../base/dkf-textarea'
  import fullScreenInput from '../base/full-screen-input'
  import messageBox from 'Base/message/message-box'
  import message from 'Base/message/message'
  import picker from 'Base/picker/picker'
  import spinner from 'Base/spinner/spinner'
  import positionTypeSelect from '../base/position-type-select'
  import positionSkillCheckbox from '../base/position-skill-checkbox'
  import {ERR_OK, ERR_UNPROCESSABLE_ENTITY} from 'Api/config'
  import {getPositionSkill} from 'Api/position-skill'
  import {getWorkExperience, updateWorkExperience, createdWorkExperience} from 'Api/work-experience'
  import POSITION_TYPE from '../base/positions'

  export default {
    data() {
      return {
        workExperienceData: {
          company_name: '', // 公司名称
          start_time: '', // 开始时间
          end_time: -1, // 结束时间
          position_type: '', // 职位类型
          work_emphasis: '', // 技能
          responsibility: '' // 工作内容
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
        position: '',
        message: '',
        messageBoxText: '',
        positionSkills: [],
        workEmphasisArr: [],
        spinner: false,
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
      // 请求当前登录用户的教育经历
      getWorkExperience().then(response => {
        if (response.code === ERR_OK) {
          this.workExperienceData = response.data
          this.workEmphasisArr = this.workExperienceData.work_emphasis.split('・')
          this._getPositionSkill(this.workExperienceData.position_type.toString().substr(0, 4) + '00')
          // 查询position_type index对应的职位类型名称
          for (let x in POSITION_TYPE) {
            for (let y in POSITION_TYPE[x]) {
              if (this.workExperienceData.position_type == y) {
                this.position = POSITION_TYPE[x][y]
                return
              }
            }
          }
        }
      })
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
          this.workExperienceData.start_time = values[0] + '-' + (values[1] < 10 ? '0' + values[1] : values[1])
        }
      },
      endPickerConfirm(values) {
        if (!values[1]) {
          this.workExperienceData.end_time = -1
        } else {
          this.workExperienceData.end_time = values[0] + '-' + (values[1] < 10 ? '0' + values[1] : values[1])
        }
      },
      positionSelected(value) {
        this.position = value.name
        this.workExperienceData.position_type = value.id
        this._getPositionSkill(value.id.substr(0, 4) + '00')
        this.workEmphasisArr = []
      },
      _getPositionSkill(positonLv2) {
        getPositionSkill(positonLv2).then(response => {
          this.positionSkills = response
        }).catch(error => {
          console.log(error)
        })
      },
      positionSkillClick() {
        if (!this.workExperienceData.position_type) {
          this.messageBoxText = '请先选择职位类型'
          this.$refs.messageBox.show()
          return
        }
        this.$refs.positionSkillCheckbox.show()
      },
      savePositionSkill(value) {
        this.workEmphasisArr = value
      },
      responsibilityOnValueChange(value) {
        this.workExperienceData.responsibility = value
      },
      exampleShowFlagChange(showFlag) {
        if (showFlag) {
          this.$nextTick(() => {
//            this.$refs.main.style.transition = 'all .3s'
            this.$refs.main.scrollTop = 9999
          })
        }
      },
      responsibilityTextareaFocus() {
        this.$refs.responsibilityTextarea.textareaFoucs()
      },
      save() {
        if (this._checkData()) {
          this.submit()
        }
      },
      submit() {
        let handle
        if (this.workExperienceData.id) {
          handle = updateWorkExperience(this.workExperienceData)
        } else {
          handle = createdWorkExperience(this.workExperienceData)
        }
        this.spinner = true
        handle.then(response => {
          this.spinner = false
          if (response.code === ERR_OK) {
            this.$router.push({'name': 'job-education'})
          }
        }).catch(error => {
          this.spinning = false
          if (!error.success && error.code === ERR_UNPROCESSABLE_ENTITY) {
            this.message = error.message
            this.$refs.message.show()
          }
        })
      },
      _checkData() {
        if (!this.workExperienceData.company_name) {
          this.message = '请填写公司名称'
        } else if (!this.workExperienceData.position_type) {
          this.message = '请填写职位类型'
        } else if (!this.workExperienceData.work_emphasis) {
          this.message = '请选择技能标签'
        } else if (!this.workExperienceData.start_time || !this.workExperienceData.end_time) {
          this.messageBoxText = '请选择时间段'
          this.$refs.messageBox.show()
          return false
        } else if (!this.workExperienceData.responsibility) {
          this.message = '工作内容的描述很重要，请务必填写。'
        } else if (this.workExperienceData.responsibility.length <= 20) {
          this.$refs.responsibilityMessage.show()
          return false
        } else {
          return true
        }
        this.$refs.message.show()
        return false
      }
    },
    watch: {
      position_type() {
        this.workEmphasisArr = []
      },
      workEmphasisArr(value) {
        this.workExperienceData.work_emphasis = value.join('・')
      }
    },
    components: {
      dkfHeader,
      dkfTextarea,
      fullScreenInput,
      message,
      messageBox,
      picker,
      spinner,
      positionTypeSelect,
      positionSkillCheckbox
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .work-experience-wrapper
        @include allCover()
        background: $bc
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
                    margin-bottom: 20px

    .slide-enter-active, .slide-leave-active
        transition: all .3s
    .slide-enter, .slide-leave-to
        transform: translate3d(100%, 0, 0)
</style>