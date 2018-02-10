<template>
    <transition name="slide">
        <div class="expect-position-wrapper">
            <dkf-header title="求职意向"></dkf-header>
            <main>
                <div class="expect-position-content">
                    <ul>
                        <li @click="showApplyStatusSelector"><label>求职状态</label><span>{{ applyStatusDisplayName }} <i class="icon icon-right"></i></span></li>
                        <li @click="showPositionTypeSelector"><label>期望职位</label><span>{{ position }} <i class="icon icon-right"></i></span></li>
                        <li @click="showIndustryCheckbox"><label>期望行业</label><span>不限 <i class="icon icon-right"></i></span></li>
                        <li @click="showDistpicker"><label>工作城市</label><span>{{ expectPositionData.locationName }} <i class="icon icon-right"></i></span></li>
                        <li><label>薪资要求</label></li>
                    </ul>
                </div>
                <div class="expect-position-remind">
                    <div class="text">362个Boss正在路上，<br>填好求职意向，开始直接沟通</div>
                </div>
                <div class="expect-position-footer">
                    <div class="submit-btn">完成</div>
                </div>
            </main>
            <job-search-status-select @select="updateApplyStatus" ref="jobSearchStatusSelector"></job-search-status-select>
            <position-type-select @selected="positionSelected" ref="positionTypeSelector"></position-type-select>
            <distpicker @selected="distPickerSelected" ref="distpicker"></distpicker>
            <industry-checkbox ref="industryCheckbox"></industry-checkbox>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import jobSearchStatusSelect from '../base/apply-status-select'
  import positionTypeSelect from '../base/position-type-select'
  import distpicker from 'Base/picker/distpicker'
  import industryCheckbox from '../base/industry-checkbox'

  const applyStatus = ['离职-随时到岗', '在职-暂不考虑', '在职-考虑机会', '在职-月内到岗']

  export default {
    data() {
      return {
        position: '',
        expectPositionData: {
          applyStatus: '',
          position_type: '',
          locationName: ''
        }
      }
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
      updateApplyStatus(value) {
        this.expectPositionData.applyStatus = value
      },
      positionSelected(value) {
        this.position = value.name
        this.expectPositionData.position_type = value.id
      },
      distPickerSelected(value) {
        this.expectPositionData.locationName = value
      }
    },
    computed: {
      applyStatusDisplayName() {
        return this.expectPositionData.applyStatus !== '' ? applyStatus[this.expectPositionData.applyStatus] : ''
      }
    },
    components: {
      dkfHeader,
      jobSearchStatusSelect,
      positionTypeSelect,
      distpicker,
      industryCheckbox
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
            .expect-position-content
                ul
                    background: #ffffff
                    padding: 0 20px
                    li
                        display: flex
                        justify-content: space-between
                        height: 50px
                        line-height: 50px
                        color: $color-text-l
                        @include border-bottom-1px($color-text-d)
                        box-sizing: border-box
                        &:last-child
                            border-bottom: none
                        i.icon
                            font-size: .3rem
                            color: $color-theme
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