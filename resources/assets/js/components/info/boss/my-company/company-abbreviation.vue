<template>
    <transition name="horizontal-slide">
        <div v-if="showFlag" class="my-company-wrapper">
            <dkf-header title="输入公司全称">
                <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                <div slot="right" style="color: #cccccc" v-if="!currentValue">保存</div>
                <div slot="right" @click="save" v-else>保存</div>
            </dkf-header>
            <div class="main">
                <div class="input-wrapper">
                    <input v-model="currentValue" type="text" class="company-name-input" v-focus>
                    <div class="work-count-wrapper"><span class="work-count" :class="{'exceed': isExceed}">{{ currentValue.length }}</span>/{{ maxLength }}</div>
                </div>
                <div class="prompt">
                    <div class="media">
                        <img class="media-figure" src="./bulb.jpg" width="40" alt="">
                        <div class="media-body">
                            <p style="font-size: .35rem;line-height: .5rem;">公司简称能让牛人记住你， 可以是<span style="color: #000000">品牌名 / 知名产品名 / 名称关键字</span>， 创建后不可修改</p>
                            <p style="margin-top: 25px;">示例: {{ examples[currentExampleIndex] }}</p>
                            <p @click="change" class="switch-button">换一个</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'

  export default {
    props: {
      value: {
        type: String,
        default: '1'
      },
      maxLength: {
        type: Number,
        default: 10
      }
    },
    data() {
      return {
        showFlag: false,
        currentValue: '',
        currentExampleIndex: 0,
        examples: [
          'boss直聘为北京华品博睿网络技术有限公司的简称',
          '腾讯为腾讯科技 (深圳) 有限公司的公司简称',
          '美团为北京三快在线科技有限公司的公司简称',
          '网秦无限为网秦无限（北京）科技有限公司的简称',
          '同道大叔为深圳市同道大叔文化传播有限公司的公司简称'
        ]
      }
    },
    directives: {
      focus: {
        inserted (el) {
          setTimeout(() => {
            el.focus()
          }, 350)
        }
      }
    },
    computed: {
      isExceed() {
        return this.currentValue.length > this.maxLength
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      change() {
        if (this.currentExampleIndex === this.examples.length - 1) {
          this.currentExampleIndex = 0
        } else {
          this.currentExampleIndex ++
        }
      },
      save() {
        this.$emit('saveEvent', this.currentValue)
        this.hide()
      }
    },
    components: {
      dkfHeader
    },
    watch: {
      value(newValue) {
        this.currentValue = newValue
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../../sass/variables"
    @import "../../../../../sass/mixin"

    .my-company-wrapper
        @include allCover
        background: $bc
        .input-wrapper
            position: relative
            margin-top: 20px
            .company-name-input
                width: 100%
                padding: 15px 50px 15px 10px
            .work-count-wrapper
                @include ct
                right: 0
                width: 40px
                text-align: right
                padding-right: 10px
                .work-count
                    color: $color-theme
                    &.exceed
                        color: red
        .prompt
            background: #f8f5fa
            margin: 20px 10px
            border-radius: 5px
            &:before
                position: absolute
                display: block
                width: 0
                height: 0
                border: 10px solid transparent
                pointer-events: none
                content: ""
                border-bottom-color: #f8f5fa
                transform: translate3d(15px, -100%, 0)
            .media
                display: flex
                align-items: flex-start
                img
                    margin-top: 5px
                .media-body
                    flex: 1
                    color: #bdbdbd
                    padding: 10px 20px 0 0
                    .switch-button
                        color: $color-theme
                        margin: 15px 0
</style>