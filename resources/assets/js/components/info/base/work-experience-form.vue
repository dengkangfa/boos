<template>
    <transition name="horizontal-slide">
        <div class="work-experience-wrapper">
            <dkf-header :title="title" class="_effect" :class="{'_effect--50': decline}" fixed>
                <div slot="left" @click="$emit('abandon')"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                <div slot="right" @click="save" style="padding-right: .3rem;">保存</div>
            </dkf-header>
            <div class="main _effect" :class="{'_effect--30': decline}">
                <div class="item-wrapper">
                    <ul class="cell">
                        <li @click="showPositionTypeSelect" class="active">
                            <div class="cell-title"><span>职位类型</span></div>
                            <div class="cell-value is-link"><span>{{ position }}</span></div>
                            <i class="icon icon-right"></i></li>
                        <li @click="showPositionNameInput">
                            <div class="cell-title"><span>职位名称</span></div>
                            <div class="cell-value is-link"><span>{{ workExperienceData.position_name ? workExperienceData.position_name : '选填' }}</span></div>
                            <i class="icon icon-right"></i></li>
                        <li @click="showCompanyNameInput" class="active">
                            <div class="cell-title">公司名称</div>
                            <div class="cell-value is-link"><span>{{ workExperienceData.company_name }}</span></div>
                            <i class="icon icon-right"></i></li>
                        <li @click="showIndustrySelect">
                            <div class="cell-title">公司行业</div>
                            <div class="cell-value is-link"><span>{{ workExperienceData.industry }}</span></div>
                            <i class="icon icon-right"></i></li>
                        <li @click="positionSkillClick" class="active">
                            <div class="cell-title">技能标签</div>
                            <div class="cell-value is-link"><span>{{ workEmphasisArr.length ? workEmphasisArr.length + '个标签' : '' }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li class="period">
                            <div class="cell-title">时间段</div>
                            <div class="cell-value"><span class="start-time" @click="showStartTimePicker">{{ workExperienceData.start_time ? workExperienceData.start_time.replace(/-/, '.') : '请选择'}}</span>至<span @click="showEndTimePicker">{{ workExperienceData.end_time != -1 ? workExperienceData.end_time.replace(/-/, '.') : '至今' }}</span></div>
                        </li>
                        <li @click="showSubordinateInput">
                            <div class="cell-title">所属部门</div>
                            <div class="cell-value is-link"><span>{{ workExperienceData.subordinate ? workExperienceData.subordinate : '选填' }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="showWorkContent">
                            <div class="cell-title">工作内容</div>
                            <div class="cell-value is-link"><span v-html="workContentValue"></span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="showPerformanceInput">
                            <div class="cell-title">工作业绩</div>
                            <div class="cell-value is-link"><span v-html="performanceValue"></span></div>
                            <i class="icon icon-right"></i>
                        </li>
                    </ul>
                </div>
                <div class="hide-my-info">
                    <span>对这家公司隐藏我的信息</span><mint-switch @change="saveIsvisible" v-model="veiled"></mint-switch>
                </div>
                <div class="work-experience-footer">
                    <div class="theme-button" @click="save">保存</div>
                </div>
            </div>
            <message-box :message="messageBoxText" cancelButtonText="好" ref="messageBox"></message-box>
            <position-type-select v-model="workExperienceData.position_type" @selected="positionSelected" ref="positionTypeSelect"></position-type-select>
            <full-screen-input :maxLength="12" v-model="workExperienceData.position_name" @saveValue="savePositionName" title="职位名称" ref="positionNameInput"></full-screen-input>
            <position-skill-checkbox @save="savePositionSkill" v-model="workEmphasisArr" :data="positionSkills" ref="positionSkillCheckbox"></position-skill-checkbox>
            <full-screen-input v-model="workExperienceData.company_name" @saveValue="saveCompanyName" title="公司名称" ref="companyNameInput"></full-screen-input>
            <picker title="时间段" :slots="startTimePickerSlots" :showToolbar="true" ref="startTimePicker" @onValuesChange="startOnValuesChange" @confirm="startPickerConfirm"></picker>
            <picker title="时间段" :slots="endTimePickerSlots" :showToolbar="true" ref="endTimePicker" @onValuesChange="endOnValuesChange" @confirm="endPickerConfirm"></picker>
            <industry-select type="radio" @checked="selectedIndustry" ref="industrySelect"></industry-select>
            <full-screen-input :maxLength="6" v-model="workExperienceData.subordinate" @saveValue="saveSubordinate" title="所属部门" ref="subordinateInput"></full-screen-input>
            <full-screen-input type="textarea" wordCountPosition="top" :maxLength="300" v-model="workExperienceData.performance" @saveValue="savePerformance" title="工作业绩" ref="performanceInput" placeholder="填写完整、有吸引力的工作业绩，有助于您更多地吸引Boss的关注                                       例如:                                                1.取得的成绩...                            2.实现的突破...                            3.获得的表彰..."></full-screen-input>
            <work-content v-model="workExperienceData.responsibility" @complete="saveWorkContent" ref="workContent"></work-content>
            <spinner :text="spinnerText" v-show="spinner"></spinner>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import fullScreenInput from './full-screen-input'
  import positionTypeSelect from './position-type-select'
  import positionSkillCheckbox from './position-skill-checkbox'
  import picker from 'Base/picker/picker'
  import industrySelect from './industry-select'
  import messageBox from 'Base/message/message-box'
  import spinner from 'Base/spinner/spinner'
  import {getPositionSkill} from 'Api/position-skill'
  import {createdWorkExperience, updateWorkExperience} from 'Api/work-experience'
  import {ERR_OK, ERR_UNPROCESSABLE_ENTITY} from 'Api/config'
  import workContent from './work-content'
  import POSITION_TYPE from './positions'

  export default {
    props: {
      value: {
        type: Object,
        default: () => {}
      },
      title: {
        type: String,
        default: '工作经历'
      }
    },
    data() {
      return {
        decline: false,
        messageBoxText: '',
        spinnerText: '', // spinner提示消息
        spinner: false, // 用于控制spinner显示与否
        originalValue: {},
        position: '',
        positionSkills: [],
        workEmphasisArr: [],
        veiled: true,
        workExperienceData: {
          position_type: '', // 职位类型
          position_name: '', // 职位名称
          company_name: '', // 公司名称
          industry: '', // 公司行业
          work_emphasis: '', // 技能标签
          start_time: '', // 开始时间
          end_time: -1, // 结束时间
          subordinate: '', // 所属部门
          responsibility: '', // 工作内容
          performance: '', // 工作业绩
          veiled: true // 是否隐藏
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
        monthListDate: [12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1]
      }
    },
    created() {
      if (JSON.stringify(this.value) !== '{}') {
        this.workExperienceData = Object.assign({}, this.value)
        this.originalValue = this.workExperienceData
        // 获取职位code对应的职位名称
        this.position = this.getPositionName(this.workExperienceData.position_type)
        // 获取当前职位对应的所有技能标签
        this._getPositionSkill(this.workExperienceData.position_type.toString().substr(0, 4) + '00')
        // 当前已选的技能标签
        this.workEmphasisArr = this.workExperienceData.work_emphasis.split('・')
        // 是否对该公司隐藏信息
        this.veiled = this.workExperienceData.veiled
      }
      this.init()
    },
    mounted() {
      if (this.workExperienceData.start_time) {
        this.initPeriodDefaultIndex()
      }
    },
    computed: {
      performanceValue() {
        return this.workExperienceData.performance ? '<i class="icon icon-correct-filling" style="font-size: .4rem;color: #e9e9e9;margin-right: 7px;"></i>' : '选填'
      },
      workContentValue() {
        return this.workExperienceData.responsibility ? '<i class="icon icon-correct-filling" style="font-size: .4rem;color: #e9e9e9;margin-right: 7px;"></i>' : ''
      }
    },
    methods: {
      init() {
        this.initPeriodSlots()
      },
      // 初始化时间段Slots数据
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
      // 初始化当前时间段
      initPeriodDefaultIndex() {
        let startTimes = this.workExperienceData.start_time.split('-')
        this.$refs.startTimePicker.setValues([parseInt(startTimes[0]), parseInt(startTimes[1])])
        if (this.workExperienceData.end_time !== -1) {
          let endTimes = this.workExperienceData.end_time.split('-')
          this.$refs.endTimePicker.setValues([parseInt(endTimes[0]), parseInt(endTimes[1])])
        }
      },
      // 显示职位类型选择器
      showPositionTypeSelect() {
        this.$refs.positionTypeSelect.show()
      },
      // 显示职位名称输入控件
      showPositionNameInput() {
        this.$refs.positionNameInput.show()
      },
      // 显示公司名称输入控件
      showCompanyNameInput() {
        this.$refs.companyNameInput.show()
      },
      // 显示开始时间的时间选择器
      showStartTimePicker() {
        this.$refs.startTimePicker.show()
      },
      // 显示结束时间的时间选择器
      showEndTimePicker() {
        this.$refs.endTimePicker.show()
      },
      // 显示公司行业选择器
      showIndustrySelect() {
        this.$refs.industrySelect.show()
      },
      showSubordinateInput() {
        this.$refs.subordinateInput.show()
      },
      showWorkContent() {
        this.$refs.workContent.show()
      },
      showPerformanceInput() {
        this.$refs.performanceInput.show()
      },
      positionSkillClick() {
        if (!this.workExperienceData.position_type) {
          this.messageBoxText = '请先选择职位类型'
          this.$refs.messageBox.show()
          return
        }
        this.$refs.positionSkillCheckbox.show()
      },
      positionSelected(value) {
        this.position = value.name
        this.workExperienceData.position_type = value.id
        this.workExperienceData.position_name = value.name
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
      getPositionName(code) {
        for (let x in POSITION_TYPE) {
          for (let y in POSITION_TYPE[x]) {
            if (code == y) {
              return POSITION_TYPE[x][y]
            }
          }
        }
      },
      savePositionName(value) {
        this.workExperienceData.position_name = value
      },
      saveCompanyName(value) {
        this.workExperienceData.company_name = value
      },
      savePositionSkill(value) {
        this.workEmphasisArr = value
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
      selectedIndustry(value) {
        this.workExperienceData.industry = value[0]
      },
      saveSubordinate(value) {
        this.workExperienceData.subordinate = value
      },
      savePerformance(value) {
        this.workExperienceData.performance = value
      },
      saveWorkContent(workContent) {
        this.workExperienceData.responsibility = workContent
      },
      saveIsvisible(checked) {
        this.workExperienceData.veiled = !checked
      },
      checkDate() {
        if (!this.workExperienceData.position_type) {
          this.messageBoxText = '请选职位类型'
        } else if (!this.workExperienceData.company_name) {
          this.messageBoxText = '请填写公司名称'
        } else if (!this.workExperienceData.industry) {
          this.messageBoxText = '请选择公司行业'
        } else if (!this.workExperienceData.work_emphasis) {
          this.messageBoxText = '请选择技能标签'
        } else if (!this.workExperienceData.start_time) {
          this.messageBoxText = '请选择时间段'
        } else if (this.workExperienceData.end_time !== -1 && this._compareDate(this.workExperienceData.start_time, this.workExperienceData.end_time)) {
          this.messageBoxText = '开始时间不能大于结束时间'
        } else if (!this.workExperienceData.responsibility) {
          this.messageBoxText = '请输入工作内容'
        } else {
          return true
        }
        this.$refs.messageBox.show()
        return false
      },
      _compareDate(d1, d2) {
        return ((new Date(d1.replace(/-/g,"\/"))) > (new Date(d2.replace(/-/g,"\/"))))
      },
      save() {
        if (this.checkDate()) {
          this.submit()
        }
      },
      submit() {
        this.spinnerText = '保存中'
        this.spinner = true
        let handle
        if (this.workExperienceData.id) {
          handle = updateWorkExperience(this.workExperienceData)
        } else {
          handle = createdWorkExperience(this.workExperienceData)
        }
        handle.then(response => {
          this.spinner = false
          if (response.code === ERR_OK) {
            this.$emit('complete', response.data)
          }
        }).catch(error => {
          this.spinner = false
          if (error.code === ERR_UNPROCESSABLE_ENTITY) {
            this.message = error.message
            this.$refs.message.show()
          }
        })
      }
    },
    watch: {
      workEmphasisArr(value) {
        this.workExperienceData.work_emphasis = value.join('・')
      }
    },
    components: {
      dkfHeader,
      positionTypeSelect,
      fullScreenInput,
      messageBox,
      positionSkillCheckbox,
      picker,
      industrySelect,
      workContent,
      spinner
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .work-experience-wrapper
        @include allCover()
        width: 100%
        height: 100%
        font-size: .35rem
        background: $bc
        overflow-y: auto
        -webkit-overflow-scrolling: touch
        .main
            position: absolute
            top: 50px
            left: 0
            bottom: 0
            width: 100%
            overflow-y: scroll
            -webkit-overflow-scrolling: touch
            .item-wrapper
                margin-top: 20px
                .period
                    .cell-value span
                        display: inline-block
                        width: 50px
                        padding: 0 10px
                        &.start-time
                            width: 70px
                            text-align: right
            .hide-my-info
                display: flex
                justify-content: space-between
                height: 45px
                line-height: 45px
                background: #ffffff
                padding: 0 .3rem
                margin-top: 20px
            .work-experience-footer
                margin-top: 35px
                margin-bottom: 20px

</style>