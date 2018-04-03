<template>
    <div>
        <div class="_effect message-wrapper" :class="{'_effect--30': decline}">
            <div class="head">
                <switches @switch="switchItem" :currentIndex="currentIndex" :switches="switches"></switches>
            </div>
            <main>
                <div class="message-show">
                    <!-- 消息列表 -->
                    <div class="meschat" v-show="currentIndex === 0">
                        <h4 class="title">联系人<span>查看新开聊(0)</span></h4>
                        <div class="chatlist">
                            <div v-show="loading" class="mint-spinner">
                                <mint-spinner color="#53CAC3" type="triple-bounce"></mint-spinner>
                            </div>
                            <ul v-show="contactList.length">
                                <router-link tag="li" v-for="contact in contactList" @click.native="currentContact = contact, contact.new_message_count = 0" :key="contact.chat_uuid" :to="{name: 'meschatDetail', params: {'chat_uuid': contact.chat_uuid}}" class="meschat-item">
                                    <div class="info-left">
                                        <img :src="contact.avatar" width="45" height="45">
                                    </div>
                                    <div class="info-right">
                                        <p class="user-name"><span class="name">{{ contact.name }}</span><span class="time">昨天</span></p>
                                        <p class="company-info"><span>{{ contact.company.data.abbreviation }}</span> | <span>{{ contact.pos_name }}</span></p>
                                        <p class="last-message"><span :class="{'read': !contact.read_at}" v-if="contact.last_message && !contact.new_message_count && contact.last_message.user_id === user.id">{{ !contact.read_at ? '已读' : '送达' }}</span>{{ contact.last_message ? contact.last_message.message : systemMessages[0].message }}</p>
                                        <span v-show="contact.new_message_count" class="new-message-count">{{ contact.new_message_count }}</span>
                                    </div>
                                </router-link>
                            </ul>
                        </div>
                    </div>
                    <div class="info-list" v-show="currentIndex === 1">
                        <div class="inter-header">
                            <ul class="inter-bar">
                                <li v-for="item in listBar"
                                    :class="{ isHad:item.isHad }"
                                    @click="toggleTab(item)">{{ item.title }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </main>
            <!--<keep-alive>-->
            <!--</keep-alive>-->
        </div>
        <router-view  @routePipe="routePipe" :currentContact="currentContact"></router-view>
    </div>
</template>

<script type="text/ecmascript-6">
  import switches from 'Base/switches/switches'
  import {contactList} from 'Api/communicate'
  import {mapState} from 'vuex'

  export default {
    data() {
      return {
        decline: false,
        switches: [{'name': '聊天'}, {'name': '互动'}],
        // 2、互动切换
        listBar: [
          {
            title: '对我感兴趣',
            isHad: true
          },
          {
            title: '看过我',
            isHad: false
          },
          {
            title: '新职位',
            isHad: false
          }
        ],
        systemMessages: [
          {
            message: 'BOSS直聘温馨提示: 与陌生Boss沟通时，请提高防范意识，谨防招聘欺骗。点击链接，获取详细防骗指南。(<a href="">点击查看防骗指南</a>)'
          },
          {
            message: '了解自己在当前职位求职者中是综合排名，点击查看<a href="">个人竞争力分享</a>'
          }
        ],
        currentContact: null,
        currentIndex: 0,
        loading: false
      }
    },
    mounted() {
      this.loading = true

      let data = this.user.roles.indexOf('recruiter') === -1 ? 'company' : ''
      this.$store.dispatch('contactList', data).then(response => {
        this.loading = false
      })
//      contactList().then(response => {
//        this.loading = false
//        this.contactList = response.data
//      })
    },
    computed: {
      ...mapState({
        contactList: state => state.ContactList.contact_list,
        user: state => state.AuthUser
      })
    },
    methods: {
      routePipe(_decline) {
        this.decline = _decline
      },
      switchItem(index) {
        this.currentIndex = index
      },
      toggleTab(item) {
        if (!item.isHad) {
          this.listBar.filter(value => {
            value.isHad = false
          })
          item.isHad = true
        }
      }
    },
    components: {
      switches
    }
  }
</script>

<style lang="sass" rel="stylesheet/sass" scoped>
    @import "../../../sass/variables"
    @import "../../../sass/mixin"

    .message-wrapper
        .head
            position: relative
            font-size: 0.48rem
            padding: 0.2rem 0
            background: $color-theme
            text-align: center
            color: #FFFFFF
        .meschat
            h4.title
                display: flex
                justify-content: space-between
                align-items: center
                background: #fff
                line-height: 1.8
                font-size: 0.444rem
                border-left: 0.083rem solid #42bfee
                margin-top: 0.278rem
                border-bottom: 0.028rem solid #e9efef
                border-top: 0.028rem solid #e9efef
                padding: 0.167rem 0.417rem
                span
                    padding: 0.139rem 0.333rem
                    background: #c3c3c3
                    color: #FFF
                    border-radius: 0.083rem
                    font-size: 0.278rem
                    line-height: normal
            .chatlist
                ul
                    padding: 0 15px
                    background: #FFFFFF
                    li:not(:last-child)
                        @include border-bottom-1px($color-text-d)
                    .meschat-item
                        display: flex
                        align-items: center
                        padding: 10px 0
                        .info-left
                            margin-right: 15px
                            img
                                border-radius: 50%
                        .info-right
                            position: relative
                            width: 100%
                            line-height: .6rem
                            .user-name
                                display: flex
                                justify-content: space-between
                                .name
                                    font-size: .4rem
                                .time
                                    color: $color-text-d
                            .company-info
                                font-size: .35rem
                                color: $color-text-l
                            .last-message
                                width: 200px
                                color: $color-text-d
                                overflow: hidden
                                text-overflow: ellipsis
                                white-space: nowrap
                                span
                                    font-size: .2rem
                                    color: $color-text
                                    background: #738cde
                                    border-radius: .3rem
                                    padding: 5px
                                    margin-right: 5px
                                    &.read
                                        background: $color-theme
                            .new-message-count
                                position: absolute
                                right: 0
                                bottom: 20%
                                color: #fff
                                padding: 2px
                                background: #e4393c
                                border-radius: 50%
                                width: 15px
                                height: 15px
                                text-align: center
                                line-height: 15px
                .mint-spinner
                    height: 45px
                    line-height: 45px
                    text-align: center
        .info-list
            .inter-header
                height: 1rem
                line-height: 1rem
                border-bottom: 0.013rem solid #e9efef
                .inter-bar
                    display: flex
                    justify-content: space-around
                    color: $color-text-d
                    li
                        &.isHad
                            color: #979797
                            border-bottom: 2px solid $color-theme
                            box-sizing: border-box

</style>