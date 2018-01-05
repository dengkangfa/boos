<template>
    <div>
        <picker title="出生年月" :slots="birthDateSlots" ref="picker" @onValuesChange="onValuesChange" @confirm="confirm"></picker>
        <message :message="message" ref="message"></message>
    </div>
</template>

<script type="text/ecmascript-6">
  import picker from 'Base/picker/picker'
  import message from 'Base/message/message'

  export default {
    props: {
      value: ''
    },
    data() {
      return {
        birthDateSlots: [
          {
            flex: 1,
            values: [],
            defaultIndex: 0
          },
          {
            flex: 1,
            values: [12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1],
            defaultIndex: 0
          }
        ],
        data: [],
        message: ''
      }
    },
    created() {
      this.init()
    },
    methods: {
      init() {
        let birthYearListData = this._getBirthYearListData()
        this.birthDateSlots[0].values = birthYearListData
        let values = this.value.split('-')
        if (values.length < 2) {
          return
        }
        // 索引初始化（values中数据必须转换为int类型）
        this.birthDateSlots[0].defaultIndex = birthYearListData.indexOf(parseInt(values[0]))
        this.birthDateSlots[1].defaultIndex = this.birthDateSlots[1].values.indexOf(parseInt(values[1]))
      },
      _getBirthYearListData() {
        let date = new Date()
        let currentYear = date.getFullYear()
        let yearListData = []
        for (let i = currentYear - 15, j = currentYear - 50; i > j; i--) {
          yearListData.push(i)
        }
        return yearListData
      },
      show() {
        this.$refs.picker.show()
      },
      onValuesChange(picker, values) {
        this.data = values
      },
      confirm() {
        let data = this.data[0] + '-' + (this.data[1] < 10 ? '0' + this.data[1] : this.data[1])
        this.$emit('select', data)
      }
    },
    watch: {
      value() {
        this.init()
      }
    },
    components: {
      picker,
      message
    }
  }
</script>