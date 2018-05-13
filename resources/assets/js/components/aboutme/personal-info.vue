<template>
    <transition name="horizontal-slide">
        <div class="personal-info-wrapper" v-if="showFlag">
            <dkf-header title="个人信息">
                <div slot="left" @click="hide()"><i class="icon-left"></i></div>
            </dkf-header>
            <div class="main">
                <ul class="cell">
                    <li @click="$refs.avatarDriver.show()">
                        <div class="cell-title"><span>头像</span></div>
                        <div class="cell-value is-link img-wrapper"><img :src="user.avatar ? user.avatar : 'images/default.png'"></div>
                        <i class="icon icon-right"></i>
                    </li>
                    <li @click="$refs.nameInput.show()">
                        <div class="cell-title"><span>姓名</span></div>
                        <div class="cell-value is-link"><span>{{ user.name }}</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                    <li>
                        <div class="cell-title"><span>姓别</span></div>
                        <div class="radio-group"><sex-radio v-model="user.gender" @change="genderChange"></sex-radio></div>
                    </li>
                    <li class="active" @click="$refs.jobDatePicker.show()">
                        <div class="cell-title"><span>参加工作时间</span></div>
                        <div class="cell-value is-link"><span>{{ user.job_date }}</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                    <li @click="$refs.wechatInput.show()">
                        <div class="cell-title"><span>微信号</span></div>
                        <div class="cell-value is-link"><span>{{ user.wechat ? user.wechat : '选填' }}</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                    <li class="active" @click="$refs.birthDatePicker.show()">
                        <div class="cell-title"><span>出生年月</span></div>
                        <div class="cell-value is-link"><span>{{ user.birth_date }}</span></div>
                        <i class="icon icon-right"></i>
                    </li>
                </ul>
            </div>
            <avatar-driver @succeed="avatarDriverSucceed" @showDefaultAvatarDriver="$refs.avatarDefault.show()" ref="avatarDriver"></avatar-driver>
            <avatar-cropper :image="cropImage" @cancel="cropperShowFlag = false"  @save="upload" v-if="cropperShowFlag" ref="avatarCropper"></avatar-cropper>
            <avatar-default :type="defaultAvatarType" :currentAvatar="user.avatar" @selectDefaultAvatar="selectDefaultAvatar" ref="avatarDefault"></avatar-default>
            <full-screen-input title="姓名" :maxLength="12" v-model="user.name" @saveValue="saveName" ref="nameInput"></full-screen-input>
            <full-screen-input title="微信号" :maxLength="50" v-model="user.wechat" @saveValue="saveWechat" ref="wechatInput"></full-screen-input>
            <job-date-picker v-model="user.job_date" @select="jobDateHandleSelect" ref="jobDatePicker"></job-date-picker>
            <birth-date-picker v-model="user.birth_date" @select="birthDateHandleSelect" ref="birthDatePicker"></birth-date-picker>
            <spinner text="正在保存中" v-show="spinnerShowFlag"></spinner>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import dkfHeader from 'Base/header/header'
  import scroll from 'Base/scroll/scroll'
  import {avatarMixin} from 'Mixin/mixin.js'
  import fullScreenInput from 'InfoBase/full-screen-input'
  import sexRadio from 'Base/radio/sex-radio'
  import jobDatePicker from 'Base/picker/job-date-picker'
  import birthDatePicker from 'Base/picker/birth-date-picker'
  import spinner from 'Base/spinner/spinner'

  export default {
    mixins: [avatarMixin],
    data() {
      return {
        showFlag: false,
        spinnerShowFlag: false
      }
    },
    methods: {
      show() {
        this.showFlag = true
      },
      hide() {
        this.showFlag = false
      },
      saveName(name) {
        this.save({name})
      },
      saveWechat(wechat) {
        this.save({wechat})
      },
      genderChange(gender) {
        this.save({gender})
      },
      jobDateHandleSelect(job_date)  {
        this.save({job_date})
      },
      birthDateHandleSelect(birth_date) {
        this.save({birth_date})
      },
      save(data) {
        this.spinnerShowFlag = true
        this.$store.dispatch('updateField', data).then(response => {
          this.spinnerShowFlag = false
        })
      }
    },
    components: {
      dkfHeader,
      scroll,
      fullScreenInput,
      sexRadio,
      jobDatePicker,
      birthDatePicker,
      spinner
    },
    watch: {
      showFlag(newValue) {
        newValue ? this.$emit('routePipe', true) : this.$emit('routePipe', false)
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import '../../../sass/variables'
    @import '../../../sass/mixin'

    .personal-info-wrapper
        @include allCover
        background: $bc
        z-index: 20
        .main
            .cell
                .cell-value
                    &.img-wrapper
                        display: flex
                        img
                            width: 1.2rem
                            height: 1.2rem
                            border: 2px solid #eeeeee
                            border-radius: 50%
                            box-sizing: border-box
                            margin: 10px 0
</style>