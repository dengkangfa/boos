<template>
    <div class="row" @change="$emit('change', currentValue)">
        <div class="female">
            <input type="radio" id="female" v-model="currentValue" value="female" name="sex" />
            <label for="female">女</label>
        </div>
        <div class="male">
            <input type="radio" id="male" v-model="currentValue" value="male" name="sex" />
            <label for="male">男</label>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
  export default {
    props: {
      value: ''
    },
    created() {
      this.currentValue = this.value
    },
    data() {
      return {
        currentValue: ''
      }
    },
    watch: {
      value() {
        this.currentValue = this.value
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    .row
        display: flex
        flex-wrap: wrap
        .female
            margin-right: 0.4rem
    input[type="radio"] + label::before
        content: "\a0"
        display: inline-block
        width: 0.2rem
        height: 0.2rem
        vertical-align: middle
        margin-right: .4em
        border-radius: 50%
        border: 1.5px solid #d0cfc1
        text-indent: .15em
        line-height: 1
    label
        padding: 5px 0
    input[type="radio"]
        position: absolute
        clip: rect(0, 0, 0, 0)
    .female input[type="radio"]:checked + label::before
        border: 1.5px solid #ffaba6
        background-clip: content-box
    .male input[type="radio"]:checked + label::before
        border: 1.5px solid #87e1fa
        background-clip: content-box

</style>