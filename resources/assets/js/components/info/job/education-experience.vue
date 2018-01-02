<template>
    <transition name="slide">
        <div class="education-experience-wrapper">
            <dkf-header title="教育经历" nextText="下一步" class="header-wrapper" @left="back" @right="next"></dkf-header>
            <div class="education-experience-content">
                <div class="education-experience">
                    <h1>教育经历</h1>
                    <ul class="education-experience-items">
                        <li @click="showSchoolInput"><label>学校</label><span class="item-value" :class="{'placeholder' : !educationData.school}">{{ educationData.school ? educationData.school : '中央美术学院' }} <i class="icon icon-right"></i></span></li>
                        <li @click="showMajorInput"><label>专业</label><span class="item-value" :class="{'placeholder' : !educationData.major}">{{ educationData.major ? educationData.major : '产品设计'}} <i class="icon icon-right"></i></span></li>
                        <li @click="showDegreePicker"><label>学历</label><span class="item-value" :class="{'placeholder' : !educationData.degree}">{{ educationData.degree ? educationData.degree : '本科' }} <i class="icon icon-right"></i></span></li>
                        <li @click="showPeriodPicker"><label>时间段</label><span class="item-value">{{ atSchoolPeriod }}</span></li>
                    </ul>
                </div>
                <div class="at-school-experience">
                    <h3>在校经历</h3>
                    <div class="text">
                        <textarea class="item-value" v-model="educationData.edu_description"  maxlength="300" mixlength="2" ref="edu_descriptionTextarea" placeholder="作为班级团支书主要负责团队、党员的                                                                            学习工作、积极分子的发展                                                         评估，负责班级的团费、党费管理..."></textarea>
                        <p class="text-num"><span class="input-num"  :class="{'exceed': isExceed}">{{ descriptionLength }}</span>/{{ length }}</p>
                    </div>
                </div>
                <div class="next-button" @click.stop="next">下一步</div>
            </div>
            <message-box :message="message" ref="message"></message-box>
            <message-box message="直聘君建议" description="突出在校经历，可以弥补工作经历不足的缺点，也可获得更多工作机会" confirmButtonText="再改改" cancelButtonText="就这样" :showConfirmButton="true" @cancel="submit" @confirm="eduDescriptionTextareaFocus" ref="eduDescriptionMessage"></message-box>
            <full-screen-input v-model="educationData.school" id="school" title="学校" :allowEmpty="true" :showValueLength="false" @saveValue="saveSchoolValue" ref="schoolInput"></full-screen-input>
            <full-screen-input v-model="educationData.major" id="major" title="专业" :allowEmpty="true" :showValueLength="false" @saveValue="saveMajorValue" ref="majorInput"></full-screen-input>
            <period-picker v-model="periodValue" @select="PeriodPickerSelectHandle" ref="periodPicker"></period-picker>
            <picker title="学历" :slots="degreePicker" @onValuesChange="degreePickerOnValuesChange" @confirm="degreePickerSelectHandle" ref="degreePicker"></picker>
            <fading-circle :text="spinnerText" v-show="spinner"></fading-circle>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import messageBox from 'Base/message/message-box'
  import fullScreenInput from '../base/full-screen-input'
  import periodPicker from 'Base/picker/period-picker'
  import picker from 'Base/picker/picker'
  import fadingCircle from 'Base/spinner/fading-circle'
  import {ERR_OK, ERR_UNPROCESSABLE_ENTITY} from 'Api/config'
  import {getEducationExperience, createdEducationExperience, updateEducationExperience} from 'Api/education-experience'

  export default {
    data() {
      return {
        educationData: {
          school: '', // 学校
          major: '', // 专业
          degree: '', // 学历
          start_year: '',
          end_year: '',
          edu_description: ''
        },
        length: 300,
        message: '',
        description: '',
        cancelButtonText: '',
        confirmButtonText: '',
        showConfirmButton: false,
        spinnerText: '',
        spinner: false,
        degreePicker: [{
          flex: 1,
          values: ['中专及以下', '高中', '大专', '本科', '硕士', '博士'],
          defaultIndex: 3
        }],
        degreePickerValue: ''
      }
    },
    created() {
      getEducationExperience().then(response => {
        if (response.code === ERR_OK) {
          this.educationData = response.data
          this.degreePicker[0].defaultIndex = this.degreePicker[0].values.indexOf(response.data.degree)
        }
      })
    },
    methods: {
      back() {
        this.$router.back()
      },
      showSchoolInput() {
        this.$refs.schoolInput.show()
      },
      showMajorInput() {
        this.$refs.majorInput.show()
      },
      showPeriodPicker() {
        this.$refs.periodPicker.show()
      },
      showDegreePicker() {
        this.$refs.degreePicker.show()
      },
      saveSchoolValue(value) {
        this.educationData.school = value
      },
      saveMajorValue(value) {
        this.educationData.major = value
      },
      degreePickerOnValuesChange(picker, values) {
        this.degreePickerValue = values[0]
      },
      PeriodPickerSelectHandle(values) {
        this.educationData.start_year = values[0]
        this.educationData.end_year = values[1]
      },
      degreePickerSelectHandle() {
        this.educationData.degree = this.degreePickerValue
        this.$refs.degreePicker.hide()
      },
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
        console.log(this.educationData)
        if (this.checkDate()) {
          this.submit()
        }
      },
      submit() {
        this.spinnerText = '保存中'
        this.spinner = true
        if (this.educationData.id) {
          updateEducationExperience(this.educationData).then(response => {
            console.log(response)
          })
        } else {
          createdEducationExperience(this.educationData).then(response => {
            this.spinner = false
            console.log(response)
          }).catch(error => {
            this.spinner = false
            if (error.code === ERR_UNPROCESSABLE_ENTITY) {
              this.message = error.message
              this.$refs.message.show()
            }
          })
        }
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
        return this.educationData.edu_description.length
      },
      isExceed() {
        return this.educationData.edu_description.length > this.length
      },
      periodValue() {
        return this.educationData.start_year + '-' + this.educationData.end_year
      }
    },
    components: {
      dkfHeader,
      messageBox,
      fullScreenInput,
      periodPicker,
      picker,
      fadingCircle
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
        font-size: .4rem
        background: $bc
        overflow-y: auto
        -webkit-overflow-scrolling: touch
        .header-wrapper
            overflow: hidden
        .education-experience-content
            width: 100%
            .education-experience, .at-school-experience
                h1, h3
                    height: 40px
                    line-height: 40px
                    padding-left: 10px
                    font-size: .3rem
                    color: rgba(0,0,0,.5)
            .education-experience .education-experience-items
                li
                    display: flex
                    justify-content: space-between
                    height: 45px
                    line-height: 45px
                    @include border-top-1px($bc)
                    padding: 0 0.3rem
                    background: #ffffff
                    .item-value
                        color: $color-text-l
                        &.placeholder
                            color: $color-text-d
                    i.icon
                        color: $color-theme
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
            .next-button
                height: 50px
                line-height: 50px
                font-size: 0.45rem
                text-align: center
                background: $color-theme
                border-radius: 0.15rem
                color: $color-text
                margin: 10px

    /*.slide-enter-active, .slide-leave-active*/
        /*transition: all .3s*/
    /*.slide-enter, .slide-leave-to*/
        /*transform: translate3d(100%, 0, 0)*/
</style>