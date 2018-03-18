<template>
    <transition name="horizontal-slide">
        <div class="create-company-wrapper" v-if="showFlag">
            <dkf-header title="新建公司">
                <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                <div slot="right" style="color: #cccccc" v-if="disabled">完成</div>
                <div slot="right" @click="$refs.joinCompany.show()" v-else>完成</div>
            </dkf-header>
            <div class="caveat">
                公司信息将被审核，请问=确保您输入的信息真实有效
            </div>
            <div class="main">
                <ul class="cell">
                    <li>
                        <div class="cell-title"><span>公司全称</span></div>
                        <div class="cell-value"><span>{{ companyName }}</span></div>
                    </li>
                    <li @click="$refs.companyAbbreviation.show()">
                        <div class="cell-title"><span>公司简称</span></div>
                        <div class="cell-value is-link" :class="{'color-theme': !companyData.abbreviation}"><span>{{ companyData.abbreviation ? companyData.abbreviation : '请填写'}}</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                    <li @click="$refs.industrySelect.show()">
                        <div class="cell-title"><span>公司行业</span></div>
                        <div class="cell-value is-link" :class="{'color-theme': !companyData.industry}"><span>{{ companyData.industry ? companyData.industry : '请填写'}}</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                    <li @click="$refs.peoplePicker.show()">
                        <div class="cell-title"><span>人员规模</span></div>
                        <div class="cell-value is-link" :class="{'color-theme': !companyData.people}"><span>{{ companyData.people ? companyData.people : '请填写'}}</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                </ul>
            </div>
            <company-abbreviation  @saveEvent="companyData.abbreviation = arguments[0]" ref="companyAbbreviation"></company-abbreviation>
            <join-company :company="companyData" ref="joinCompany"></join-company>
            <industry-select type="radio" @checked="companyData.industry = arguments[0][0]" ref="industrySelect"></industry-select>
            <picker title="人员规模" :slots="peoplePickerSlots" :showToolbar="true" ref="peoplePicker" @confirm="companyData.people = arguments[0][0]"></picker>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import picker from 'Base/picker/picker'
  import companyAbbreviation from './company-abbreviation'
  import joinCompany from './join-company'
  import industrySelect from '../../base/industry-select'

  export default {
    props: {
      companyName: {
        type: String,
        required: true
      }
    },
    data() {
      return {
        showFlag: false,
        peoplePickerSlots: [
          {
            flex: 1,
            values: ['0-20人', '20-99人', '100-499人', '500-900人', '1000-9999人', '10000人以上'],
            defaultIndex: 0
          }
        ],
        companyData: {
          name: '',
          abbreviation: '',
          industry: '',
          people: ''
        }
      }
    },
    computed: {
      disabled() {
        for (let key in this.companyData) {
          if (this.companyData[key] === '') {
            return true
          }
        }
        return false
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      }
    },
    watch: {
      showFlag() {
        this.companyData.name = this.companyName
      }
    },
    components: {
      dkfHeader,
      companyAbbreviation,
      joinCompany,
      industrySelect,
      picker
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../../sass/variables"
    @import "../../../../../sass/mixin"

    .create-company-wrapper
        @include allCover
        background: $bc
        .caveat
            color: #d6bd7c
            background: #fff4c7
            font-size: .30rem
            text-align: center
            padding: 15px 5px
        .cell
            li:first-child
                margin: 20px 0
            .cell-value.color-theme
                color: $color-theme
</style>