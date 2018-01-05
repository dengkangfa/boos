<template>
    <transition name="slide">
        <div class="basic-info">
            <dkf-header title="个人信息" @left="back" nextText="下一步" @right="next"></dkf-header>
            <div class="avatar-wrapper active" @click="showAvatarDriver">
                <div class="img-wrapper">
                    <img :src="user.avatar ? user.avatar : 'images/default.png'" class="avatar">
                    <i class="icon icon-camera-outline" v-show="!user.avatar"></i>
                    <div class="tooltip" v-show="!user.avatar">
                        上传真实头像，<br>职位查看率增加20%
                    </div>
                </div>
            </div>
            <ul class="basic-info-items">
                <li class="active" @click="showNameInput"><label class="item-name">姓名</label><span class="item-value">{{ userData.name }} <i class="icon icon-right"></i></span></li>
                <li><label class="item-name">性别</label><div class="radio-group"><sex-radio v-model="userData.gender" @change="genderChange"></sex-radio> </div></li>
                <li class="active" @click="showJobDatePicker"><label class="item-name">参加工作时间</label><span class="item-value">{{ userData.job_date }} <i class="icon icon-right"></i></span></li>
                <li class="active" @click="showBirthDatePicker"><label class="item-name">出生年月</label><span class="item-value">{{ userData.birth_date }} <i class="icon icon-right"></i></span></li>
            </ul>
            <div class="basic-info-bottom">
                <span class="disc">创建一份微简历，高薪职位触手可得</span>
                <div class="basic-info-next" @click="next">下一步</div>
            </div>
            <avatar-driver @succeed="avatarDriverSucceed" @showDefaultAvatarDriver="showDefaultAvatarDriver" ref="avatarDriver"></avatar-driver>
            <avatar-cropper  :image="cropImage" @cancel="hideCropper"  @save="upload" v-if="cropperShowFlag" ref="avatarCropper"></avatar-cropper>
            <avatar-default :type="defaultAvatarType" :currentAvatar="user.avatar" @selectDefaultAvatar="selectDefaultAvatar" ref="avatarDefault"></avatar-default>
            <name-input title="姓名" :maxLength="12" v-model="userData.name" @saveValue="saveName" ref="nameInput"></name-input>
            <job-date-picker v-model="userData.job_date" @select="jobDateHandleSelect" ref="jobDatePicker"></job-date-picker>
            <birth-date-picker v-model="userData.birth_date" @select="birthDateHandleSelect" ref="birthDatePicker"></birth-date-picker>
            <spinner :text="spinnerText" v-show="spinner"></spinner>
            <message :message="message" ref="message"></message>
            <router-view></router-view>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import avatarDriver from 'Base/avatar/avatar-driver'
  import avatarDefault from 'Base/avatar/avatar-default'
  import avatarCropper from 'Base/avatar/avatar-cropper'
  import nameInput from '../base/full-screen-input'
  import sexRadio from 'Base/radio/sex-radio'
  import jobDatePicker from 'Base/picker/job-date-picker'
  import birthDatePicker from 'Base/picker/birth-date-picker'
  import spinner from 'Base/spinner/spinner'
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
      if (!this.user.authenticated) {
        this.$store.dispatch('setAuthUser').then(response => {
          this.init()
        })
      } else {
        this.init()
      }
    },
    computed: {
      ...mapState({
        user: state => state.AuthUser
      })
    },
    methods: {
      init() {
        this.userData.name = this.user.name
        this.userData.gender = this.user.gender
        this.userData.job_date = this.user.job_date
        this.userData.birth_date = this.user.birth_date
      },
      back() {
        this.$router.back()
      },
      next() {
        // 验证数据合法性
        if (this.checkData()) {
          this.spinnerText = '正在保存个人信息，请稍后'
          this.spinner = true
          this.$store.dispatch('updateProflie', this.userData).then(response => {
            this.spinner = false
            if (this.userData.job_date === '应届生') {
              this.$router.push({'name': 'job-education'})
            } else {
              this.$router.push({'name': 'job-work-experience'})
            }
          }).catch(error => {
            this.spinner = false
            if (error.code === ERR_UNPROCESSABLE_ENTITY) {
              this.message = error.message
              this.$refs.message.show()
            }
          })
        }
      },
      // 验证数据
      checkData() {
        if (!this.user.avatar) {
          this.message = '请上传您的头像'
        } else if (!this.userData.name) {
          this.message = '请填写您的姓名'
        } else if (!this.userData.gender) {
          this.message = '请选择您的性别'
        } else if (!this.userData.job_date) {
          this.message = '请选择参加工作时间'
        } else if (!this.userData.birth_date) {
          this.message = '请选择出生年月'
        } else {
          return true
        }
        this.$refs.message.show()
        return false
      },
      // 显示头像设置组件
      showAvatarDriver() {
        this.$refs.avatarDriver.show()
      },
      // 显示默认头像设置组件
      showDefaultAvatarDriver() {
        this.$refs.avatarDefault.show()
      },
      // 显示姓名设置组件
      showNameInput() {
        this.$refs.nameInput.show()
      },
      saveName(nameValue) {
        this.userData.name = nameValue
      },
      genderChange(currentValue) {
        this.userData.gender = currentValue
      },
      // 参加工作日期选择器
      showJobDatePicker() {
        this.$refs.jobDatePicker.show()
      },
      // 出生年月日期选择器
      showBirthDatePicker() {
        this.$refs.birthDatePicker.show()
      },
      jobDateHandleSelect(data) {
        this.userData.job_date = data
      },
      birthDateHandleSelect(data) {
        this.userData.birth_date = data
      },
      // 头像上传成功回调
      avatarDriverSucceed(data) {
        this.cropImage = data
        this.cropperShowFlag = true
      },
      // 隐藏裁剪组件
      hideCropper() {
        this.cropperShowFlag = false
      },
      // 上传头像
      upload(data) {
        this.cropperShowFlag = false
        this.spinnerText = '上传中'
        this.spinner = true
        this.$store.dispatch('uploadAvatar', data).then(response => {
          this.spinner = false
        })
      },
      // 上传默认头像
      selectDefaultAvatar(avatar) {
        this.spinnerText = '上传中'
        this.spinner = true
        this.$store.dispatch('updateDefaultAvatar', avatar).then(response => {
          this.spinner = false
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
      spinner,
      message
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .basic-info
        @include allCover()
        font-size: .35rem
        background: $bc
        .active:active
            background: #d9d9d9
        .avatar-wrapper
            position: relative
            display: flex
            text-align: center
            padding: 0.7rem
            background: #ffffff
            .img-wrapper
                position: relative
                width: 1.7rem
                height: 1.7rem
                margin: auto
                .avatar
                    width: 1.7rem
                    height: 1.7rem
                    border-radius: 50%
                .icon
                    position: absolute
                    top: 0
                    right: 0
                    color: #4cafab
                    background: #fff
                    border-radius: 50%
                    padding: 4px
                    transform: translate3d(25%, 25%, 0)
                .tooltip
                    text-align: left
                    position: absolute
                    top: 50%
                    left: 100%
                    width: 2rem
                    font-size: .2rem
                    line-height: .4rem
                    color: #fff
                    background: #7f7f7f
                    border-radius: .1rem
                    padding: 0.15rem
                    margin-left: 20px
                    transform: translateY(-50%)
                    &:before
                        position: absolute
                        display: block
                        width: 0
                        height: 0
                        border: 10px solid transparent
                        pointer-events: none
                        content: ""
                        border-right-color: #7f7f7f
                        transform: translate3d(-100%, -50%, 0)
                        top: 50%
        .basic-info-items
            li
                display: flex
                justify-content: space-between
                @include border-top-1px($bc)
                padding: 0.45rem 0.3rem
                background: #ffffff
                .item-value
                    color: $color-text-d
                i.icon
                    font-size: .3rem
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