<template>
    <transition name="slide">
        <div class="basic-info">
            <dkf-header title="个人信息" @left="back" nextText="下一步" @right="next"></dkf-header>
            <div class="avatar-wrapper active" @click="showAvatarDriver">
                <img :src="user.avatar ? user.avatar : 'images/default.png'" class="avatar">
            </div>
            <ul class="basic-info-items">
                <li class="active" @click="showNameInput"><label class="item-name">姓名</label><span class="item-value">{{ userData.name }} <i class="icon icon-right"></i></span></li>
                <li><label class="item-name">性别</label><div class="radio-group"><sex-radio v-model="userData.gender" @change="genderChange"></sex-radio> </div></li>
                <li class="active" @click="showPicker"><label class="item-name">参加工作时间</label><span class="item-value">{{ userData.job_date }} <i class="icon icon-right"></i></span></li>
                <li class="active" @click="showBirthDatePicker"><label class="item-name">出生年月</label><span class="item-value">{{ userData.birth_date }} <i class="icon icon-right"></i></span></li>
            </ul>
            <div class="basic-info-bottom">
                <span class="disc">创建一份微简历，高薪职位触手可得</span>
                <div class="basic-info-next" @click="next">下一步</div>
            </div>
            <avatar-driver @succeed="avatarDriverSucceed" @showDefaultAvatarDriver="showDefaultAvatarDriver" ref="avatarDriver"></avatar-driver>
            <avatar-cropper  :image="cropImage" @cancel="hideCropper"  @save="upload" v-if="cropperShowFlag" ref="avatarCropper"></avatar-cropper>
            <avatar-default :type="defaultAvatarType" :currentAvatar="user.avatar" @selectDefaultAvatar="selectDefaultAvatar" ref="avatarDefault"></avatar-default>
            <name-input :name="userData.name" @saveName="saveName" ref="nameInput"></name-input>
            <job-date-picker v-model="userData.job_date" @select="jobDateHandleSelect" ref="picker"></job-date-picker>
            <birth-date-picker v-model="userData.birth_date" @select="birthDateHandleSelect" ref="birthDatePicker"></birth-date-picker>
            <fading-circle :text="spinnerText" v-show="spinner"></fading-circle>
            <message :message="message" ref="message"></message>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import avatarDriver from 'Base/avatar/avatar-driver'
  import avatarDefault from 'Base/avatar/avatar-default'
  import avatarCropper from 'Base/avatar/avatar-cropper'
  import nameInput from '../base/name-input'
  import sexRadio from 'Base/radio/sex-radio'
  import jobDatePicker from 'Base/picker/job-date-picker'
  import birthDatePicker from 'Base/picker/birth-date-picker'
  import fadingCircle from 'Base/spinner/fading-circle'
  import message from 'Base/message/message'
  import {mapState} from 'vuex'
  import {ERR_UNPROCESSABLE_ENTITY} from 'Api/config'

  export default {
    data() {
      return {
        userData: {
          name: '',
          gender: '',
          job_date: '',
          birth_date: ''
        },
        cropImage: {},
        cropperShowFlag: false,
        spinner: false,
        spinnerText: '',
        message: '',
        defaultAvatarType: 1
      }
    },
    created() {
      this.userData.name = this.user.name
      this.userData.gender = this.user.gender
      this.userData.job_date = this.user.job_date
      this.userData.birth_date = this.user.birth_date
    },
    computed: {
      ...mapState({
        user: state => state.AuthUser
      })
    },
    methods: {
      back() {
        this.$router.back()
      },
      next() {
        this.spinnerText = '正在保存个人信息，请稍后'
        this.spinner = true
        this.$store.dispatch('updateProflie', this.userData).then(response => {
          this.spinner = false
        }).catch(error => {
          this.spinner = false
          if (error.code === ERR_UNPROCESSABLE_ENTITY) {
            this.message = error.message
            this.$refs.message.show()
          }
        })
      },
      showAvatarDriver() {
        this.$refs.avatarDriver.show()
      },
      showDefaultAvatarDriver() {
        this.$refs.avatarDefault.show()
      },
      showNameInput() {
        this.$refs.nameInput.show()
      },
      saveName(nameValue) {
        this.userData.name = nameValue
      },
      genderChange(currentValue) {
        this.userData.gender = currentValue
      },
      showPicker() {
        this.$refs.picker.show()
      },
      showBirthDatePicker() {
        this.$refs.birthDatePicker.show()
      },
      jobDateHandleSelect(data) {
        this.userData.job_date = data
      },
      birthDateHandleSelect(data) {
        this.userData.birth_date = data
      },
      avatarDriverSucceed(data) {
        this.cropImage = data
        this.cropperShowFlag = true
      },
      hideCropper() {
        this.cropperShowFlag = false
      },
      upload(data) {
        this.cropperShowFlag = false
        this.spinnerText = '上传中'
        this.spinner = true
        this.$store.dispatch('uploadAvatar', data).then(response => {
          this.spinner = false
        })
      },
      selectDefaultAvatar(avatar) {
        this.$store.dispatch('updateDefaultAvatar', avatar).then(response => {

        })
      }
    },
    components: {
      dkfHeader,
      avatarDriver,
      avatarDefault,
      avatarCropper,
      nameInput,
      sexRadio,
      jobDatePicker,
      birthDatePicker,
      fadingCircle,
      message
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