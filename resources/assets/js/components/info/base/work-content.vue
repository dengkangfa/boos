<template>
    <transition name="horizontal-slide">
        <div class="work-content-wrapper" v-show="showFlag">
            <dkf-header title="工作内容">
                <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="main">
                <div class="prompt"></div>
                <dkf-textarea v-model="currentValue" :examples="examples" title="工作内容" :maxLength="1600" @onValueChange="onValueChange" placeholder="1、主要负责新员工入职培训;                 2、分享制定员工每月个人销售业绩;          3、帮助员工提高每日客单价，整体店面管理等工作。"></dkf-textarea>
                <div class="theme-button" @click="save">保存</div>
            </div>
            <message message="工作内容的描述很重要，</br>请务必填写。" ref="message"></message>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import dkfTextarea from './dkf-textarea'
  import message from 'Base/message/message'

  export default {
    props: {
      value: {
        type: String,
        default: ''
      }
    },
    data() {
      return {
        showFlag: false,
        examples: [ // 我的优势例子
          {
            'avatar': 'images/avatar_1.png',
            'position': '产品助理',
            'advantage': '具备一定的产品设计与规划经验，可运用Axure、visio、PS等工具来工作，善于分享市场及用户需求，实事求是，不会夸夸其谈，热爱产品，喜爱阅读与思考。'
          },
          {
            'avatar': 'images/avatar_2.png',
            'position': 'Android开发',
            'advantage': '4年Android开发经验，参与过多款 App开发，涉及到教育，电商，资讯多方面。为人谦虚谨慎，求知欲望强烈，对待工作一丝不苟'
          },
          {
            'avatar': 'images/avatar_3.png',
            'position': '资深UI设计师',
            'advantage': '3年UI设计经验，曾混在景观，游戏，教育行业。上可出原型，下可做UI，动画，插画，原画。经重重磨练，具备各方综合能力'
          },
          {
            'avatar': 'images/avatar_4.png',
            'position': 'PHP工程师',
            'advantage': '3年PHP开发经验，逻辑思维强悍，沟通能力好。对需求分析从无理解偏差，并能更好完善需求！'
          },
          {
            'avatar': 'images/avatar_5.png',
            'position': '销售经理',
            'advantage': '2年销售管理经验，在担任区域负责人期间，带领区域同事做到移动业务量全省第一。口齿伶俐、思维灵敏、管理组织能力强，精通各种营销手段。'
          }
        ]
      }
    },
    computed: {
      currentValue: {
        get() {
          return this.value;
        },
        set(val) {
          this.$emit('input', val);
        }
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      onValueChange(value) {
        this.currentValue = value
      },
      save() {
        if (!this.currentValue) {
          this.$refs.message.show()
          return
        }
        this.$emit('complete', this.currentValue)
        this.hide()
      }
    },
    components: {
      dkfHeader,
      dkfTextarea,
      message
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .work-content-wrapper
        @include allCover()
        width: 100%
        height: 100%
        background: $bc
        overflow-y: auto
        -webkit-overflow-scrolling: touch
        .main
            position: absolute
            top: 50px
            left: 0
            bottom: 0
            width: 100%
            overflow-y: scroll
            -webkit-overflow-scrolling: touch
            .prompt
                height: 70px
                background-image: url('./work-content-prompt.jpg')
                background-size: 100% 100%
            .theme-button
                margin-top: 20px
                margin-bottom: 20px
</style>