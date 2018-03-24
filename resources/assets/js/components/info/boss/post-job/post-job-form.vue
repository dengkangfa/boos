<template>
    <transition name="horizontal-slide">
        <div class="post-job-form-wrapper" v-if="showFlag">
            <dkf-header title="发布职位">
                <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                <div slot="right" @click="release">发布</div>
            </dkf-header>
            <div class="main">
                <div class="company-info">
                    <ul class="cell">
                        <li @click="">
                            <div class="cell-title"><span>公司信息</span></div>
                            <div class="cell-value is-link"><span>{{ company.abbreviation + ' | ' + company.industry_str }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                    </ul>
                </div>
                <div class="position">
                    <ul class="cell">
                        <li @click="$refs.positionTypeSelect.show()">
                            <div class="cell-title"><span>职位类型</span></div>
                            <div class="cell-value is-link" :class="{'theme-color': !positionData.type_str}"><span>{{ positionData.type_str ? positionData.type_str : '请选择' }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="$refs.positionNameInput.show()">
                            <div class="cell-title"><span>职位名称</span></div>
                            <div class="cell-value is-link" :class="{'theme-color': !positionData.name}"><span>{{ positionData.name ? positionData.name : '请填写' }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="jobPlaceShowFlag = true">
                            <div class="cell-title"><span>工作地点</span></div>
                            <div class="cell-value is-link" :class="{'theme-color': !positionData.place}"><span>{{ positionData.place ? positionData.place : '请填写' }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                    </ul>
                    <div class="position-remind">
                        <span>职类信息、职类名称和工作城市发布后不可修改</span>
                    </div>
                    <ul class="cell">
                        <li @click="$refs.naturePicker.show()">
                            <div class="cell-title"><span>工作性质</span></div>
                            <div class="cell-value is-link"><span>{{ positionData.nature }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="positionSkillClick">
                            <div class="cell-title"><span>技能要求</span></div>
                            <div class="cell-value is-link" :class="{'theme-color': !workEmphasisArr.length}"><span>{{ workEmphasisArr.length ? workEmphasisArr.length + '个标签' : '请选择' }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="$refs.salaryPicker.show()">
                            <div class="cell-title"><span>薪资范围</span></div>
                            <div class="cell-value is-link" :class="{'theme-color': !positionData.low_salary || !positionData.high_salary}"><span>{{ positionData.low_salary ? positionData.low_salary + 'k-' + positionData.high_salary + 'k' : '请选择' }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                    </ul>
                    <ul class="cell position-claim">
                        <li @click="$refs.seniorityPicker.show()">
                            <div class="cell-title"><span>经验要求</span></div>
                            <div class="cell-value is-link"><span>{{ positionData.seniority }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="$refs.educationPicker.show()">
                            <div class="cell-title"><span>最低学历</span></div>
                            <div class="cell-value is-link"><span>{{ positionData.education }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                    </ul>
                    <ul class="cell">
                        <li @click="$refs.descriptionInput.show()">
                            <div class="cell-title"><span>职位描述</span></div>
                            <div class="cell-value is-link" :class="{'theme-color': !positionData.description}"><span v-html="descriptionValue"></span></div>
                            <i class="icon icon-right"></i>
                        </li>
                    </ul>
                </div>
                <div class="bulletin">
                    <p>发布职位即表示你已同意遵守 <span>《Boss直聘职位信息发布规则》</span></p>
                    <p>所以职位均有专人审核，禁止发布手机兼职、淘宝兼职等职位</p>
                </div>
                <div class="theme-button">发布</div>
            </div>
            <position-type-select v-model="positionData.type" @selected="positionSelected" ref="positionTypeSelect"></position-type-select>
            <position-skill-checkbox @save="workEmphasisArr = arguments[0]" v-model="workEmphasisArr" :data="positionSkills" ref="positionSkillCheckbox"></position-skill-checkbox>
            <job-place ref="jobPlace" v-if="jobPlaceShowFlag" @save="positionData.place = arguments[0], jobPlaceShowFlag = false" @hide="jobPlaceShowFlag = false"></job-place>
            <full-screen-input :maxLength="12" v-model="positionData.name" @saveValue="positionData.name = arguments[0]" title="职位名称" ref="positionNameInput"></full-screen-input>
            <picker title="工作性质" :slots="natureSlots" @confirm="positionData.nature = arguments[0][0]" ref="naturePicker"></picker>
            <picker title="经验要求" :slots="senioritySlots" @confirm="positionData.seniority = arguments[0][0]" ref="seniorityPicker"></picker>
            <picker title="工作性质" :slots="educationSlots" @confirm="positionData.education = arguments[0][0]" ref="educationPicker"></picker>
            <salary-picker @selected="positionData.low_salary = arguments[0][0], positionData.high_salary = arguments[0][1]" ref="salaryPicker"></salary-picker>
            <full-screent-textarea v-model="positionData.description" ref="descriptionInput"></full-screent-textarea>
            <message :message="message" ref="message"></message>
            <message-box :message="messageBoxText" cancelButtonText="好" ref="messageBox"></message-box>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import positionTypeSelect from '../../base/position-type-select'
  import positionSkillCheckbox from '../../base/position-skill-checkbox'
  import jobPlace from './job-place'
  import fullScreenInput from '../../base/full-screen-input'
  import fullScreentTextarea from '../../base/full-screen-textarea'
  import salaryPicker from 'Base/picker/salary-picker'
  import picker from 'Base/picker/picker'
  import dkfTextarea from '../../base/dkf-textarea'
  import message from 'Base/message/message'
  import messageBox from 'Base/message/message-box'
  import {mapState} from 'vuex'
  import {getPositionSkill} from 'Api/position-skill'

  export default {
    data() {
      return {
        showFlag: false,
        message: '',
        messageBoxText: '',
        workEmphasisArr: [],
        positionSkills: [],
        jobPlaceShowFlag: false,
        natureSlots: [{values: ['全职', '兼职', '实习']}],
        senioritySlots: [{values: ['不限', '应届生', '一年以内', '1-3年', '3-5年', '5-10年', '10年以上'], defaultIndex: 2}],
        educationSlots: [{values: ['不限', '中专及以下', '高中', '大专', '本科', '硕士', '博士'], defaultIndex: 4}],
        positionData: {
          company_id: '', // 公司id
          type_str: '', // 职位类型
          type_code: '', // 职位类型 code
          name: '', // 职位名称
          place: '', // 工作地点
          nature: '全职', // 工作性质
          work_emphasis: '', // 技能要求
          low_salary: '',
          high_salary: '',
          seniority: '一年以内', // 经验要求
          education: '本科', // 最低学历要求
          description: '' // 描述
        }
      }
    },
    computed: {
      ...mapState({
        company: state => state.Company
      }),
      descriptionValue() {
        return this.positionData.description ? '<i class="icon icon-correct-filling" style="font-size: .4rem;color: #e9e9e9;margin-right: 7px;"></i>' : '请填写'
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      positionSelected(value) {
        this.positionData.type_str = value.name
        this.positionData.type_code = value.id
        this.positionData.name = value.name
        this._getPositionSkill(value.id.substr(0, 4) + '00')
        this.workEmphasisArr = []
      },
      positionSkillClick() {
        if (!this.positionData.type_code) {
          this.messageBoxText = '请先选择职位类型'
          this.$refs.messageBox.show()
          return
        }
        this.$refs.positionSkillCheckbox.show()
      },
      release() {
        if (this._checkData()) {

        }
      },
      _getPositionSkill(positonLv2) {
        getPositionSkill(positonLv2).then(response => {
          this.positionSkills = response
        }).catch(error => {
          console.log(error)
        })
      },
      _checkData() {
        if (!this.positionData.type) {
          this.message = '请您选择职位类型'
        } else if (!this.positionData.place) {
          this.message = '请您选择工作城市'
        } else {
          return true
        }
        this.$refs.message.show()
        return false
      }
    },
    components: {
      dkfHeader,
      positionTypeSelect,
      positionSkillCheckbox,
      jobPlace,
      fullScreenInput,
      fullScreentTextarea,
      salaryPicker,
      picker,
      dkfTextarea,
      message,
      messageBox
    },
    watch: {
      company(newCompany) {
        this.positionData.company_id = newCompany.id
      },
      workEmphasisArr(value) {
        this.positionData.work_emphasis = value.join('・')
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../../sass/variables"
    @import "../../../../../sass/mixin"

    .post-job-form-wrapper
        @include allCover
        background: $bc
        .main
            position: absolute
            top: 50px
            left: 0
            bottom: 0
            width: 100%
            overflow-y: scroll
            -webkit-overflow-scrolling: touch
            .company-info
                margin: 20px 0
            .position
                .position-claim
                    margin: 20px 0
                .position-remind
                    color: $color-text-d
                    padding: 10px
            .bulletin
                line-height: .4rem
                color: $color-text-d
                padding: 20px 20px 0 20px
                span
                    color: $color-theme
            .theme-button
                margin-bottom: 20px
</style>