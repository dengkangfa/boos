<template>
    <div class="identity-wrapper">
        <div class="job-wrapper">
            <div class="identity-img job-img"></div>
            <button class="identity-button job-btn">我要找工作</button>
        </div>
        <div class="or">
            <div class="line"></div>
            <div class="text">或者</div>
            <div class="line"></div>
        </div>
        <div class="hiring-wrapper">
            <div class="identity-img hiring-img"></div>
            <button class="identity-button hiring-btn">我要找工作</button>
        </div>
        <div class="logout">
            <a @click.prevent="logout">退出登录</a>
        </div>
        <message-box message="确定退出登录？" :showCancelButton="true" @confirm="confirm" ref="messageBox"></message-box>
    </div>
</template>

<script type="text/ecmascript-6">
  import messageBox from 'Base/message/message-box'

  export default {
    methods: {
      logout() {
        this.$refs.messageBox.show()
      },
      confirm() {
        this.$store.dispatch('logoutRequest').then(response => {
          sessionStorage.setItem('logout-message', '已退出登录')
          this.$router.push({'name': 'login'})
        })
      }
    },
    components: {
      messageBox
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass">
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .identity-wrapper
        @include allCover()
        .identity-button
            display: block
            width: 40%
            padding: 0.3rem 0.6rem
            border: none
            border-radius: 0.1rem
            color: #ffffff
            background: $color-theme
            margin: auto
        .identity-img
            width: 5rem
            height: 5rem
            background-size: 100%
            margin: auto
        .job-wrapper
            width: 100%
            .job-img
                background-image: url("job.jpg")
        .or
            display: flex
            width: 80%
            align-items: center
            color: $color-text-d
            transform: translateY(50%)
            opacity: 0.5
            margin: 0.5rem auto
            & .line
                position: relative
                flex: 1
                @include border-1px($color-text-d)
            & .text
                padding: 0 12px
                font-weight: bold
                font-size: 14px
        .hiring-wrapper
            width: 100%
            .hiring-img
                background-image: url("hiring.jpg")
        .logout
            position: absolute
            width: 100%
            bottom: 1rem
            a
                display: block
                width: 2rem
                color: $color-theme
                text-align: center
                padding: 5px
                margin: auto

    @media screen and (max-width: 320px)
        .identity-wrapper
            .identity-img
                width: 4.5rem
                height: 4rem
            .logout
                bottom: 0.2rem
</style>