<template>
    <transition name="vertical-slide">
        <div class="cur-role-wrapper" v-if="showFlag">
            <div class="main">
                <div class="footer">
                    <span class="current-role">您当前的身份是“{{role}}”</span>
                    <div class="theme-button cur" @click="cur">转换成为“{{role == '牛人' ? 'Boss' : '牛人'}}”身份</div>
                    <div class="theme-button back" @click="showFlag = false">返回</div>
                </div>
            </div>
            <spinner text="身份切换中" v-show="spinnerShowFalg"></spinner>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import spinner from 'Base/spinner/spinner'

  export default {
    props: {
      role: String
    },
    data() {
      return {
        showFlag: false,
        spinnerShowFalg: false
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      cur() {
        let role = (this.role === '牛人' ? 'recruiter' : 'applicant')
        this.spinnerShowFalg = true
        this.$store.dispatch('cutRole', {role}).then(response => {
          this.spinnerShowFalg = false
          if (role === 'recruiter') {
            this.$router.push({'name': 'boss-basic-info'})
          } else {
            this.$router.push({'name': 'job-basic-info'})
          }
        })
      }
    },
    components: {
      spinner
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../sass/variables"
    @import "../../../sass/mixin"

    .cur-role-wrapper
        @include allCover()
        background: #ffffff
        z-index: 110
        .main
            width: 100%
            height: 100%
            background: url("./applicant.jpg") no-repeat 0 0
            background-size: 100% 70%
            .footer
                position: fixed
                bottom: 50px
                width: 100%
                .current-role
                    display: block
                    margin: auto
                    font-weight: bold
                    color: $color-theme
                    font-size: .6rem
                    font-weight: bold
                    text-align: center
                    padding: 20px
                .back
                    background: #FFFFFF
                    border: 1px solid $color-theme
                    margin: 10px 40px
                    color: $color-theme
                .cur
                    margin: 10px 40px
</style>