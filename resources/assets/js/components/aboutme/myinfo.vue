<template>
    <div class="my-info-wrapper">
        <div class="top-tool">
            <span><i class="icon-saomiao"></i></span>
            <span @click="$refs.setting.show()"><i class="icon-shezhi"></i></span>
        </div>
        <scroll class="my-info">
            <main>
                <div class="head">
                    <div class="head-t">
                        <div class="user-info">
                            <div class="name">
                                <h3>{{ user.name }}</h3>
                                <p class="resume"><span>查看并修改个人微简历</span><i class="icon-right"></i></p>
                            </div>
                            <div class="avatar">
                                <img :src="user.avatar" width="50" height="50">
                            </div>
                        </div>
                    </div>
                    <div class="head-b">
                        <ul>
                            <li>
                                <span>1</span>
                                <span>沟通过</span>
                            </li>
                            <li>
                                <span>1</span>
                                <span>面试</span>
                            </li>
                            <li>
                                <span>0</span>
                                <span>已投递</span>
                            </li>
                            <li>
                                <span>0</span>
                                <span>感兴趣</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="props">
                    <div>
                        <div class="title"><span>职场竞争力分析器</span></div>
                        <div class="description"><span>清楚掌握你的职场竞争力</span></div>
                    </div>
                    <div>
                        <span><i class="icon-right"></i></span>
                    </div>
                </div>
                <ul class="user-info-cell">
                    <li>
                        <div class="user-info-cell-title"><span><i class="icon-portrait"></i>附件简历</span></div>
                        <div class="user-info-cell-value is-link"><span>未上传</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                    <li>
                        <div class="user-info-cell-title"><span>管理求职意向</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                    <li>
                        <div class="user-info-cell-title"><span>牛人问答</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                    <li>
                        <div class="user-info-cell-title"><span>关注公司</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                    <li>
                        <div class="user-info-cell-title"><span>钱包</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                    <li>
                        <div class="user-info-cell-title"><span>帮助与反馈</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                    <li>
                        <div class="user-info-cell-title"><span>隐私设置</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                </ul>
                <div class="landline">
                    <p>客户电话 400-065-5799</p>
                    <p>工作时间 9:30-18:30</p>
                </div>
            </main>
        </scroll>
        <setting ref="setting"></setting>
    </div>
</template>

<script type="text/ecmascript-6">
  import {mapState} from 'vuex'
  import scroll from 'Base/scroll/scroll'
  import setting from '../setting/setting'

  export default {
    created() {
      if (!this.user.authenticated) {
        this.$store.dispatch('setAuthUser').then(response => {
        })
      }
    },
    computed: {
      ...mapState({
        user: state => state.AuthUser
      })
    },
    components: {
      scroll,
      setting
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import '../../../sass/variables'
    @import '../../../sass/mixin'

    .my-info-wrapper
        position: fixed
        width: 100%
        left: 0
        top: 0
        bottom: 50px
        background: $bc
        .top-tool
            position: fixed
            display: flex
            justify-content: space-between
            top: 0
            z-index: 10
            width: 100%
            color: #FFFFFF
            background: $color-theme
            font-size: .5rem
            box-sizing: border-box
            padding: 20px
        .my-info
            height: 100%
            overflow: hidden
            .head
                margin-bottom: 10px
                .head-t
                    height: 4rem
                    color: #FFFFFF
                    background: $color-theme
                    padding: 20px
                    box-sizing: border-box
                    .user-info
                        display: flex
                        align-items: center
                        margin-top: 50px
                        .name
                            flex: 1
                            h3
                                font-size: .6rem
                                font-weight: bold
                                margin-bottom: 20px
                            .resume
                                i
                                    margin-left: 5px
                        .avatar
                            img
                                border-radius: 50%
                .head-b
                    background: #FFFFFF
                    ul
                        display: flex
                        justify-content: space-around
                        line-height: .5rem
                        padding: 10px 0
                        li
                            display: flex
                            align-items: center
                            flex-direction: column
                            span:first-child
                                font-size: .4rem
                                font-weight: bold
                            span:last-child
                                font-size: .35rem
                                color: $color-text-l
            .props
                display: flex
                justify-content: space-between
                align-items: center
                color: $color-text-d
                font-size: .3rem
                line-height: .6rem
                background: #ffffff
                padding: 20px
                margin-bottom: 10px
                .title
                    font-size: .4rem
                    color: #000
            .user-info-cell
                background: #FFFFFF
                padding: 0 20px
                li
                    display: flex
                    align-items: center
                    height: 50px
                    line-height: 50px
                    color: $color-text-d
                    &:not(:first-child)
                        @include border-top-1px($bc)
                    .user-info-cell-title
                        flex: 1
                        color: $color-text-l
                        font-size: .4rem
                        i
                            margin-right: 10px
                            font-size: .45rem
            .landline
                padding: 20px
                text-align: center
                line-height: .5rem
                color: $color-text-d
</style>