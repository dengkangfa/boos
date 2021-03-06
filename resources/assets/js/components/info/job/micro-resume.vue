<template>
    <transition name="horizontal-slide">
        <div class="my-resume-wrapper">
            <dkf-header title="我的简历">
                <div slot="left" @click="$router.back()"><i class="icon-left" style="padding: 0.3rem;"></i></div>
            </dkf-header>
            <div class="main">
                <div class="info">
                    <div class="info-item-wrapper">
                        <ul>
                            <li class="info-item" @click="editOrAddEducationInfo(item)" v-for="item in educationInfos"><label>{{ item.school }}</label><span class="item-value">{{ item.start_year + '-' + item.end_year }}<i class="icon icon-right"></i></span></li>
                            <li @click="editOrAddEducationInfo({})" class="add-item"><i class="icon-jiahao"></i> 教育经历</li>
                        </ul>
                    </div>
                    <div class="info-item-wrapper">
                        <ul>
                            <li class="info-item" @click="editOrAddWorkExperience(item)" v-for="item in workExperiences"><label>{{ item.company_name }}</label><span class="item-value">{{ item.start_time.replace(/-/, '.') + '-' + (item.end_time !== -1 ? item.end_time.replace(/-/, '.') : '至今') }}<i class="icon icon-right"></i></span></li>
                            <li @click="editOrAddWorkExperience({})" class="add-item"><i class="icon-jiahao"></i> {{ workExperienceAlias }}</li>
                        </ul>
                    </div>
                    <div class="info-item-wrapper">
                        <ul>
                            <li class="info-item" @click="editProjectExperience(item)" v-for="item in projectExperiences"><label>{{ item.name }}</label></li>
                            <li class="add-item" @click="showProjectExperience"><i class="icon-jiahao"></i> 项目经验</li>
                        </ul>
                    </div>
                    <div class="info-item-wrapper">
                        <ul>
                            <li class="info-item" @click="showSocialHomePage">
                                <label>社交主页</label>
                                <div class="item-value">
                                    <span><i v-for="homepage in user.homepages" style="margin-left: 5px" :class="$refs.socialHomePage ? $refs.socialHomePage.className(homepage) : ''"></i></span>
                                    <i class="icon icon-right"></i>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="theme-button" @click="$router.push({'name': 'job-expect-position'})" style="margin-top: 70px">
                    完成
                </div>
            </div>
            <education-form v-model="educationInfo" headerRightButtonText="保存" v-if="educationFormShowFlag" @complete="educationFormSubmit" @abandon="abandon"></education-form>
            <work-experience-form v-model="workExperience" :title="workExperienceAlias" v-if="workExperienceFormShowFlag" @complete="workExperienceFormSubmit" @abandon="abandon"></work-experience-form>
            <project-experience v-model="projectExperience" @save="projectExperienceFormSaveEvent" @deleteEvent="deletePerienceById" ref="projectExperience"></project-experience>
            <social-home-page v-model="user.homepages" @complete="HomepageFormSubmit" ref="socialHomePage"></social-home-page>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import educationForm from '../base/education-form'
  import workExperienceForm from '../base/work-experience-form'
  import socialHomePage from '../base/social-home-page'
  import projectExperience from '../base/project-experience'
  import {ERR_OK} from 'Api/config'
  import {getCurrentUserAllEducationExperience} from 'Api/education-experience'
  import {getCurrentUserAllWorkExperience} from 'Api/work-experience'
  import {getCurrentUserAllProjectExperience} from 'Api/project-experience'
  import {mapState} from 'vuex'

  export default {
    data() {
      return {
        educationFormShowFlag: false,
        workExperienceFormShowFlag: false,
        educationInfos: [],
        educationInfo: {},
        workExperiences: [],
        workExperience: {},
        projectExperiences: [],
        projectExperience: {}
      }
    },
    created() {
      getCurrentUserAllEducationExperience().then(response => {
        if (response.code === ERR_OK) {
          this.educationInfos = response.data
        }
      })
      getCurrentUserAllWorkExperience().then(response => {
        if (response.code === ERR_OK) {
          this.workExperiences = response.data
        }
      })
      getCurrentUserAllProjectExperience().then(response => {
        if (response.code === ERR_OK) {
          this.projectExperiences = response.data
        }
      })
    },
    beforeMount() {
      this.$emit('routePipe', true)
    },
    beforeDestroy() {
      this.$emit('routePipe', false)
    },
    computed: {
      ...mapState({
        user: state => state.AuthUser
      }),
      workExperienceAlias() {
        return this.user.job_date === '应届生' ? '实习经历' : '工作经历'
      }
    },
    methods: {
      showSocialHomePage() {
        this.$refs.socialHomePage.show()
      },
      showProjectExperience() {
        this.projectExperience = {}
        this.$refs.projectExperience.show()
      },
      editOrAddEducationInfo(educationInfo) {
        this.educationInfo = educationInfo
        this.educationFormShowFlag = true
      },
      editOrAddWorkExperience(workExperience) {
        this.workExperience = workExperience
        this.workExperienceFormShowFlag = true
      },
      abandon() {
        this.educationFormShowFlag = false
        this.workExperienceFormShowFlag = false
      },
      editProjectExperience(projectExperience) {
        this.projectExperience = projectExperience
        this.$refs.projectExperience.show()
      },
      educationFormSubmit(educationInfo) {
        this.educationInfos = this.educationInfos.filter((value) => {
          if (value.id === educationInfo.id) {
            return false
          }
          return true
        })
        this.educationInfos.push(educationInfo)
        this.educationInfos.sort((a, b) => {
          return a.end_year < b.end_year
        })
        this.educationFormShowFlag = false
      },
      workExperienceFormSubmit(workExperience) {
        this.workExperiences = this.workExperiences.filter((value) => {
          if (value.id === workExperience.id) {
            return false
          }
          return true
        })
        this.workExperiences.push(workExperience)
        this.workExperiences.sort((a, b) => {
          return a.start_time < b.start_time
        })
        this.workExperienceFormShowFlag = false
      },
      HomepageFormSubmit(homepages) {
        this.homepages = homepages
      },
      projectExperienceFormSaveEvent(projectExperience) {
        this.projectExperiences = this.projectExperiences.filter((value) => {
          if (value.id === projectExperience.id) {
            return false
          }
          return true
        })
        this.projectExperiences.push(projectExperience)
        this.projectExperiences.sort((a, b) => {
          return a.id < b.id
        })
      },
      deletePerienceById(id) {
        this.projectExperiences = this.projectExperiences.filter((value) => {
          if (value.id === id) {
            return false
          }
          return true
        })
      }
    },
    components: {
      dkfHeader,
      educationForm,
      workExperienceForm,
      socialHomePage,
      projectExperience
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
            position: absolute
            top: 50px
            bottom: 0
            width: 100%
            overflow-y: scroll
            -webkit-overflow-scrolling: touch
            .info
                .info-item-wrapper
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
