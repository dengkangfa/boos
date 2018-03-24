<template>
    <transition name="slide">
        <div class="advantage-wrapper">
            <dkf-header class="_effect" :class="{'_effect--50': decline}" title="我的优势" fixed>
                <div slot="left" @click="showFriendlyReminderMessage"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                <div slot="right" @click="submit('job-expect-position')"><i class="icon-correct" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <main class="_effect" :class="{'_effect--30': decline}">
                <div class="advantage-content">
                    <dkf-textarea v-model="user.advantage" :examples="examples" @onValueChange="onValueChange" @exampleShowFlagChange="exampleShowFlagChange" title="我的优势" :maxLength="140" :placeholder="placeholder" ref="advantageTextarea"></dkf-textarea>
                    <div class="submit-button-group" :class="{'add-margin-top': !exampleShowFlag}">
                        <div class="theme-button" @click="submit('job-expect-position'), spinnerText = '发布中'">完成</div>
                        <div class="micro-resume"><span @click="submit('job-micro-resume'), spinnerText = '保存中'">继续完善微简历></span></div>
                    </div>
                </div>
            </main>
            <message :message="message" ref="message"></message>
            <message-box message="直聘君建议" description="文字可以再修饰一下，可加深Boos对你的印象" confirmButtonText="再改改" cancelButtonText="就这样" :showConfirmButton="true" @confirm="advantageTextareaFocus" @cancel="request" ref="suggestMessage"></message-box>
            <message-box message="友情提示" description="离高薪职位只差一步，你确定放弃？" confirmButtonText="放弃" cancelButtonText="点错了" :showConfirmButton="true" @cancel="hideFriendlyReminderMessage" @confirm="back" ref="friendlyReminderMessage"></message-box>
            <spinner :text="spinnerText" v-show="spinner"></spinner>
            <router-view @routePipe="routePipe"></router-view>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import messageBox from 'Base/message/message-box'
  import message from 'Base/message/message'
  import spinner from 'Base/spinner/spinner'
  import dkfTextarea from '../base/dkf-textarea'
  import {mapState} from 'vuex'

  export default {
    data() {
      return {
        decline: false,
        message: '',
        routerName: '',
        spinner: false,
        spinnerText: '',
        placeholder: '两年UI设计经验，                                                         熟悉iOS和Android的界面设计规范,                                                        对产品本色有独特见解，有一定的手绘基础。                            不少于20字',
        examples: [ // 我的优势例子
          {
            'avatar': 'images/avatar_9.png',
            'position': '产品助理',
            'advantage': '具备一定的产品设计与规划经验，可运用Axure、visio、PS等工具来工作，善于分享市场及用户需求，实事求是，不会夸夸其谈，热爱产品，喜爱阅读与思考。'
          },
          {
            'avatar': 'images/avatar_10.png',
            'position': 'Android开发',
            'advantage': '4年Android开发经验，参与过多款 App开发，涉及到教育，电商，资讯多方面。为人谦虚谨慎，求知欲望强烈，对待工作一丝不苟'
          },
          {
            'avatar': 'images/avatar_11.png',
            'position': '资深UI设计师',
            'advantage': '3年UI设计经验，曾混在景观，游戏，教育行业。上可出原型，下可做UI，动画，插画，原画。经重重磨练，具备各方综合能力'
          },
          {
            'avatar': 'images/avatar_12.png',
            'position': 'PHP工程师',
            'advantage': '3年PHP开发经验，逻辑思维强悍，沟通能力好。对需求分析从无理解偏差，并能更好完善需求！'
          },
          {
            'avatar': 'images/avatar_13.png',
            'position': '销售经理',
            'advantage': '2年销售管理经验，在担任区域负责人期间，带领区域同事做到移动业务量全省第一。口齿伶俐、思维灵敏、管理组织能力强，精通各种营销手段。'
          }
        ],
        exampleShowFlag: false
      }
    },
    beforeMount() {
      this.$emit('routePipe', true)
    },
    beforeDestroy() {
      this.$emit('routePipe', false)
    },
    methods: {
      routePipe(_decline) {
        this.decline = _decline
        this.$emit('routePipe', _decline)
      },
      // 显示建议消息框
      showFriendlyReminderMessage() {
        this.$refs.friendlyReminderMessage.show()
      },
      // 隐藏建议消息框
      hideFriendlyReminderMessage() {
        this.$refs.friendlyReminderMessage.hide()
      },
      back() {
        this.$router.back()
      },
      // 文本框值发送改变将触发该函数
      onValueChange(value) {
        this.user.advantage = value
      },
      exampleShowFlagChange(value) {
        this.exampleShowFlag = value
      },
      // 我的优势文本框获取焦点
      advantageTextareaFocus() {
        this.$refs.suggestMessage.hide()
        this.$refs.advantageTextarea.textareaFoucs()
      },
      checkData() {
        if (!this.user.advantage) {
          this.message = '请填写您的个人优势'
          this.$refs.message.show()
        } else if (this.user.advantage.length <= 20) {
          this.$refs.suggestMessage.show()
        } else {
          return true
        }
        return false
      },
      submit(routerName) {
        this.routerName = routerName
        if (this.checkData()) {
          this.request()
        }
      },
      // 发出请求
      request() {
        this.spinner = true
        this.$store.dispatch('updateAdvantage', {'advantage': this.user.advantage}).then(response => {
          this.spinner = false
          this.$router.push({'name': this.routerName})
        })
      }
    },
    computed: {
      ...mapState({
        user: state => state.AuthUser
      })
    },
    components: {
      dkfHeader,
      messageBox,
      message,
      dkfTextarea,
      spinner
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .advantage-wrapper
        @include allCover()
        background: $bc
        height: 100%
        .header-wrapper
            position: fixed
            width: 100%
            height: 50px
            right: 0
            top: 0
        main
            position: absolute
            top: 50px
            bottom: 0
            width: 100%
            overflow-y: scroll
            -webkit-overflow-scrolling: touch
            .advantage-content
                height: 100%
                .submit-button-group
                    &.add-margin-top
                        margin-top: 85px
                    .micro-resume
                        text-align: center
                        padding: 20px
                        span
                            color: #999
                            padding: 5px
    .slide-enter-active, .slide-leave-active
        transition: all .3s
    .slide-enter, .slide-leave-to
        transform: translate3d(100%, 0, 0)
</style>