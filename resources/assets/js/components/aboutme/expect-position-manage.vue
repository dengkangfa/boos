<template>
    <transition name="horizontal-slide">
        <div class="expect-position-manage-wrapper" v-if="showFlag">
            <dkf-header title="管理求职意向">
                <div slot="left" @click="hide()"><i class="icon-left"></i></div>
            </dkf-header>
            <div class="main">
                <ul class="cell">
                    <li>
                        <div class="cell-title"><span>求职状态</span></div>
                        <div class="cell-value is-link"><span>{{ user.job_date }}</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                    <li v-for="expectPosition in expectPositions" class="expect-position" @click="editExpectPosition(expectPosition)">
                        <div>
                            <div class="expect-position-main"><span>{{ `[${expectPosition.location_name}]${expectPosition.position_name}` }}</span></div>
                            <div><span style="margin-right: 8px">{{ expectPosition.low_salary + 'k-' + expectPosition.high_salary + 'k' }}</span><span>{{ expectPosition.industry.replace(/・/g, '、') }}</span></div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="theme-footer-button">
                <div @click="currentExpectPosition = expectPositionData;expectPositionFormShowFlag = true">
                    添加期望职位
                </div>
            </div>
            <expect-position-form v-model="currentExpectPosition" showHeaderLeftBtn @complete="complete" v-show="expectPositionFormShowFlag"></expect-position-form>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import expectPositionForm from 'InfoBase/expect-position-form'
  import {mapState} from 'vuex'

  export default {
    data() {
      return {
        showFlag: false,
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
      })
    },
    methods: {
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
    },
    components: {
      dkfHeader,
      expectPositionForm
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
        z-index: 20
        .expect-position
            line-height: .8rem
            .expect-position-main
                font-size: .4rem
</style>