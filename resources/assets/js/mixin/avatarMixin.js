import avatarDriver from 'Base/avatar/avatar-driver'
import avatarDefault from 'Base/avatar/avatar-default'
import avatarCropper from 'Base/avatar/avatar-cropper'
import {mapState} from 'vuex'

export default {
  data() {
    return {
      cropImage: null,
      cropperShowFlag: false,
      defaultAvatarType: 0
    }
  },
  computed: {
    ...mapState({
      user: state => state.AuthUser,
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
  },
  components: {
    avatarDriver,
    avatarDefault,
    avatarCropper
  }
}