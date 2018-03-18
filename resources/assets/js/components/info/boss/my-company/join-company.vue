<template>
    <transition>
        <div class="join-company-wrapper" v-if="showFlag">
            <dkf-header title="确认">
                <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="main">
                <div class="prompt">
                    <p>你即将加入公司</p>
                </div>
                <div class="media">
                    <img src="./company.jpg" width="70">
                    <div class="media-body">
                        <h3>{{ company.abbreviation }} | {{ company.industry }}</h3>
                        <p class="company-name">{{ company.name }}</p>
                        <span class="boss-count">0个Boss</span>
                    </div>
                </div>
                <div class="join-company-footer">
                    <div class="submit-btn" @click="submit">确认加入</div>
                </div>
            </div>
        </div>
    </transition>
</template>
<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'

  export default {
    props: {
      company: {
        type: Object
      }
    },
    data() {
      return {
        showFlag: false
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      }
    },
    components: {
      dkfHeader
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../../sass/variables"
    @import "../../../../../sass/mixin"

    .join-company-wrapper
        @include allCover
        background: $bc
        .prompt
            font-size: .4rem
            color: $color-text-l
            text-align: center
            padding: 20px 0
        .media
            display: flex
            align-items: flex-start
            background: #ffffff
            border-radius: 8px
            padding: 20px
            margin: 0 10px
            img
                margin-right: 20px
                border-radius: 8px
            .media-body
                flex: 1
                line-height: .65rem
                color: $color-text-d
                h3
                    font-size: .5rem
                    color: #000000
                .company-name
                    font-size: .35rem
                .boss-count
                    font-size: .2rem
        .join-company-footer
            position: absolute
            left: 0
            right: 0
            bottom: 0
            background: #ffffff
            padding: 10px
            .submit-btn
                height: 35px
                line-height: 35px
                color: #ffffff
                text-align: center
                border-radius: .1rem
                background: $color-theme


</style>