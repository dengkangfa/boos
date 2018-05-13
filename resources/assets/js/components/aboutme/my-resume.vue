<template>
    <transition name="horizontal-slide">
        <div class="my-resume-wrapper">
            <dkf-header title="我的简历" class="header _effect" :class="{'_effect--30': decline}" fixed>
                <div slot="left" @click="$router.back()"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                <div slot="right"><i class="icon-saomiao"></i></div>
            </dkf-header>
            <scroll class="main-wrapper _effect" :class="{'_effect--30': decline}">
                <main>
                    <ul class="cell">
                        <li @click="$refs.personalInfo.show()">
                            <div class="user-info">
                                <img :src="user.avatar" width="50" height="50">
                                <div class="info">
                                    <div>{{ user.name }} | {{ user.gender }}</div>
                                    <div>编辑个人信息</div>
                                </div>
                            </div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="$refs.expectPositionManage.show()">
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
            <div class="theme-footer-button _effect" :class="{'_effect--30': decline}">
                <div>
                    预览简历
                </div>
            </div>
            <personal-info ref="personalInfo" @routePipe="routePipe"></personal-info>
            <expect-position-manage ref="expectPositionManage" @routePipe="routePipe"></expect-position-manage>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import scroll from 'Base/scroll/scroll'
  import {mapState} from 'vuex'
  import personalInfo from './personal-info'
  import expectPositionManage from './expect-position-manage'

  export default {
    data() {
      return {
        decline: false,
      }
    },
    computed: {
      ...mapState({
        user: state => state.AuthUser
      })
    },
    methods: {
      routePipe(_decline) {
        this.decline = _decline
      },
    },
    components: {
      dkfHeader,
      scroll,
      personalInfo,
      expectPositionManage
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import '../../../sass/variables'
    @import '../../../sass/mixin'

    .my-resume-wrapper
        @include allCover
        background: $bc
        .header
            z-index: 10
        .main-wrapper
            position: absolute
            top: 50px
            bottom: 55px
            width: 100%
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