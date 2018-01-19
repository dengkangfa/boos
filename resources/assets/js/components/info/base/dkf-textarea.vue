<template>
    <div class="dkf-textarea">
        <h3 class="title" v-if="title">{{ title }}</h3>
        <div class="text">
            <textarea class="item-value" v-model="currentValue"  :maxlength="maxLength !== -1 ? maxLength : ''" mixlength="2" ref="dkfTextarea" :placeholder="placeholder"></textarea>
            <p class="text-bottom">
                <span @click="toggleExampleShowFlag" v-if="examples.length">看看别人怎么写</span>
                <span class="input-num right-float"  :class="{'exceed': isExceed}">{{ currentValue.length }}/<span class="max-length">{{ maxLength }}</span></span>
            </p>
        </div>
        <div class="example" v-for="(example, index) in examples" v-show="index === currentExampleIndex && exampleShowFlag">
            <div class="example-header">
                <div class="left">
                    <img :src="example.avatar" width="24" height="24" class="avatar">
                    <span class="position">{{ example.position }}</span>
                </div>
                <span class="replace" @click="next">换一个</span>
            </div>
            <div class="example-content">
                {{ example.advantage }}
            </div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
  export default {
    props: {
      value: {
        type: String,
        default: ''
      },
      title: {
        type: String,
        default: ''
      },
      placeholder: {
        type: String,
        default: ''
      },
      maxLength: {
        type: Number,
        default: -1
      },
      examples: {
        type: Array,
        default: []
      }
    },
    data() {
      return {
        exampleShowFlag: false,
        currentValue: '',
        currentExampleIndex: -1
      }
    },
    methods: {
      toggleExampleShowFlag() {
        this.exampleShowFlag = !this.exampleShowFlag
        if (this.exampleShowFlag) {
          this.$emit('exampleShowFlagChange', this.exampleShowFlag)
          this.next()
        }
      },
      next() {
        if (this.currentExampleIndex === this.examples.length - 1) {
          this.currentExampleIndex = 0
        } else {
          this.currentExampleIndex ++
        }
      },
      textareaFoucs() {
        this.$refs.dkfTextarea.focus()
      }
    },
    computed: {
      isExceed() {
        if (this.maxLength !== -1) {
          return this.currentValue.length > this.maxLength
        }
      }
    },
    watch: {
      value(value) {
        this.currentValue = value
      },
      currentValue(value) {
        this.$emit('onValueChange', value)
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .dkf-textarea
        width: 100%
        font-size: .37rem
        .title
            height: 40px
            line-height: 40px
            padding-left: 10px
            color: rgba(0,0,0,.5)
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
                    color: #bdbdbd
            .text-bottom
                height: 45px
                line-height: 45px
                color: $color-theme
                background: #fff
                .input-num
                    font-size: .3rem
                    .max-length
                        color: #999
                    &.exceed
                        color: red
        .example
            color: #ffffff
            background: $color-theme
            opacity: 0.8
            border-radius: .1rem
            padding: 10px 10px 0
            margin: 20px 10px 30px
            &:before
                position: absolute
                display: block
                width: 0
                height: 0
                border: 10px solid transparent
                pointer-events: none
                content: ""
                border-bottom-color: $color-theme
                transform: translate3d(5px, -150%, 0);
            .example-header
                display: flex
                justify-content: space-between
                height: 25px
                line-height: 25px
                @include border-bottom-1px(#ffffff)
                padding-bottom: 5px
                .left
                    display: flex
                    align-items: center
                    height: 25px
                    text-align: center
                    .position
                        display: block
                        padding: 10px
                .avatar
                    border-radius: 50%
            .example-content
                font-size: .3rem
                line-height: .4rem
                padding: .4rem 0 .4rem
</style>