<template>
    <transition name="horizontal-slide">
        <div class="meschat-detail-wrapper">
            <div class="head">
                <span @click="$router.back()"><i class="icon-left"></i></span>
                <div class="name">
                    <p>{{currentContact ? currentContact.name : contact.name}}</p>
                    <p>{{currentContact? currentContact.company.data.abbreviation : contact.company_name}}</p>
                </div>
                <span><i class="icon-right"></i></span>
            </div>
            <scroll :data="messageList" :scrollToEndFlag="true" class="scroll-wrapper" ref="scroll">
                <div class="main">
                    <div v-show="job.name">
                        <div class="message-header">
                            <div class="title">
                                <div class="line"></div>
                                <p>您正在与Boss{{currentContact ? currentContact.name : contact.name}}直接沟通以下职位</p>
                                <div class="line"></div>
                            </div>
                            <div @click="$router.push({ name: 'job-detail', params: { id: job.id }})" class="job-detail">
                                <div class="pos-info">
                                    <div class="name"><span>{{ job.name }}</span></div>
                                    <div class="salary"><span>{{job.low_salary !== -1 ? job.low_salary + 'k-' + job.high_salary + 'k' : '面议'}}</span></div>
                                </div>
                                <div class="company-info">
                                    <div class="basic-info"><span>{{ company.name }}</span><span>未融资</span></div>
                                    <div class="claim">
                                        <span><i class="icon-position"></i>广州 天河区</span>
                                        <span><i class="icon-seniority"></i>{{ job.seniority }}</span>
                                        <span><i class="icon-education"></i>{{ job.education }}</span>
                                    </div>
                                </div>
                                <div class="user-info">
                                    <img :src="currentContact ? currentContact.avatar : contact.avatar" width="20" height="20" class="avatar">
                                    <span>{{ currentContact ? currentContact.name : contact.name }}</span> <b style="color: ragb(0,0,0,0.5)">|</b>
                                    <span>{{ currentContact ? currentContact.pos_name : contact.pos_name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="message-main">
                            <div v-for="item in messageList" class="message-item-wrapper" ref="messageItemWrapper">
                                <div class="message-time">{{item.created_at}}</div>
                                <div class="message-item" v-if="item.user.data.id !== user.id">
                                    <img :src="item.user.data.avatar" width="35" height="35">
                                    <div class="message">
                                        <p>{{item.message}}</p>
                                    </div>
                                </div>
                                <div class="message-item me" v-else>
                                    <div class="message">
                                        <p>{{item.message}}</p>
                                    </div>
                                    <img :src="user.avatar" width="35" height="35">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </scroll>
            <div class="footer" ref="footer">
                <div class="fast">常用语</div>
                <div class="input-group">
                    <input type="text" v-model="message" @keyup.13="sendMessage" @focus="inputFocus" @blur="inputBlur">
                    <span></span>
                    <span class="jiahao"><i class="icon-jiahao"></i></span>
                </div>
            </div>
        </div>
    </transition>
</template>

<script type="text/ecmascript-6">
  import scroll from 'Base/scroll/scroll'
  import {messageDetail, seedMessage} from 'Api/communicate'
  import {mapState} from 'vuex'

  export default {
    props: {
      currentContact: {
        type: Object
      }
    },
    data() {
      return {
        job: {},
        company: {},
        messageList: [],
        message: ''
      }
    },
    created() {
      messageDetail(this.$route.params.chat_uuid).then(response => {
        this.job = response.data.job.data
        this.company = response.data.job.data.company.data
        this.messageList = response.data.message.data
        console.log(response.data)
      })
      let self = this
      echo.channel('chat-room.1')
        .listen('ChatMessageWasReceived', function (response) {
          response.chatMessage.user = {data: response.user}
          self.messageList = self.messageList.concat(response.chatMessage)
        })
    },
    computed: {
      ...mapState({
        user: state => state.AuthUser,
        contact: state => state.Contact
      })
    },
    methods: {
      inputFocus() {
        this.interval = setInterval(function() {// 设置一个计时器，时间设置与软键盘弹出所需时间相近
          document.body.scrollTop = document.body.scrollHeight // 获取焦点后将浏览器内所有内容高度赋给浏览器滚动部分高度
        }, 100)
      },
      inputBlur() {
        let bfscrolltop = document.body.scrollTop
        clearInterval(this.interval) // 清除计时器
        document.body.scrollTop = bfscrolltop // 将软键盘唤起前的浏览器滚动部分高度重新赋给改变后的高度
      },
      sendMessage() {
        let message = this.message
        this.message = ''
        this.messageList = this.messageList.concat({'message': message, 'created_at': new Date().toLocaleString(), user:{ data:{ id: this.user.id}}})
        seedMessage(this.$route.params.chat_uuid, {'message': message}).then(response => {
        })
      }
    },
    components: {
      scroll
    },
    watch: {
      messageList() {
        this.$nextTick(() => {
          this.$refs.scroll.refresh()
        })
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass">
    @import "../../../sass/variables"
    @import "../../../sass/mixin"

    .meschat-detail-wrapper
        @include allCover()
        background: $bc
        z-index: 100
        .head
            position: absolute
            top: 0
            z-index: 100
            display: flex
            align-items: center
            width: 100%
            text-align: center
            color: $color-text
            background: $color-theme
            box-sizing: border-box
            height: 48px
            line-height: .4rem
            padding: 0 10px
            i
                font-size: .4rem
                padding: 5px
            .name
                flex: 1
        .scroll-wrapper
            position: absolute
            top: 48px
            width: 100%
            bottom: 45px
            transition: all .3s
            .main
                .message-header
                    padding: 5px
                    margin-bottom: 15px
                    .title
                        display: flex
                        align-items: center
                        color: $color-text-d
                        padding: 15px 5px
                        p
                            margin: 0 5px
                        .line
                            flex: 1
                            border: 1px solid $color-text-d
                    .job-detail
                        background: #fff
                        border-radius: .1rem
                        padding: 0 15px
                        .pos-info
                            display: flex
                            justify-content: space-between
                            font-size: .45rem
                            padding: 15px 0
                            .name
                                color: $color-theme
                            .salary
                                color: $red
                        .company-info
                            .basic-info
                                font-size: .4rem
                                color: $color-text-l
                                span
                                    margin-right: 5px
                            .claim
                                font-size: .3rem
                                color: $color-text-l
                                padding: 15px 0
                                @include border-bottom-1px($color-text-d)
                                span
                                    margin-right: 10px
                                    i
                                        padding-right: 5px
                        .user-info
                            display: flex
                            align-items: center
                            padding: 8px 0
                            .avatar
                                border-radius: 50%
                            span
                                margin: 0 8px
                .message-main
                    padding: 0 10px
                    .message-item-wrapper
                        padding-bottom: 15px
                        .message-time
                            color: $color-text-d
                            text-align: center
                            padding: 20px 0
                        .message-item
                            display: flex
                            img
                                border: 1px solid $color-text-d
                                border-radius: 50%
                            .message
                                position: relative
                                border-radius: .1rem
                                padding: 10px 15px 15px 5px
                                margin: 0 20px
                            &.me
                                justify-content: flex-end
                                .message
                                    color: $color-text
                                    background: $color-theme
                                    &:before
                                        position: absolute
                                        right: 0
                                        display: block
                                        width: 0
                                        height: 0
                                        border: 8px solid transparent
                                        pointer-events: none
                                        content: ""
                                        border-left-color: $color-theme
                                        -webkit-transform: translate3d(15px, -100%, 0)
                                        transform: translateX(100%)
                            &:not(.me)
                                .message
                                    background: $color-text
                                    &:before
                                        position: absolute
                                        left: 0
                                        display: block
                                        width: 0
                                        height: 0
                                        border: 8px solid transparent
                                        pointer-events: none
                                        content: ""
                                        border-right-color: $color-text
                                        transform: translateX(-100%);

        .footer
            position: absolute
            display: flex
            align-items: center
            bottom: 0
            width: 100%
            background: #FFF
            box-sizing: border-box
            padding: 5px
            .fast
                color: $color-text
                background: $color-theme
                border-radius: .1rem
                padding: 8px 4px
            .input-group
                display: flex
                align-items: center
                flex: 1
                input
                    flex: 1
                    height: 35px
                    line-height: 35px
                    border-radius: .1rem
                    border: 1px solid #e9efef
                    margin: 0 8px
            .jiahao
                font-size: .5rem
                border: 1px solid $color-text-d
                border-radius: 50%
                padding: 5px
</style>