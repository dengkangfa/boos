<template>
    <transition name="horizontal-slide">
         <div class="post-job-wrapper">
             <dkf-header title="发布职位" class="header"></dkf-header>
             <div class="main">
                 <h3>发布职位</h3>
                 <div>
                     <div class="post-job-prompt"><span>我们将根据您发布的职位，为您推荐最合适的候选牛人</span></div>
                     <div class="post-job" @click="$refs.postJobForm.show()">
                         <span>发布职位</span>
                         <i class="icon icon-right"></i>
                     </div>
                 </div>
                 <div class="post-job-footer">
                     <span class="options" @click="$refs.options.show()">...</span>
                 </div>
             </div>
             <boss-options ref="options"></boss-options>
             <post-job-form ref="postJobForm"></post-job-form>
         </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import bossOptions from './options.vue'
  import postJobForm from './post-job-form'

  export default {
    data() {
      return {
        fadeShowFlag: false
      }
    },
    components: {
      dkfHeader,
      bossOptions,
      postJobForm
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../../sass/variables"
    @import "../../../../../sass/mixin"

    .post-job-wrapper
        @include allCover
        background: #ffffff
        .header
            color: rgba(255, 255, 255, .6)
        .main
            padding: 20px
            h3
                font-size: .8rem
                font-weight: bold
            .post-job-prompt
                font-size: .4rem
                line-height: .8rem
                padding: 20px 0
                @include border-bottom-1px($color-text-d)
            .post-job
                display: flex
                justify-content: space-between
                font-size: .5rem
                padding: 20px 0
                @include border-bottom-1px($color-text-d)
                i
                    font-size: .35rem
                    color: $color-text-d
        .post-job-footer
            position: absolute
            bottom: 0
            font-size: 1.2rem
            color: $color-text-d
            padding: 20px 0
            .options
                font-weight: bold

</style>