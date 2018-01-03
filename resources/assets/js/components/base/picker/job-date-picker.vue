<template>
    <div>
        <picker title="参加工作时间" :slots="jobDateSlots" ref="picker" @onValuesChange="onValuesChange" @confirm="confirm"></picker>
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
        data: [],
        message: '',
        jobMonthListData: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        jobDateSlots: [
          {
            flex: 1,
            values: [],
            defaultIndex: 0
          },
          {
            flex: 1,
            values: [],
            defaultIndex: 0
          }
        ]
      }
    },
    created() {
      this.init()
    },
    methods: {
      init() {
        let jobYearListData = this.getJobYearListData()
        this.jobDateSlots[0].values = jobYearListData
        let values = this.value.split('-')
        if (values.length < 1) {
          return
        } else if (values.length === 1) {
          this.jobDateSlots[0].defaultIndex = jobYearListData.indexOf(values[0])
        } else {
          this.jobDateSlots[1].values = this.jobMonthListData
          this.jobDateSlots[0].defaultIndex = jobYearListData.indexOf(values[0] + '年')
          this.jobDateSlots[1].defaultIndex = this.jobMonthListData.indexOf(parseInt(values[1]) + '月')
        }
      },
      getJobYearListData() {
        let DateItems = ['请选择', '应届生']
        let date = new Date()
        let currentYear = date.getFullYear()
        for (let i = currentYear, j = 1990; i >= j; i--) {
          DateItems.push(i + '年')
        }
        DateItems.push('1990年以前')
        return DateItems
      },
      getJobMonthListData(jobYear) {
        if (this.inArray(['请选择', '应届生', '1990年以前'], jobYear)) {
          return []
        }
        return this.jobMonthListData
      },
      inArray(arr, value) {
        if (arr.indexOf && typeof arr.indexOf === 'function') {
          var index = arr.indexOf(value)
          if (index >= 0) {
            return true
          }
        }
        return false
      },
      onValuesChange(picker, values) {
        this.data = values
        picker.setSlotValues(1, this.getJobMonthListData(values[0]))
      },
      confirm() {
        if (this.data[0] === '请选择') {
          this.message = '请选择参加工作时间'
          this.$refs.message.show()
          return
        }
        let jobDate = ''
        if (this.data[1] === undefined) {
          jobDate = this.data[0]
        } else {
          let month = this.jobMonthListData.indexOf(this.data[1]) + 1
          jobDate = this.data[0].slice(0, -1) + '-' + (month < 10 ? '0' + month : month)
        }

        this.$emit('select', jobDate)
        this.hide()
      },
      show() {
        this.$refs.picker.show()
      },
      hide() {
        this.$refs.picker.hide()
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

