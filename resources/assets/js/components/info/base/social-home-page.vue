<template>
    <transition name="horizontal-slide">
        <div class="social-home-page-wrapper" v-if="showFlag">
            <dkf-header title="社交主页">
                <div slot="left" @click="hide"><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'

  export default {
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
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .social-home-page-wrapper
        @include allCover()
        background: $bc
</style>