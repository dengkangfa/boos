<template>
    <div>
        <picker title="在校时间" :slots="periodSlots" ref="picker" @input="currentValueChange" @onValuesChange="onValuesChange" @confirm="confirm"></picker>
    </div>
</template>

<script type="text/ecmascript-6">
  import picker from 'Base/picker/picker'
  export default {
    props: {
      value: {
        type: Array,
        default: () => []
      }
    },
    data() {
      return {
        periodSlots: [
          {
            flex: 1,
            values: [],
            defaultIndex: 5
          },
          {
            flex: 1,
            values: [],
            defaultIndex: 7
          }
        ],
        values: [],
        startYear: ''
      }
    },
    created() {
      this.init()
    },
    mounted() {
      if (this.value.length) {
        this.startYear = this.value[0]
        this.$refs.picker.setSlotValues(1, this.getEndData(this.value[0]))
        this.$refs.picker.setValues(this.value)
      }
    },
    methods: {
      show() {
        this.$refs.picker.show()
      },
      init() {
        let date = new Date()
        let startYears = []
        let currentYear = date.getFullYear()
        for (let i = currentYear; i >= 1990; i--) {
          startYears.push(i)
        }
        startYears.push('1990年以前')
        this.periodSlots[0].values = startYears
        this.periodSlots[1].values = this.getEndData(startYears[5])
      },
      currentValueChange(currentValue) {
        this.currentValue = currentValue
      },
      onValuesChange(picker, values) {
        this.values = values
        if (this.startYear !== values[0]) {
          this.startYear = values[0]
          picker.setSlotValues(1, this.getEndData(values[0]))
          picker.setSlotValue(1, values[0] + 1)
        }
      },
      getEndData(currentStartData) {
        let endYears = []
        if (currentStartData === '1990年以前') {
          endYears = ['1997', '1996', '1995', '1994', '1993', '1992', '1991', '1990']
          return endYears
        }
        let endYear = currentStartData + 1
        for (let i = endYear + 7; i >= endYear; i--) {
          endYears.push(i)
        }
        return endYears
      },
      confirm() {
        this.$emit('select', this.values)
      }
    },
    watch: {
      value(newValue) {
        this.startYear = newValue[0]
        this.$refs.picker.setSlotValues(1, this.getEndData(newValue[0]))
        this.$refs.picker.setValues(newValue)
      }
    },
    components: {
      picker
    }
  }
</script>