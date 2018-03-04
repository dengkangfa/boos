<template>
    <transition name="horizontal-slide">
        <div class="education-experience-wrapper">
            <dkf-header title="教育经历" class="_effect" :class="{'_effect--50': decline}" fixed>
                <div slot="left" @click="remind ? showFriendlyReminderMessage() : $emit('abandon')"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                <div slot="right" @click="next" style="padding-right: .3rem;">{{ headerRightButtonText }}</div>
            </dkf-header>
            <main class="_effect" :class="{'_effect--30': decline}">
                <div class="education-experience-content">
                    <div class="education-experience">
                        <h1>教育经历</h1>
                        <ul class="cell">
                            <li @click="showSchoolInput">
                                <div class="cell-title"><span>学校</span></div>
                                <div class="cell-value is-link"><span :class="{'placeholder' : !educationData.school}">{{ educationData.school ? educationData.school : '中央美术学院' }}</span></div>
                                <i class="icon icon-right"></i>
                            </li>
                            <li @click="showMajorInput">
                                <div class="cell-title"><span>专业</span></div>
                                <div class="cell-value is-link"><span :class="{'placeholder' : !educationData.major}">{{ educationData.major ? educationData.major : '产品设计'}}</span></div>
                                <i class="icon icon-right"></i>
                            </li>
                            <li @click="showDegreePicker">
                                <div class="cell-title"><span>学历</span></div>
                                <div class="cell-value is-link"><span :class="{'placeholder' : !educationData.degree}">{{ educationData.degree ? educationData.degree : '本科' }}</span></div>
                                <i class="icon icon-right"></i>
                            </li>
                            <li @click="showPeriodPicker">
                                <div class="cell-title"><span>时间段</span></div>
                                <div class="cell-value is-link"><span class="item-value">{{ atSchoolPeriod }}</span></div>
                                <i class="icon icon-right"></i>
                            </li>
                        </ul>
                    </div>
                    <!-- 在校经历文本输入框 -->
                    <div class="at-school-experience">
                        <h3>在校经历</h3>
                        <div class="text">
                            <textarea class="item-value" v-model="educationData.edu_description"  maxlength="300" mixlength="2" ref="edu_descriptionTextarea" placeholder="作为班级团支书主要负责团队、党员的                                                                            学习工作、积极分子的发展                                                         评估，负责班级的团费、党费管理..."></textarea>
                            <p class="text-num"><span class="input-num"  :class="{'exceed': isExceed}">{{ descriptionLength }}</span>/{{ length }}</p>
                        </div>
                    </div>
                    <!-- 在校经历文本输入框END -->
                    <div class="next-button" @click.stop="next">下一步</div>
                </div>
            </main>
            <message-box :message="message" ref="message"></message-box>
            <message-box message="直聘君建议" description="突出在校经历，<br>可以弥补工作经历不足的缺点，也可获得更多工作机会" confirmButtonText="再改改" cancelButtonText="就这样" :showConfirmButton="true" @cancel="submit" @confirm="eduDescriptionTextareaFocus" ref="eduDescriptionMessage"></message-box>
            <message-box message="友情提示" description="离高薪职位只差一步，你确定放弃？" confirmButtonText="放弃" cancelButtonText="点错了" :showConfirmButton="true" @cancel="hideFriendlyReminderMessage" @confirm="abandon" ref="friendlyReminderMessage"></message-box>
            <!-- 学校名称输入框 -->
            <full-screen-input v-model="educationData.school" id="school" title="学校" :allowEmpty="true" :showValueLength="false" @saveValue="saveSchoolValue" ref="schoolInput"></full-screen-input>
            <!-- 专业名称输入框 -->
            <full-screen-input v-model="educationData.major" id="major" title="专业" :allowEmpty="true" :showValueLength="false" @saveValue="saveMajorValue" ref="majorInput"></full-screen-input>
            <!-- 在校时间段选择器 -->
            <period-picker v-model="periodValue" @select="PeriodPickerSelectHandle" ref="periodPicker"></period-picker>
            <!-- 学历选择器 -->
            <picker title="学历" :slots="degreePicker" @onValuesChange="degreePickerOnValuesChange" @confirm="degreePickerSelectHandle" ref="degreePicker"></picker>
            <spinner :text="spinnerText" v-show="spinner"></spinner>
            <router-view @routePipe="routePipe"></router-view>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import messageBox from 'Base/message/message-box'
  import fullScreenInput from '../base/full-screen-input'
  import periodPicker from 'Base/picker/period-picker'
  import picker from 'Base/picker/picker'
  import spinner from 'Base/spinner/spinner'
  import {ERR_OK, ERR_UNPROCESSABLE_ENTITY} from 'Api/config'
  import {createdEducationExperience, updateEducationExperience} from 'Api/education-experience'

  export default {
    props: {
      value: {
        type: Object,
        default: () => {}
      },
      headerRightButtonText: {
        type: String,
        default: '下一步'
      },
      remind: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        decline: false,
        length: 300,
        message: '', // 提示消息
        spinnerText: '', // spinner提示消息
        spinner: false, // 用于控制spinner显示与否
        educationData: {
          school: '', // 学校
          major: '', // 专业
          degree: '', // 学历
          start_year: '', // 在校时间段开始时间
          end_year: '', // 在校时间段结束时间
          edu_description: '' // 在校经历
        },
        degreePicker: [{ // 学历选择框可选值
          flex: 1,
          values: ['中专及以下', '高中', '大专', '本科', '硕士', '博士'],
          defaultIndex: 3
        }],
        degreePickerValue: '' // 用于临时记录用户学历选择值
      }
    },
    created() {
      console.log(this.value)
      console.log('education')
      if (JSON.stringify(this.value) !== '{}') {
        this.educationData = Object.assign({}, this.value)
        this.degreePicker[0].defaultIndex = this.degreePicker[0].values.indexOf(this.educationData.degree)
      }
    },
    methods: {
      routePipe(_decline) {
        this.decline = _decline
        this.$emit('routePipe', _decline)
      },
      abandon() {
        this.hideFriendlyReminderMessage()
        this.$emit('abandon')
      },
      // 显示学校名称输入框
      showSchoolInput() {
        this.$refs.schoolInput.show()
      },
      // 显示专业名称输入框
      showMajorInput() {
        this.$refs.majorInput.show()
      },
      // 显示学历选择框
      showDegreePicker() {
        this.$refs.degreePicker.show()
      },
      // 显示在校时间段选择框
      showPeriodPicker() {
        this.$refs.periodPicker.show()
      },
      // 显示友情提示消息框
      showFriendlyReminderMessage() {
        this.$refs.friendlyReminderMessage.show()
      },
      // 隐藏友情提示消息框
      hideFriendlyReminderMessage() {
        this.$refs.friendlyReminderMessage.hide()
      },
      // 学校名称保存函数
      saveSchoolValue(value) {
        this.educationData.school = value
      },
      // 专业名称保存函数
      saveMajorValue(value) {
        this.educationData.major = value
      },
      // 学历选择框值发送变化触发函数
      degreePickerOnValuesChange(picker, values) {
        this.degreePickerValue = values[0]
      },
      // 学历选择框确定选择回调
      degreePickerSelectHandle() {
        this.educationData.degree = this.degreePickerValue
        this.$refs.degreePicker.hide()
      },
      // 在校时间段选择框值发送变化触发函数
      PeriodPickerSelectHandle(values) {
        this.educationData.start_year = values[0]
        this.educationData.end_year = values[1]
      },
      // 触发在校经历获取焦点
      eduDescriptionTextareaFocus() {
        this.$refs.edu_descriptionTextarea.focus()
      },
      checkDate() {
        if (!this.educationData.school) {
          this.message = '请填写学校名称'
        } else if (!this.educationData.major) {
          this.message = '请填写所学专业'
        } else if (!this.educationData.degree) {
          this.message = '请选择学历'
        } else if (!this.educationData.start_year || !this.educationData.end_year) {
          this.message = '请选择在校时间段'
        } else if (!this.educationData.edu_description) {
          this.$refs.eduDescriptionMessage.show()
          return false
        } else {
          return true
        }
        this.$refs.message.show()
        return false
      },
      next() {
        if (this.checkDate()) {
          this.submit()
        }
      },
      // 保存操作
      submit() {
        this.spinnerText = '保存中'
        this.spinner = true
        let handle
        if (this.educationData.id) {
          handle = updateEducationExperience(this.educationData)
        } else {
          handle = createdEducationExperience(this.educationData)
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
    computed: {
      atSchoolPeriod() {
        if (this.educationData.start_year && this.educationData.end_year) {
          return this.educationData.start_year + '-' + this.educationData.end_year
        }
        return ''
      },
      descriptionLength() {
        return this.educationData.edu_description ? this.educationData.edu_description.length : 0
      },
      isExceed() {
        return this.educationData.edu_description ? this.educationData.edu_description.length > this.length : false
      },
      periodValue() {
        return this.educationData.start_year ? [this.educationData.start_year, this.educationData.end_year] : []
      }
    },
    watch: {
      value(newValue) {
        console.log(222)
        if (JSON.stringify(newValue) !== '{}') {
          this.educationData = Object.assign({}, newValue)
          this.degreePicker[0].defaultIndex = this.degreePicker[0].values.indexOf(this.educationData.degree)
        }
      }
    },
    components: {
      dkfHeader,
      messageBox,
      fullScreenInput,
      periodPicker,
      picker,
      spinner
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .education-experience-wrapper
        @include allCover()
        width: 100%
        height: 100%
        font-size: .35rem
        background: $bc
        overflow-y: auto
        -webkit-overflow-scrolling: touch
        .header-wrapper
            position: fixed
            height: 50px
            left: 0
            right: 0
            top: 0
        main
            position: absolute
            top: 50px
            left: 0
            bottom: 0
            width: 100%
            overflow-y: scroll
            -webkit-overflow-scrolling: touch
            .education-experience-content
                width: 100%
                .education-experience, .at-school-experience
                    h1, h3
                        height: 40px
                        line-height: 40px
                        padding-left: 10px
                        font-size: .3rem
                        color: rgba(0,0,0,.5)
                .at-school-experience
                    width: 100%
                    margin-bottom: 1rem
                    .text
                        padding: 10px 10px 0
                        background: #ffffff
                        textarea
                            width: 100%
                            height: 150px
                            font-size: .37rem
                            color: $color-text-l
                            border: none
                            outline: none
                            resize: none
                            padding: 0
                            @include border-bottom-1px($bc)
                            &::placeholder
                                color: #999
                        .text-num
                            height: 45px
                            line-height: 45px
                            text-align: right
                            font-size: .3rem
                            background: #fff
                            .input-num
                                color: $color-theme
                                &.exceed
                                    color: red
                .next-button
                    height: 50px
                    line-height: 50px
                    font-size: 0.45rem
                    text-align: center
                    background: $color-theme
                    border-radius: 0.15rem
                    color: $color-text
                    margin: 10px
</style>