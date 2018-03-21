<template>
    <transition name="horizontal-slide">
        <div class="boss-basic-info">
            <div class="_effect" :class="{'_effect--30': decline}" style="height: 100%;">
                <dkf-header title="Boss个人信息">
                    <div slot="left" @click="$router.back()"><i class="icon-left" style="padding: 0.3rem;"></i></div>
                    <div slot="right" @click="complete">完成</div>
                </dkf-header>
                <main>
                    <ul class="cell">
                        <li @click="$refs.avatarDriver.show()">
                            <div class="cell-title"><span>头像</span></div>
                            <div class="cell-value is-link img-wrapper"><img :src="user.avatar ? user.avatar : 'images/default.png'"></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="$refs.nameFullScreenInput.show()">
                            <div class="cell-title"><span>姓名</span></div>
                            <div class="cell-value is-link"><span>{{ user.name }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                    </ul>
                    <ul class="cell">
                        <li @click="$refs.myCompany.show()">
                            <div class="cell-title"><span>我的公司</span></div>
                            <div class="cell-value is-link"><span>{{ company.id ? company.abbreviation + '(' + company.industry_str + ')' : '您当前就职的公司' }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                    </ul>
                    <ul class="cell">
                        <li @click="$refs.positionNameFullScreenInput.show()">
                            <div class="cell-title"><span>我的职务</span></div>
                            <div class="cell-value is-link"><span>{{ user.pos_name ? user.pos_name : '您在公司内担任的职务' }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                        <li @click="$refs.emailFullScreenInput.show()">
                            <div class="cell-title"><span>我的邮箱（接收简历用）</span></div>
                            <div class="cell-value email is-link"><span>{{ user.email ? user.email : '选填，您的邮箱' }}</span></div>
                            <i class="icon icon-right"></i>
                        </li>
                    </ul>
                    <div class="theme-button" @click="complete" style="margin-top: 30px">
                        完成
                    </div>
                </main>
            </div>
            <avatar-driver @succeed="avatarDriverSucceed" @showDefaultAvatarDriver="$refs.avatarDefault.show()" ref="avatarDriver"></avatar-driver>
            <avatar-cropper  :image="cropImage" @cancel="cropperShowFlag = false"  @save="upload" v-if="cropperShowFlag" ref="avatarCropper"></avatar-cropper>
            <avatar-default :type="defaultAvatarType" :currentAvatar="user.avatar" @selectDefaultAvatar="selectDefaultAvatar" ref="avatarDefault"></avatar-default>
            <full-screen-input title="姓名" :maxLength="12" v-model="user.name" @saveValue="user.name = arguments[0]" ref="nameFullScreenInput"></full-screen-input>
            <full-screen-input title="我的职务" :maxLength="12" v-model="user.pos_name" @saveValue="user.pos_name = arguments[0]" ref="positionNameFullScreenInput"></full-screen-input>
            <full-screen-input title="接收简历邮箱" :maxLength="64" filter="email" v-model="user.email" @saveValue="user.email = arguments[0]" ref="emailFullScreenInput"></full-screen-input>
            <my-company :maxLength="46" ref="myCompany"></my-company>
            <spinner :text="spinnerText" v-if="spinnerShowFlag"></spinner>
            <message :message="message" ref="message"></message>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import {mapState} from 'vuex'
  import dkfHeader from 'Base/header/header'
  import avatarDriver from 'Base/avatar/avatar-driver'
  import avatarDefault from 'Base/avatar/avatar-default'
  import avatarCropper from 'Base/avatar/avatar-cropper'
  import spinner from 'Base/spinner/spinner'
  import fullScreenInput from '../base/full-screen-input'
  import message from 'Base/message/message'
  import myCompany from './my-company/my-company'

  const keys = ['name', 'company_id', 'pos_name', 'email']

  export default {
    data() {
      return {
        decline: false,
        cropImage: {},
        cropperShowFlag: false,
        spinnerText: '',
        message: '',
        spinnerShowFlag: false,
        defaultAvatarType: 0
      }
    },
    created() {
      this.$store.dispatch('setAuthUserWithCompany').then(response => {

      })
    },
    computed: {
      ...mapState({
        user: state => state.AuthUser,
        company: state => state.Company
      })
    },
    methods: {
      // 头像上传成功回调
      avatarDriverSucceed(data) {
        this.cropImage = data
        this.cropperShowFlag = true
      },
      // 上传头像
      upload(data) {
        this.cropperShowFlag = false
        this.spinnerText = '上传中'
        this.spinnerShowFlag = true
        this.$store.dispatch('uploadAvatar', data).then(response => {
          this.spinnerShowFlag = false
        })
      },
      // 上传默认头像
      selectDefaultAvatar(avatar) {
        this.spinnerText = '上传中'
        this.spinnerShowFlag = true
        this.$store.dispatch('updateDefaultAvatar', avatar).then(response => {
          this.spinnerShowFlag = false
        })
      },
      complete() {
        if (this._checkData()) {
          this.spinnerText = '保存中'
          this.spinnerShowFlag = true
          this.$store.dispatch('updateProflie', this._formatUser(this.user)).then(response => {
            this.spinnerShowFlag = false
          })
        }
      },
      _formatUser(data) {
        let newData = {}
        for (let key in data) {
          if (keys.indexOf(key) >= 0) {
            newData[key] = data[key]
          }
        }
        return newData
      },
      _checkData() {
        if (!this.user.avatar) {
          this.message = '头像不能为空'
        } else if (!this.user.name) {
          this.message = '姓名不能为空'
        } else if (!this.company) {
          this.message = '当前公司不能为空'
        } else if (!this.user.pos_name) {
          this.message = '我的职务不能为空'
        } else {
          return true
        }
        this.$refs.message.show()
        return false
      }
    },
    components: {
      dkfHeader,
      avatarDriver,
      avatarDefault,
      avatarCropper,
      spinner,
      fullScreenInput,
      myCompany,
      message
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../../sass/variables"
    @import "../../../../sass/mixin"

    .boss-basic-info
        @include allCover
        background: $bc
        .cell
            padding: 0 10px
            background-color: #ffffff
            margin-top: 10px
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
                &.email
                    max-width: 25%


</style>