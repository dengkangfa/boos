<template>
    <div class="spinner-wrapper">
        <div class="spinner-fading-circle circle-color" >
            <div class="fading-circle-circle is-circle"></div>
            <div class="fading-circle-circle is-circle2"></div>
            <div class="fading-circle-circle is-circle3"></div>
            <div class="fading-circle-circle is-circle4"></div>
            <div class="fading-circle-circle is-circle5"></div>
            <div class="fading-circle-circle is-circle6"></div>
            <div class="fading-circle-circle is-circle7"></div>
            <div class="fading-circle-circle is-circle8"></div>
            <div class="fading-circle-circle is-circle9"></div>
            <div class="fading-circle-circle is-circle10"></div>
            <div class="fading-circle-circle is-circle11"></div>
            <div class="fading-circle-circle is-circle12"></div>
        </div>
        <span class="spinner-fading-circle-span">{{ text }}</span>
    </div>

</template>

<script type="text/ecmascript-6">
  export default {
    props: {
      text: {
        type: String,
        default: '正在加载中'
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/stylus" scoped>

    .spinner-wrapper
        position: absolute
        z-index: 200
        top: 45%
        left: 50%
        transform: translate(-50%, -50%)
        width: 100px
        height: 100px
        border-radius: 0.2rem
        background: rgba(40, 47, 52, 0.7)
    .spinner-fading-circle
        position: relative
        width: 1rem
        height: 1rem
        margin: 20% auto
    .circle-color > div::before
        background-color: #42b983
    .fading-circle-circle
        width: 100%
        height: 100%
        top: 0
        left: 0
        position: absolute
    .fading-circle-circle::before
        content: " "
        display: block
        margin: 0 auto
        width: 15%
        height: 15%
        border-radius: 100%
        -webkit-animation: mint-fading-circle 1.2s infinite ease-in-out both
        animation: mint-fading-circle 1.2s infinite ease-in-out both
    .fading-circle-circle.is-circle2
        transform: rotate(30deg)
    .fading-circle-circle.is-circle2::before
        animation-delay: -1.1s
    .fading-circle-circle.is-circle3
        transform: rotate(60deg)
    .fading-circle-circle.is-circle3::before
        animation-delay: -1s
    .fading-circle-circle.is-circle4
        transform: rotate(90deg)
    .fading-circle-circle.is-circle4::before
        animation-delay: -0.9s
    .fading-circle-circle.is-circle5
        transform: rotate(120deg)
    .fading-circle-circle.is-circle5::before
        animation-delay: -0.8s
    .fading-circle-circle.is-circle6
        transform: rotate(150deg)
    .fading-circle-circle.is-circle6::before
        animation-delay: -0.7s
    .fading-circle-circle.is-circle7
        transform: rotate(180deg)
    .fading-circle-circle.is-circle7::before
        animation-delay: -0.6s
    .fading-circle-circle.is-circle8
        transform: rotate(210deg)
    .fading-circle-circle.is-circle8::before
        animation-delay: -0.5s
    .fading-circle-circle.is-circle9
        transform: rotate(240deg)
    .fading-circle-circle.is-circle9::before
        animation-delay: -0.4s
    .fading-circle-circle.is-circle10
        transform: rotate(270deg)
    .fading-circle-circle.is-circle10::before
        animation-delay: -0.3s
    .fading-circle-circle.is-circle11
        transform: rotate(300deg)
    .fading-circle-circle.is-circle11::before
        animation-delay: -0.2s
    .fading-circle-circle.is-circle12
        transform: rotate(330deg)
    .fading-circle-circle.is-circle12::before
        animation-delay: -0.1s
    .spinner-fading-circle-span
        display: inline-block
        width: 100%
        color: #fff
        text-align: center

    @keyframes mint-fading-circle
        0%, 39%, 100%
            opacity: 0
        40%
            opacity: 1
</style>