<template>
    <transition name="slide">
        <div class="expect-position-wrapper">
            <dkf-header title="求职意向"></dkf-header>
            <main>
                <div class="expect-position-content">
                    <ul>
                        <li><label>求职状态</label></li>
                        <li><label>期望职位</label></li>
                        <li><label>期望行业</label></li>
                        <li><label>工作城市</label></li>
                        <li><label>薪资要求</label></li>
                    </ul>
                </div>
            </main>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  export default {
    components: {
      dkfHeader
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .expect-position-wrapper
        @include allCover()
        background: $bc
</style>