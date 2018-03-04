<template>
    <transition name="horizontal-slide">
        <div class="industry-checkbox-wrapper" v-show="showFlag">
            <div>
                <dkf-header title="选择行业">
                    <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                    <div slot="right" @click="save" v-if="type === 'checkbox'"><span style="padding: 0.3rem;">保存</span></div>
                </dkf-header>
            </div>
            <div class="selected" v-if="type === 'checkbox'">
                <div class="selected-top">
                    <div class="title">已选行业</div>
                    <div><span class="count">{{ selectedIndustry.length }}</span>/3</div>
                </div>
                <div class="selected-industry">
                    <span class="placeholder" v-show="!selectedIndustry.length">请选择行业，最多3个</span>
                    <span class="selected-industry-item" v-for="item in selectedIndustry" v-show="selectedIndustry.length">
                        {{ item }} <i @click="deleteIndustry(item)" class="icon-close" style="padding: 3px;"></i>
                    </span>
                </div>
            </div>
            <div style="flex: auto; overflow: auto">
                <div class="industry-items-wrapper">
                    <div v-for="item in industry">
                        <span class="industry-categories">{{ item['name'] }}</span>
                        <div class="industry-items">
                            <span @click="checked(industry['name'])" v-for="industry in item['subLevelModelList']" :class="{'checked': inArray(industry['name']) }">
                                {{ industry['name'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <message message="最多选3个" ref="message"></message>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import {getIndustry} from 'Api/industry'
  import scroll from 'Base/scroll/scroll'
  import message from 'Base/message/message'

  export default {
    props: {
      type: {
        type: String,
        default: 'checkbox'
      },
    },
    data() {
      return {
        showFlag: false,
        industry: [],
        selectedIndustry: []
      }
    },
    created() {
      getIndustry().then((response) => {
        this.industry = response
      })
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      checked(item) {
        if (this.type === 'checkbox') {
          let index = this.selectedIndustry.indexOf(item)
          if (index > -1) {
            this.selectedIndustry.splice(index, 1)
          } else if (this.selectedIndustry.length < 3) {
            this.selectedIndustry.push(item)
          } else {
            this.$refs.message.show()
          }
        } else {
          this.selectedIndustry = []
          this.selectedIndustry.push(item)
          this.save()
        }
      },
      deleteIndustry(item) {
        this.selectedIndustry.splice(this.selectedIndustry.indexOf(item), 1)
      },
      inArray(name) {
        return this.selectedIndustry.indexOf(name) > -1
      },
      save() {
        this.$emit('checked', this.selectedIndustry)
        this.hide()
      }
    },
    components: {
      dkfHeader,
      scroll,
      message
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass">
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .industry-checkbox-wrapper
        display: flex
        flex-flow: column nowrap
        justify-content: space-between
        @include allCover
        height: 100%
        background: #ffffff
        .selected
            box-shadow: 0 5px 5px #eee
            padding: 20px 20px 1px 20px
            .selected-top
                display: flex
                justify-content: space-between
                font-size: .4rem
                .title
                    font-weight: 700
                    color: $color-text-l
                .count
                    color: $color-theme
            .selected-industry
                min-height: 50px
                line-height: 50px
                margin: 20px 0
                .placeholder
                    color: #999999
                .selected-industry-item
                    display: inline-block
                    line-height: 1
                    color: $color-theme
                    border: 1px solid $color-theme
                    border-radius: .1rem
                    padding: 8px 12px
                    margin-right: 10px
        .industry-items-wrapper
            height: 100%
            padding: 0 20px
            overflow: auto
            .industry-categories
                display: inline-block
                color:  #999999
                margin-top: 20px
            .industry-items
                span
                    display: inline-block
                    border: 1px solid #ebebeb
                    border-radius: .1rem
                    padding: 8px 12px
                    margin: 15px 8px 0 0
                    &.checked
                        background: #ebebeb
</style>