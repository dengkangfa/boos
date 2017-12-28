<template>
    <transition name="slide">
        <div class="basic-info">
            <dkf-header title="个人信息" @left="back" nextText="下一步"></dkf-header>
            <div class="avatar-wrapper active" @click="showAvatarDriver">
                <img src="https://img2.bosszhipin.com/boss/avatar/avatar_15.png" class="avatar">
            </div>
            <ul class="basic-info-items">
                <li class="active" @click="showNameInput"><label class="item-name">姓名</label><span class="item-value">{{ userData.name }} <i class="icon icon-right"></i></span></li>
                <li><label class="item-name">性别</label><div class="radio-group"><sex-radio @change="sexChange"></sex-radio> </div></li>
                <li class="active" @click="showPicker"><label class="item-name">参加工作时间</label><span class="item-value">{{ userData.jobDate }} <i class="icon icon-right"></i></span></li>
                <li class="active" @click="showBirthDatePicker"><label class="item-name">出生年月</label><span class="item-value">{{ userData.birthDate }} <i class="icon icon-right"></i></span></li>
            </ul>
            <div class="basic-info-bottom">
                <span class="disc">创建一份微简历，高薪职位触手可得</span>
                <div class="basic-info-next">下一步</div>
            </div>
            <avatar-driver ref="avatarDriver"></avatar-driver>
            <name-input ref="nameInput" :name="userData.name" @saveName="saveName"></name-input>
            <job-date-picker ref="picker" v-model="userData.jobDate" @select="jobDateHandleSelect"></job-date-picker>
            <birth-date-picker ref="birthDatePicker" v-model="userData.birthDate" @select="birthDateHandleSelect"></birth-date-picker>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import avatarDriver from 'Base/avatar/avatar-driver'
  import nameInput from '../base/name-input'
  import sexRadio from 'Base/radio/sex-radio'
  import jobDatePicker from 'Base/picker/job-date-picker'
  import birthDatePicker from 'Base/picker/birth-date-picker'

  export default {
    data() {
      return {
        userData: {
          name: '',
          sex: '',
          jobDate: '2016-06',
          birthDate: '1997-02'
        }
      }
    },
    methods: {
      back() {
        this.$router.back()
        console.log(this.userData)
      },
      showAvatarDriver() {
        this.$refs.avatarDriver.show()
      },
      showNameInput() {
        this.$refs.nameInput.show()
      },
      saveName(nameValue) {
        this.userData.name = nameValue
      },
      sexChange(currentValue) {
        this.userData.sex = currentValue
      },
      showPicker() {
        this.$refs.picker.show()
      },
      showBirthDatePicker() {
        this.$refs.birthDatePicker.show()
      },
      jobDateHandleSelect(data) {
        this.userData.jobDate = data
      },
      birthDateHandleSelect(data) {
        this.userData.birthDate = data
      }
    },
    components: {
      dkfHeader,
      avatarDriver,
      nameInput,
      sexRadio,
      jobDatePicker,
      birthDatePicker
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .basic-info
        @include allCover()
        background: #e9efef
        .active:active
            background: #d9d9d9
        .avatar-wrapper
            text-align: center
            padding: 0.7rem
            background: #ffffff
            .avatar
                width: 1.7rem
                height: 1.7rem
                border-radius: 50%
        .basic-info-items
            li
                display: flex
                justify-content: space-between
                @include border-top-1px(rgb(238, 238, 238))
                padding: 0.45rem 0.3rem
                background: #ffffff
                .item-value
                    color: $color-text-d
                i.icon
                    color: $color-theme
        .basic-info-bottom
            position: absolute
            width: 100%
            text-align: center
            color: $color-text-d
            bottom: 0
            .disc
                display: block
                margin-bottom: 0.4rem
            .basic-info-next
                width: 90%
                font-size: 0.45rem
                text-align: center
                background: $color-theme
                border-radius: 0.15rem
                color: $color-text
                padding: 15px 0
                margin: 0 auto 0.7rem

    .slide-enter-active, .slide-leave-active
        transition: all 0.3s
    .slide-enter, .slide-leave-to
        transform: translate3d(100%, 0, 0)
</style>