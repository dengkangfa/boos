<template>
    <transition name="horizontal-slide">
        <div class="project-experience-wrapper" v-if="showFlag">
            <dkf-header title="项目经验">
                <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="main">
                <div class="prompt" v-if="!projectExperienceData.id"></div>
                <div style="margin-top: 20px">
                    <ul class="cell">
                        <li @click="$refs.namefullScreenInput.show()" class="active">
                            <div class="cell-title"><span>项目名称</span></div>
                            <div class="cell-value is-link"><span>{{ projectExperienceData.name }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="$refs.urlfullScreenInput.show()" class="active">
                            <div class="cell-title"><span>项目URL</span></div>
                            <div class="cell-value is-link"><span>{{ projectExperienceData.url ? projectExperienceData.url : '选填'}}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="$refs.rolefullScreenInput.show()" class="active">
                            <div class="cell-title"><span>项目角色</span></div>
                            <div class="cell-value is-link"><span>{{ projectExperienceData.role }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="$refs.descriptionScreenInput.show()" class="active">
                            <div class="cell-title"><span>项目描述</span></div>
                            <div class="cell-value is-link"><span v-html="descriptionValue"></span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="$refs.PerformanceScreenInput.show()" class="active">
                            <div class="cell-title"><span>项目业绩</span></div>
                            <div class="cell-value is-link"><span v-html="PerformanceValue"></span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li class="period">
                            <div class="cell-title">时间段</div>
                            <div class="cell-value"><span @click="$refs.startTimePicker.show()">{{ projectExperienceData.start_time ? projectExperienceData.start_time.replace(/-/, '.') : '请选择'}}</span>至<span @click="$refs.endTimePicker.show()">{{ projectExperienceData.end_time ? projectExperienceData.end_time != -1 ? projectExperienceData.end_time.replace(/-/, '.') : '至今' : '请选择'}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="theme-button" @click="save">保存</div>
                <div class="theme-button" style="background-color: red;margin-top: 20px" @click="deleteEvent" v-if="projectExperienceData.id">删除</div>
            </div>
            <fullScreenInput v-model="projectExperienceData.name" title="项目名称" :maxLength="16" @saveValue="projectExperienceData.name = arguments[0]" ref="namefullScreenInput"></fullScreenInput>
            <fullScreenInput v-model="projectExperienceData.url" title="项目URL" :maxLength="60" @saveValue="projectExperienceData.url = arguments[0]" filter="url" ref="urlfullScreenInput"></fullScreenInput>
            <fullScreenInput v-model="projectExperienceData.role" title="项目角色" :maxLength="12" @saveValue="projectExperienceData.role = arguments[0]" ref="rolefullScreenInput"></fullScreenInput>
            <full-screen-input type="textarea" wordCountPosition="top" :maxLength="1600" v-model="projectExperienceData.description" @saveValue="projectExperienceData.description = arguments[0]" title="项目描述" ref="descriptionScreenInput" placeholder="描述该项目，向Boss展示您的项目经验                      例如：                                                          1.项目概况...                                                        2.人员分工...                                 3.我的责任..."></full-screen-input>
            <full-screen-input type="textarea" wordCountPosition="top" :maxLength="300" v-model="projectExperienceData.performance" @saveValue="projectExperienceData.performance = arguments[0]" title="项目业绩" ref="PerformanceScreenInput" placeholder="描述您的项目业绩，向Boss展示您的工作能力                    例如：                                                       1.项目收益...                                                      2.我的贡献...                                           3.我的收获..."></full-screen-input>
            <picker title="时间段" :slots="startTimePickerSlots" :showToolbar="true" ref="startTimePicker" @onValuesChange="startOnValuesChange" @confirm="startPickerConfirm"></picker>
            <picker title="时间段" :slots="endTimePickerSlots" :showToolbar="true" ref="endTimePicker" @onValuesChange="endOnValuesChange" @confirm="endPickerConfirm"></picker>
            <message :message="message" ref="message"></message>
            <message-box message="请选择时间段" cancelButtonText="好" ref="messageBox"></message-box>
            <message-box message="友情提示" description="确定删除这条项目经历吗？" cancelButtonText="取消" confirmButtonText="确定" :showConfirmButton="true" @cancel="$refs.friendlyReminderMessage.hide()" @confirm="deleteProjectExperience" ref="friendlyReminderMessage"></message-box>
            <spinner text="保存中" v-show="spinner"></spinner>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import fullScreenInput from './full-screen-input'
  import picker from 'Base/picker/picker'
  import message from 'Base/message/message'
  import messageBox from 'Base/message/message-box'
  import spinner from 'Base/spinner/spinner'
  import {createdProjectExperience, updateProjectExperience, deleteProjectExperience} from 'Api/project-experience'
  import {ERR_OK, ERR_UNPROCESSABLE_ENTITY} from 'Api/config'

  export default {
    props: {
      value: {
        type: Object,
        default: null
      }
    },
    data() {
      return {
        showFlag: false,
        message: '',
        spinner: false,
        projectExperienceData: {
          name: '',
          url: '',
          role: '',
          description: '',
          performance: '',
          start_time: '',
          end_time: ''
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
            values: [12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1],
            defaultIndex: 0
          }
        ],
        monthListDate: [12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1]
      }
    },
    created() {
      this.init()
    },
    computed: {
      descriptionValue() {
        return this.projectExperienceData.description ? '<i class="icon icon-correct-filling" style="font-size: .4rem;color: #e9e9e9;margin-right: 7px;"></i>' : ''
      },
      PerformanceValue() {
        return this.projectExperienceData.performance ? '<i class="icon icon-correct-filling" style="font-size: .4rem;color: #e9e9e9;margin-right: 7px;"></i>' : '选填'
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      init() {
        this.initPeriodSlots()
      },
      // 初始化时间段Slots数据
      initPeriodSlots() {
        let startTimeList = []
        let endTimeList = ['至今']
        let date = new Date()
        let currentYear = date.getFullYear()
        let currentMonth = date.getMonth()
        for (let i = currentYear, j = 1990; i >= j; i--) {
          startTimeList.push(i)
          endTimeList.push(i)
        }
        startTimeList.push('1990年以前')
        this.startTimePickerSlots[0].values = startTimeList
        this.startTimePickerSlots[0].defaultIndex = startTimeList.indexOf(currentYear)
        this.startTimePickerSlots[1].defaultIndex = this.monthListDate.indexOf(currentMonth + 1)
        this.endTimePickerSlots[0].values = endTimeList
        this.endTimePickerSlots[0].defaultIndex = endTimeList.indexOf(currentYear)
        this.endTimePickerSlots[1].defaultIndex = this.monthListDate.indexOf(currentMonth + 1)
      },
      // 初始化当前时间段
      initPeriodDefaultIndex() {
        let startTimes = this.projectExperienceData.start_time.split('-')
        this.$refs.startTimePicker.setValues([parseInt(startTimes[0]), parseInt(startTimes[1])])
        if (this.projectExperienceData.end_time !== -1) {
          let endTimes = this.projectExperienceData.end_time.split('-')
          this.$refs.endTimePicker.setValues([parseInt(endTimes[0]), parseInt(endTimes[1])])
        } else {
          this.$refs.endTimePicker.setValues(['至今', ''])
        }
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
          this.projectExperienceData.start_time = values[0]
        } else {
          this.projectExperienceData.start_time = values[0] + '-' + (values[1] < 10 ? '0' + values[1] : values[1])
        }
      },
      endPickerConfirm(values) {
        if (!values[1]) {
          this.projectExperienceData.end_time = -1
        } else {
          this.projectExperienceData.end_time = values[0] + '-' + (values[1] < 10 ? '0' + values[1] : values[1])
        }
      },
      save() {
        if (this._checkData()) {
          this.submit()
        }
      },
      deleteEvent() {
        this.$refs.friendlyReminderMessage.show()
      },
      deleteProjectExperience() {
        deleteProjectExperience(this.projectExperienceData.id).then(() => {
          this.hide()
          this.$emit('deleteEvent', this.projectExperienceData.id)
        })
      },
      _checkData() {
        if (!this.projectExperienceData.name) {
          this.message = '请填写项目名称'
        } else if (!this.projectExperienceData.role) {
          this.message = '请填写项目角色'
        } else if (!this.projectExperienceData.description) {
          this.message = '请填写项目描述'
        } else if (!this.projectExperienceData.start_time || !this.projectExperienceData.end_time) {
          this.$refs.messageBox.show()
          return false
        } else {
          return true
        }
        this.$refs.message.show()
        return false
      },
      submit() {
        this.spinner = true
        let handle
        if (this.projectExperienceData.id) {
          handle = updateProjectExperience(this.projectExperienceData)
        } else {
          handle = createdProjectExperience(this.projectExperienceData)
        }
        handle.then(response => {
          this.spinner = false
          if (response.code === ERR_OK) {
            this.$emit('save', response.data)
            this.hide()
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
    components: {
      dkfHeader,
      fullScreenInput,
      picker,
      message,
      messageBox,
      spinner
    },
    watch: {
      value(newValue) {
        if (Object.keys(newValue).length) {
          this.projectExperienceData = newValue
          this.$nextTick(() => {
            this.initPeriodDefaultIndex()
          })
        } else {
          this.projectExperienceData = {
            name: '',
            url: '',
            role: '',
            description: '',
            performance: '',
            start_time: '',
            end_time: ''
          }
        }
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass">
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .project-experience-wrapper
        @include allCover()
        background: $bc
        .main
            .prompt
                height: 70px
                background-image: url('./project-experience-prompt.jpg')
                background-size: 100% 100%
            .cell
                .period
                    .cell-value span
                        display: inline-block
                        width: 50px
                        padding: 0 10px
            .theme-button
                margin-top: 40px
                margin-bottom: 20px
</style>