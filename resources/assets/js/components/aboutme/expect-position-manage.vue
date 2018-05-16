<template>
    <transition name="horizontal-slide">
        <div class="expect-position-manage-wrapper" v-if="showFlag">
            <dkf-header title="管理求职意向" class="_effect" :class="{'_effect--30': decline}">
                <div slot="left" @click="hide()"><i class="icon-left"></i></div>
            </dkf-header>
            <div class="main _effect"  :class="{'_effect--30': decline}">
                <scroll :data="expectPositions" class="scroll">
                    <ul class="cell">
                        <li @click="jobStatusText === '应届生' ? $refs.prompt.show() : $refs.jobStatusSelect.show()">
                            <div class="cell-title"><span>求职状态</span></div>
                            <div class="cell-value is-link"><span>{{ jobStatusText }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li v-for="expectPosition in expectPositions" class="expect-position" @click="editExpectPosition(expectPosition)">
                            <div>
                                <div class="expect-position-main"><span>{{ `[${expectPosition.location_name}]${expectPosition.position_name}` }}</span></div>
                                <div><span style="margin-right: 8px">{{ expectPosition.low_salary + 'k-' + expectPosition.high_salary + 'k' }}</span><span>{{ expectPosition.industry ? expectPosition.industry.replace(/・/g, '、') : '不限' }}</span></div>
                            </div>
                        </li>
                    </ul>
                </scroll>
            </div>
            <div class="theme-footer-button _effect"  :class="{'_effect--30': decline}">
                <div @click="currentExpectPosition = expectPositionData;expectPositionFormShowFlag = true">
                    添加期望职位
                </div>
            </div>
            <expect-position-form v-model="currentExpectPosition" showHeaderLeftBtn :showDeleteBtn="currentExpectPosition.id && expectPositions.length > 1" @complete="complete" @delete="deleteHandle" @hide="expectPositionFormShowFlag = false" @routePipe="routePipe" v-if="expectPositionFormShowFlag"></expect-position-form>
            <personal-info ref="personalInfo" @routePipe="routePipe"></personal-info>
            <prompt message="若您为非应届生，<br>请先前往更改“参加工作时间”" btnText="前往" @confirm="$refs.personalInfo.show()" ref="prompt"></prompt>
            <job-status-select @select="updateJobStatus" ref="jobStatusSelect"></job-status-select>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import scroll from 'Base/scroll/scroll'
  import prompt from './prompt'
  import jobStatusSelect from 'InfoBase/job-status-select'
  import personalInfo from './personal-info'
  import expectPositionForm from 'InfoBase/expect-position-form'
  import {mapState} from 'vuex'

  const jobStatus = ['离职-随时到岗', '在职-暂不考虑', '在职-考虑机会', '在职-月内到岗']

  export default {
    data() {
      return {
        showFlag: false,
        decline: false,
        expectPositions: [],
        currentExpectPosition: {},
        expectPositionFormShowFlag: false,
        expectPositionData: {
          position_type: '',
          position_name: '',
          industry: '',
          location_name: '',
          low_salary: '',
          high_salary: ''
        }
      }
    },
    created() {
      axios.get('api/me/expect_positions').then(response => {
        this.expectPositions = response.data.data
      })
    },
    computed: {
      ...mapState({
        user: state => state.AuthUser
      }),
      jobStatusText() {
        return this.user.job_date === '应届生' ? '应届生' : jobStatus[this.user.job_status]
      }
    },
    methods: {
      routePipe(_decline) {
        this.decline = _decline
      },
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      editExpectPosition(data) {
        this.currentExpectPosition = data
        this.expectPositionFormShowFlag = true
      },
      updateJobStatus(job_status) {
        this.$store.dispatch('updateField',{job_status})
      },
      complete(expectPosition) {
        this.expectPositions = this.expectPositions.filter((value) => {
          if (value.id === expectPosition.id) {
            return false
          }
          return true
        })
        this.expectPositions.push(expectPosition)
        this.expectPositions.sort((a, b) => {
          return a.id > b.id
        })
        this.expectPositionFormShowFlag = false
      },
      deleteHandle(id) {
        this.expectPositions = this.expectPositions.filter((value) => {
          if (value.id === id) {
            return false
          }
          return true
        })
        this.expectPositionFormShowFlag = false
      }
    },
    components: {
      dkfHeader,
      expectPositionForm,
      scroll,
      prompt,
      jobStatusSelect,
      personalInfo
    },
    watch: {
      showFlag(newValue) {
        newValue ? this.$emit('routePipe', true) : this.$emit('routePipe', false)
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import '../../../sass/variables'
    @import '../../../sass/mixin'

    .expect-position-manage-wrapper
        @include allCover
        background: $bc
        height: 100%
        z-index: 20
        .main
            position: absolute
            top: 50px
            width: 100%
            bottom: 55px
            overflow: hidden
            .scroll
                height: 100%
            .expect-position
                line-height: .8rem
                .expect-position-main
                    font-size: .4rem
</style>