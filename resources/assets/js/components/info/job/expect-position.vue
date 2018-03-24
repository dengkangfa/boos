<template>
    <transition name="horizontal-slide">
        <div class="expect-position-wrapper">
            <dkf-header title="求职意向"></dkf-header>
            <main>
                <div>
                    <ul class="cell">
                        <li @click="showApplyStatusSelector">
                            <div class="cell-title"><span>求职状态</span></div>
                            <div class="cell-value is-link"><span>{{ applyStatusDisplayName }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="showPositionTypeSelector">
                            <div class="cell-title"><span>期望职位</span></div>
                            <div class="cell-value is-link"><span>{{ position }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="showIndustrySelect">
                            <div class="cell-title"><span>期望行业</span></div>
                            <div class="cell-value is-link"><span>{{ industryArr.length ? industryArr.length + '个标签' : '不限' }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="showDistpicker">
                            <div class="cell-title"><span>工作城市</span></div>
                            <div class="cell-value is-link"><span>{{ expectPositionData.location_name }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="showSalaryPicker">
                            <div class="cell-title"><span>薪资要求</span></div>
                            <div class="cell-value is-link"><span>{{ expectPositionData.low_salary ? expectPositionData.low_salary === -1 ? '面议' : expectPositionData.low_salary + 'k-' + expectPositionData.high_salary + 'k' : '' }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
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
            <distpicker title="工作城市" @selected="distPickerSelected" ref="distpicker"></distpicker>
            <industry-select type="checkbox" @checked="selectedIndustry" ref="industryCheckbox"></industry-select>
            <salary-picker @selected="selectedSalary" ref="salaryPicker"></salary-picker>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import jobSearchStatusSelect from '../base/apply-status-select'
  import positionTypeSelect from '../base/position-type-select'
  import distpicker from 'Base/picker/distpicker'
  import industrySelect from '../base/industry-select'
  import salaryPicker from 'Base/picker/salary-picker'
  import {createdExpectPosition} from 'Api/expect-position'

  const applyStatus = ['离职-随时到岗', '在职-暂不考虑', '在职-考虑机会', '在职-月内到岗']

  export default {
    data() {
      return {
        position: '',
        industryArr: '',
        expectPositionData: {
          apply_status: 2,
          position_type: '',
          industry: '',
          location_name: '',
          low_salary: '',
          high_salary: ''
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
      showIndustrySelect() {
        this.$refs.industryCheckbox.show()
      },
      showSalaryPicker() {
        this.$refs.salaryPicker.show()
      },
      updateApplyStatus(value) {
        this.expectPositionData.apply_status = value
      },
      positionSelected(value) {
        this.position = value.name
        this.expectPositionData.position_type = value.id
      },
      distPickerSelected(value) {
        this.expectPositionData.location_name = value[1]
      },
      selectedIndustry(industry) {
        this.industryArr = industry
        this.expectPositionData.industry = industry.join('・')
      },
      selectedSalary(value) {
        this.expectPositionData.low_salary = value[0]
        this.expectPositionData.high_salary = value[1]
      },
      submit() {
        createdExpectPosition(this.expectPositionData).then(response => {
          console.log(response)
        })
      }
    },
    computed: {
      applyStatusDisplayName() {
        return this.expectPositionData.apply_status !== '' ? applyStatus[this.expectPositionData.apply_status] : ''
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
      industrySelect,
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