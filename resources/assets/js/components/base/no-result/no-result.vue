<template>
  <div class="no-result">
    <div class="no-result-icon"></div>
    <p class="no-result-text">{{title}}</p>
  </div>
</template>

<script type="text/ecmascript-6">
  export default {
    props: {
      title: {
        type: String,
        default: ''
      }
    }
  }
</script>

<style scoped lang="sass" rel="stylesheet/sass">
  @import '../../../../sass/variables'
  @import '../../../../sass/mixin'

  .no-result
    text-align: center
    .no-result-icon
      width: 150px
      height: 150px
      margin: 0 auto
      background: url('icon-page-error.png')
      background-size: 150px 150px
    .no-result-text
      margin-top: 30px
      font-size: .3rem
      color: $color-text-d
</style>