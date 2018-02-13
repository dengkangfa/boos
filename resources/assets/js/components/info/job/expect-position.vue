<template>
    <transition name="horizontal-slide">
        <div class="expect-position-wrapper">
            <dkf-header title="求职意向"></dkf-header>
            <main>
                <div>
                    <ul class="cell">
                        <li @click="showApplyStatusSelector"><label>求职状态</label><span class="item-value">{{ applyStatusDisplayName }} <i class="icon icon-right"></i></span></li>
                        <li @click="showPositionTypeSelector"><label>期望职位</label><span class="item-value">{{ position }} <i class="icon icon-right"></i></span></li>
                        <li @click="showIndustryCheckbox"><label>期望行业</label><span class="item-value">{{ industryArr.length ? industryArr.length + '个标签' : '不限' }} <i class="icon icon-right"></i></span></li>
                        <li @click="showDistpicker"><label>工作城市</label><span class="item-value">{{ expectPositionData.locationName }} <i class="icon icon-right"></i></span></li>
                        <li @click="showSalaryPicker"><label>薪资要求</label><span class="item-value">{{ expectPositionData.lowSalary ? expectPositionData.lowSalary === '面议' ? '面议' : expectPositionData.lowSalary + 'k-' + expectPositionData.highSalary + 'k' : '' }}<i class="icon icon-right"></i></span></li>
                    </ul>
                </div>
                <div class="expect-position-remind">
                    <div class="text">362个Boss正在路上，<br>填好求职意向，开始直接沟通</div>
                </div>
                <div class="expect-position-footer">
                    <div class="submit-btn" @click="submit">完成</div>
                </div>
            </main>
            <job-search-status-select @select="updateApplyStatus" ref="jobSearchStatusSelector"></job-search-status-select>
            <position-type-select @selected="positionSelected" ref="positionTypeSelector"></position-type-select>
            <distpicker @selected="distPickerSelected" ref="distpicker"></distpicker>
            <industry-checkbox @checked="selectedIndustry" ref="industryCheckbox"></industry-checkbox>
            <salary-picker @selected="selectedSalary" ref="salaryPicker"></salary-picker>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import jobSearchStatusSelect from '../base/apply-status-select'
  import positionTypeSelect from '../base/position-type-select'
  import distpicker from 'Base/picker/distpicker'
  import industryCheckbox from '../base/industry-checkbox'
  import salaryPicker from 'Base/picker/salary-picker'
  import {createdExpectPosition} from 'Api/expect-position'

  const applyStatus = ['离职-随时到岗', '在职-暂不考虑', '在职-考虑机会', '在职-月内到岗']

  export default {
    data() {
      return {
        position: '',
        industryArr: '',
        expectPositionData: {
          applyStatus: '',
          position_type: '',
          locationName: '',
          lowSalary: '',
          highSalary: ''
        }
      }
    },
    beforeMount() {
      this.$emit('routePipe', true)
    },
    beforeDestroy() {
      this.$emit('routePipe', false)
    },
    methods: {
      showApplyStatusSelector() {
        this.$refs.jobSearchStatusSelector.show()
      },
      showPositionTypeSelector() {
        this.$refs.positionTypeSelector.show()
      },
      showDistpicker() {
        this.$refs.distpicker.show()
      },
      showIndustryCheckbox() {
        this.$refs.industryCheckbox.show()
      },
      showSalaryPicker() {
        this.$refs.salaryPicker.show()
      },
      updateApplyStatus(value) {
        this.expectPositionData.applyStatus = value
      },
      positionSelected(value) {
        this.position = value.name
        this.expectPositionData.position_type = value.id
      },
      distPickerSelected(value) {
        this.expectPositionData.locationName = value
      },
      selectedIndustry(industry) {
        this.industryArr = industry
      },
      selectedSalary(value) {
        this.expectPositionData.lowSalary = value[0]
        this.expectPositionData.highSalary = value[1]
      },
      submit() {
        createdExpectPosition(this.expectPositionData).then(response => {
          console.log(response)
        })
      }
    },
    computed: {
      applyStatusDisplayName() {
        return this.expectPositionData.applyStatus !== '' ? applyStatus[this.expectPositionData.applyStatus] : ''
      }
    },
    watch: {
      industryArr(value) {
        this.expectPositionData.industry = value.join('・')
      }
    },
    components: {
      dkfHeader,
      jobSearchStatusSelect,
      positionTypeSelect,
      distpicker,
      industryCheckbox,
      salaryPicker
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .expect-position-wrapper
        @include allCover()
        background: $bc
        main
            height: 100%
            .expect-position-remind
                text-align: center
                margin: 50% auto 0
                .text
                    color: $color-text-d
                    line-height: .5rem
                    transform: translateY(-100%)
            .expect-position-footer
                position: absolute
                left: 0
                right: 0
                bottom: 0
                background: #ffffff
                padding: 10px
                .submit-btn
                    height: 35px
                    line-height: 35px
                    color: #ffffff
                    text-align: center
                    border-radius: .1rem
                    background: $color-theme

</style>