<template>
    <div>
        <picker title="薪资要求(月薪，单位:千元)" :slots="salarySlots" @onValuesChange="onValuesChange" ref="picker" @confirm="confirm"></picker>
    </div>
</template>

<script type="text/ecmascript-6">
  import picker from './picker'

  export default {
    data() {
      return {
        data: [],
        salarySlots: [
          {
            flex: 1,
            values: [],
            defaultIndex: 10
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
        this.salarySlots[0].values = this._getLowSalary()
        this.salarySlots[1].values = this._getHighSalary(10)
      },
      _getLowSalary() {
        let lowSalarys = ['面议']
        let interval = 1
        for (let i = 1; i <= 250; i += interval) {
          lowSalarys.push(i)
          if (i >= 50) {
            interval = 10
          }
        }
        return lowSalarys
      },
      _getHighSalary(lowSalary) {
        let highSalary = []
        let interval = 1
        let i = lowSalary
        if (lowSalary > 50) {
          interval = 10
          i += 10
        } else {
          i += 1
        }
        for (; i <= lowSalary * 2; i += interval) {
          highSalary.push(i)
        }
        return highSalary
      },
      show() {
        this.$refs.picker.show()
      },
      onValuesChange(picker, values) {
        this.data = values
        if (values[0] === '面议') {
          picker.setSlotValues(1, [])
          this.data[1] = '面议'
        } else {
          picker.setSlotValues(1, this._getHighSalary(values[0]))
        }
      },
      confirm() {
        this.$emit('selected', this.data)
      }
    },
    components: {
      picker
    }
  }
</script>