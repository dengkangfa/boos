<template>
    <transition name="horizontal-slide">
        <div class="my-resume-wrapper">
            <dkf-header title="我的简历">
                <div slot="left" @click="$router.back()"><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="main">
                <div class="info">
                    <div>
                        <ul>
                            <li class="info-item" @click="editEducationInfo(item)" v-for="item in educationData"><label>{{ item.school }}</label><span class="item-value">{{ item.start_year + '-' + item.end_year }}<i class="icon icon-right"></i></span></li>
                            <li class="add-item"><i class="icon-jiahao"></i> 教育经历</li>
                        </ul>
                    </div>
                    <div>
                        <ul>
                            <li class="add-item"><i class="icon-jiahao"></i> 实习经历</li>
                        </ul>
                    </div>
                    <div>
                        <ul>
                            <li class="add-item"><i class="icon-jiahao"></i> 项目经验</li>
                        </ul>
                    </div>
                    <div>
                        <ul>
                            <li class="info-item"><label>社交主页</label><span class="item-value"><i class="icon icon-right"></i></span></li>
                        </ul>
                    </div>
                </div>
                <div class="theme-button" style="margin-top: 70px">
                    完成
                </div>
            </div>
            <education-form v-model="educationInfo" v-if="educationFormShowFlag" @abandon="abandon"></education-form>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import educationForm from '../base/education-form'
  import {ERR_OK} from 'Api/config'
  import {getCurrentUserAllEducationExperience} from 'Api/education-experience'

  export default {
    data() {
      return {
        educationFormShowFlag: false,
        educationData: [],
        educationInfo: {}
      }
    },
    created() {
      getCurrentUserAllEducationExperience().then(response => {
        if (response.code === ERR_OK) {
          this.educationData = response.data
        }
      })
    },
    beforeMount() {
      this.$emit('routePipe', true)
    },
    beforeDestroy() {
      this.$emit('routePipe', false)
    },
    methods: {
      editEducationInfo(educationInfo) {
        this.educationInfo = educationInfo
        this.educationFormShowFlag = true
      },
      abandon() {
        this.educationFormShowFlag = false
      }
    },
    components: {
      dkfHeader,
      educationForm
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .my-resume-wrapper
        @include allCover
        background: $bc
        .main
            .info
                div
                    width: 100%
                    background: #ffffff
                    box-sizing: border-box
                    padding: 0 10px
                    margin-bottom: 10px
                    ul
                        li
                            height: 1.5rem
                            line-height: 1.5rem
                            text-align: center
                            @include border-bottom-1px($bc)
                            &.add-item
                                color: $color-theme
                                font-size: .4rem
                            &.info-item
                                display: flex
                                justify-content: space-between
                                .item-value
                                    color: $color-text-l
                                i.icon
                                    font-size: .3rem
                                    color: $color-theme
                                    margin-left: .3rem
</style>
