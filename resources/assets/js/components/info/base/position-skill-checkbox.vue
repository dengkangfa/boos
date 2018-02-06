<template>
    <transition name="slide">
        <div class="position-skill-wrapper" v-show="showFlag">
            <dkf-header title="技能标签">
                <div slot="left" @click="cancel"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                <div slot="right" @click="confirm" style="padding-right: .3rem;">确定</div>
            </dkf-header>
            <div class="main">
                <div class="content">
                    <div class="disc">
                        <p>输入或选择技能标签，最多3个</p>
                    </div>
                    <div class="position-skills">
                        <div v-for="item in positionSkills" :class="{'active': inArray(item)}" @click="checkbox(item)">{{ item }}</div>
                        <div @click="addPositionSkill"><i class="icon-jiahao"></i></div>
                    </div>
                </div>
            </div>
            <message-box :message="message" ref="message"></message-box>
            <message-box message="内容尚未保存，确定放弃？" @confirm="hide" @cancle="hideNotSavedYetMessage" cancleText="取消" confirmText="确定" :showConfirmButton="true" ref="notSavedYetMessage"></message-box>
            <message-box @inputValueChange="inputValueChange" @confirm="savePositionSkill" message="输入标签，不超过6个字" :showInput="true" :showConfirmButton="true" ref="inputMessageBox"></message-box>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import messageBox from 'Base/message/message-box'

  export default {
    props: {
      value: {
        type: Array,
        default: []
      },
      data: {
        type: Array,
        default: []
      }
    },
    data() {
      return {
        currentValue: [],
        showFlag: false,
        message: '',
        positionSkillValue: '',
        positionSkills: [],
        isChange: false
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
        this.isChange = true
        let index = this.currentValue.indexOf(item)
        if (index > -1) {
          this.currentValue.splice(index, 1)
          return
        }
        if (this.currentValue.length >= 3) {
          this.message = '最多3个标签'
          this.$refs.message.show()
          return
        }
        this.currentValue.push(item)
      },
      inArray(item) {
        return this.currentValue.indexOf(item) > -1
      },
      confirm() {
        if (!this.currentValue) {
          this.message = '至少选择一个标签'
          this.$refs.message.show()
          return
        }
        this.$emit('save', this.currentValue)
        this.hide()
      },
      cancel() {
        if (this.isChange) {
          this.$refs.notSavedYetMessage.show()
          return
        }
        this.hide()
      },
      hideNotSavedYetMessage() {
        this.$refs.notSavedYetMessage.hide()
      },
      addPositionSkill() {
        if (this.currentValue.length >= 3) {
          this.message = '最多3个标签'
          this.$refs.message.show()
          return
        }
        this.isChange = true
        this.$refs.inputMessageBox.show()
      },
      inputValueChange(value) {
        this.positionSkillValue = value
      },
      savePositionSkill() {
        if (!this.positionSkillValue) {
          return
        }
        if (this._getByteLen(this.positionSkillValue) > 12) {
          this.message = '不超过6个字'
          this.$refs.message.show()
          return
        }
        this.positionSkills.push(this.positionSkillValue)
        this.currentValue.push(this.positionSkillValue)
      },
      _getByteLen(val) {
        var len = 0
        for (var i = 0; i < val.length; i++) {
          var a = val.charAt(i)
          if (a.match(/[^\x00-\xff]/ig) != null) {
            len += 2
          } else {
            len += 1
          }
        }
        return len
      }
    },
    watch: {
      data(value) {
        this.positionSkills = value
      },
      showFlag() {
        this.currentValue = this.value.slice(0)
        this.isChange = false
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
            position: absolute
            top: 50px
            bottom: 0
            overflow-y: scroll
            -webkit-overflow-scrolling: touch
            .content
                height: 100%
                .disc
                    color: #999
                    text-align: center
                    padding: 15px 10px 10px
                .position-skills
                    padding: 5px
                    div
                        display: inline-block
                        border: 1px solid #c2c2c2
                        border-radius: .1rem
                        background: #fff
                        padding: 10px
                        margin: 5px
                        &:last-child
                            width: 50px
                            text-align: center
                            color: $color-theme
                            border: 1px dashed $color-theme
                        &.active
                            color: #fff
                            background: $color-theme
                            border: 1px solid $color-theme
</style>