<template>
    <div class="slide_tab">
        <ul class="slide_one">
            <li @click="toggleSelected(list)" v-for="list in slideTemp" :class="{ selected: list.hadSelested }">
                <span>{{list.name}}</span>
                <i class="iconfont icon-correct" v-show="list.hadSelested"></i>
            </li>
        </ul>
        <div id="mask" class="mask" @click="$emit('hide')"></div>
    </div>
</template>

<script>
  export default {
    name: 'slide',
    props: {
      slideTemp: {
        type: Array,
        default: []
      }
    },
    data () {
      return {
        msg: 'Welcome to Your Vue.js App'
      }
    },
    watch: {},
    computed: {},
    methods: {
      // 切换选择样式
      toggleSelected(list) {
        if (!list.hadSelested) {
          this.slideTemp.filter(value => {
            value.hadSelested = false
          })
          list.hadSelested = true
          this.$emit('change', list.name)
        }
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../sass/variables"
    @import "../../../sass/mixin"

    .slide_one
        position: absolute
        z-index: 100
        width: 100%
        background: #E9EFEF
        box-sizing: border-box
        padding: .4rem .6rem
        li
            line-height: 1.2rem
            color: #8D8D8D
            overflow: hidden
            i
                margin-left: .15rem
        .selected
            color: $color-theme
    
    .mask
        position: fixed
        width: 100%
        height: 100%
        background: rgba(0,0,0,0.5)
        z-index: 99

</style>
