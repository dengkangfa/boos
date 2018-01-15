<template>
    <transition name="slide">
        <div class="position-skill-wrapper" v-show="showFlag">
            <dkf-header title="技能标签" nextText="确定"></dkf-header>
            <div class="main">
                <div class="disc">
                    <p>输入或选择技能标签，最多3个</p>
                </div>
                <div class="position-skills">
                    <div v-for="item in data" :class="{'active': inArray(item)}" @click="checkbox(item)">{{ item }}</div>
                </div>
            </div>
            <message-box message="最多3个标签" ref="messageBox"></message-box>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import messageBox from 'Base/message/message-box'

  export default {
    props: {
      data: {
        type: Array,
        default: []
      }
    },
    data() {
      return {
        value: [],
        showFlag: false
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      checkbox(item) {
        if (this.value.length >= 3) {
          this.refs.messageBox.show()
          return
        }
        this.value.push(item)
      },
      inArray(item) {
        return this.value.indexOf(item) > -1
      }
    },
    components: {
      dkfHeader,
      messageBox
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .position-skill-wrapper
        @include allCover()
        background: $bc
        .main
            height: 100%
            overflow-y: scroll
            -webkit-overflow-scrolling: touch
            .disc
                color: #999
                text-align: center
                padding: 10px
            .position-skills
                padding: 5px
                div
                    display: inline-block
                    border: 1px solid #999
                    border-radius: .1rem
                    background: #fff
                    padding: 10px
                    margin: 5px
                    &.active
                        background: $color-theme
</style>