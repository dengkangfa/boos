<template>
    <transition name="horizontal-slide">
        <div class="my-resume-wrapper">
            <dkf-header title="我的简历" fixed>
                <div slot="left" @click="$router.back()"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                <div slot="right"><i class="icon-saomiao"></i></div>
            </dkf-header>
            <scroll class="_scroll">
                <main>
                    <ul class="cell">
                        <li>
                            <div class="user-info">
                                <img :src="user.avatar" width="50" height="50">
                                <div class="info">
                                    <div>{{ user.name }} | {{ user.gender }}</div>
                                    <div>编辑个人信息</div>
                                </div>
                            </div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li>
                            <div class="cell-title">管理求职意向</div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li>
                            <div class="cell-title">更新附件简历</div>
                            <i class="icon icon-right"></i>
                        </li>
                    </ul>
                    <h3 class="resume-details-title">简历详情</h3>
                    <ul class="cell">
                        <li>
                            <div class="cell-title">我的优势</div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li>
                            <div class="cell-title">社交主页</div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li>
                            <div class="cell-title">我的问答</div>
                            <i class="icon icon-right"></i>
                        </li>
                    </ul>
                    <ul class="cell">
                        <li style="justify-content: center">
                            <div class="cell-value color-theme">+ 实习经历（选填）</div>
                        </li>
                    </ul>
                    <ul class="cell">
                        <li style="justify-content: center">
                            <div class="cell-value color-theme">+ 项目经验 </div>
                        </li>
                    </ul>
                    <ul class="cell">
                        <li style="justify-content: center">
                            <div class="cell-value color-theme">+ 教育经历 </div>
                        </li>
                    </ul>
                    <div class="occupy"></div>
                </main>
            </scroll>
            <div class="theme-footer-button">
                <div>
                    预览简历
                </div>
            </div>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import scroll from 'Base/scroll/scroll'
  import {mapState} from 'vuex'

  export default {
    computed: {
      ...mapState({
        user: state => state.AuthUser
      })
    },
    components: {
      dkfHeader,
      scroll
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import '../../../sass/variables'
    @import '../../../sass/mixin'

    .my-resume-wrapper
        position: fixed
        width: 100%
        left: 0
        top: 50px
        bottom: 55px
        background: $bc
        main
            .occupy
                height: 1px
                &::before
                    content: ' '
            .cell
                padding: 0 10px
                background: #ffffff
                margin-bottom: 8px
                li
                    padding: 0
                .user-info
                    display: flex
                    flex: 1
                    align-items: center
                    height: 70px
                    line-height: .5rem
                    font-size: .4rem
                    img
                        border-radius: 50%
                        margin-right: 20px
                .work-experience
                    justify-content: center
                    div
                        color: $color-theme !important
        .resume-details-title
            padding: 5px
            color: rgba(0, 0, 0, 0.5)
</style>