<template>
    <transition name="horizontal-slide">
        <div class="social-home-page-wrapper" v-if="showFlag">
            <dkf-header title="社交主页">
                <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="social-home-page-main">
                <div class="social-home-page-form">
                    <div>
                        <h3>展示我的社交</h3>
                    </div>
                    <div class="input-group">
                        <input type="text" v-model="homepage" @keyup.13="addHomepage" placeholder="https://dribbble.com.shots?list=teams">
                        <span @click="addHomepage"><i class="icon-correct"></i></span>
                    </div>
                    <div class="example">
                        <p>建议添加能突出你个人实力的主页<br>如：微博、GitHub、Dribbble、LOFTER、个人主页...</p>
                    </div>
                </div>
                <div>
                    <ul class="cell">
                        <li @click="showFullScreenInput(homepage)" class="active" v-for="homepage in value">
                            <div class="cell-title">
                                <i class="icon" :class="className(homepage)"></i>
                                <span>{{ homepage }}</span>
                            </div>
                            <i class="icon icon-right"></i>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="social-home-page-bottom">
                <div class="theme-button" @click="submit">完成</div>
            </div>
            <message message="最多可添加三个主页" ref="message"></message>
            <message-box message="请输入合法的网站地址" cancelButtonText="好" ref="messageBox"></message-box>
            <full-screen-input v-model="currentHomepage" :maxLength="60" filter="url" @saveValue="updateHomepage" title="编辑社交主页" ref="fullScreenInput"></full-screen-input>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import message from 'Base/message/message'
  import messageBox from 'Base/message/message-box'
  import fullScreenInput from './full-screen-input'

  const websiteReg = /[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9^・]{0,62})+\.?/
  const githubReg = /^(?:http(?:s|):\/\/|)(?:(?:\w*?)\.|)github\.(?:\w{2,4})(?:\?.*|\/.*|)$/i
  const dribbbleReg = /^(?:http(?:s|):\/\/|)(?:(?:\w*?)\.|)dribbble\.(?:\w{2,4})(?:\?.*|\/.*|)$/i
  const lofterReg = /^(?:http(?:s|):\/\/|)(?:(?:\w*?)\.|)lofter\.(?:\w{2,4})(?:\?.*|\/.*|)$/i
  const weiboReg = /^(?:http(?:s|):\/\/|)(?:(?:\w*?)\.|)weibo\.(?:\w{2,4})(?:\?.*|\/.*|)$/i

  export default {
    props: {
      value: {
        type: Array,
        default: []
      }
    },
    data() {
      return {
        showFlag: false,
        homepage: '',
        currentHomepage: '',
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      showFullScreenInput(homepage) {
        this.currentHomepage = homepage
        this.$refs.fullScreenInput.show()
      },
      addHomepage() {
        if (this.value.length >= 3) {
          this.$refs.message.show()
        } else if (this.homepage === '') {
          return
        } else if (!websiteReg.test(this.homepage)) {
          this.$refs.messageBox.show()
        } else {
          this.value.push(this.homepage)
          this.homepage = ''
        }
      },
      className(homepage) {
        if (githubReg.test(homepage)) {
          return 'icon-github'
        } else if (dribbbleReg.test(homepage)) {
          return 'icon-dribbble'
        } else if (lofterReg.test(homepage)) {
          return 'icon-lofter'
        } else if (weiboReg.test(homepage)) {
          return 'icon-sina-weibo'
        } else {
          return 'icon-home'
        }
      },
      updateHomepage(homepage) {
        if (this.value.indexOf(this.currentHomepage) > -1) {
          this.$set(this.value, this.value.indexOf(this.currentHomepage), homepage)
        }
      },
      submit() {
        let formData = {'homepages': this.value.join('・')}
        this.$store.dispatch('updateHomepages', formData).then(response => {
          this.$emit('complete', this.value)
          this.hide()
        })
      }
    },
    components: {
      dkfHeader,
      message,
      messageBox,
      fullScreenInput
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .social-home-page-wrapper
        @include allCover()
        background: $bc
        .social-home-page-main
            font-size: .4rem
            .social-home-page-form
                background: #ffffff
                padding: 20px 10px
                margin-bottom: 10px
                .input-group
                    width: 100%
                    position: relative
                    display: flex
                    border-collapse: separate
                    padding: 15px 0
                    input
                        flex: 1
                        background: $bc
                        border-radius: .2rem 0 0 .2rem
                        border: 1px solid #eee
                        padding: 5px 0 5px 10px
                    span
                        color: #ffffff
                        font-size: .45rem
                        background: $color-theme
                        border-radius: 0 .2rem .2rem 0
                        border: 1px solid #eee
                        padding: 10px
                .example
                    color: #999
                    font-size: 12px
            .cell-title
                i
                    margin: 5px
        .social-home-page-bottom
            position: absolute
            left: 0
            right: 0
            bottom: 20px
</style>