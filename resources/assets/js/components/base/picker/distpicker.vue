<template>
    <div>
        <picker :title="title" :slots="districtsSlots" @onValuesChange="onValuesChange" @confirm="confirm" ref="picker"></picker>
    </div>
</template>

<script type="text/ecmascript-6">
  import picker from './picker'
  import DISTRICTS from './districts'

  const DEFAULT_CODE = 100000

  export default {
    props: {
      province: { type: [String, Number], default: '北京' },
      city: { type: [String, Number], default: '' },
      area: { type: [String, Number], default: '' },
      depth: { type: Number, default: 2 },
      title: ''
    },
    data() {
      return {
        showFlag: false,
        values: [],
        currentProvince: this.determineType(this.province),
        currentCity: this.determineType(this.city),
        currentArea: this.determineType(this.area),
        districtsSlots: this.formatDistricts()
      }
    },
    methods: {
      show() {
        this.$refs.picker.show()
      },
      hide() {
        this.$refs.picker.hide()
      },
      formatDistricts(code = DEFAULT_CODE) {
        let districts = []
        let provinces = this.getDistricts(code)
        districts.push({flex: 1, values: provinces, defaultIndex: provinces.indexOf(this.determineType(this.currentProvince))})
        let citys = this.getDistricts(this.getAreaCode(this.determineType(this.currentProvince)))
        districts.push({flex: 1, values: citys, defaultIndex: citys.indexOf(this.determineType(this.currentCity))})
        if (this.depth === 3) {
          let areas = this.getDistricts(this.getAreaCode(this.determineType(this.currentCity)))
          districts.push({flex: 1, values: areas, defaultIndex: areas.indexOf(this.determineType(this.currentArea))})
        }
        return districts
      },
      onValuesChange(picker, values) {
        values = values.map(item => {
          if (typeof(item) === 'undefined') {
            return ''
          }
          return item
        })
        this.values = values
        picker.setSlotValues(1, this.getDistricts(this.getAreaCode(values[0])))
        if (this.depth === 3) {
          picker.setSlotValues(2, this.getDistricts(this.getAreaCode(values[1])))
        }
      },
      confirm() {
        this.$emit('selected', this.values)
        this.hide()
      },
      getDistricts(x = DEFAULT_CODE) {
        let districts = []
        for (let y in DISTRICTS[x]) {
          districts.push(DISTRICTS[x][y])
        }
        return districts
      },
      getAreaCode(name) {
        for (let x in DISTRICTS) {
          for (let y in DISTRICTS[x]) {
            if (DISTRICTS[x][y] === name) {
              return y
            }
          }
        }
      },
      getCodeValue(code) {
        for (let x in DISTRICTS) {
          for (let y in DISTRICTS[x]) {
            if (code == parseInt(y)) {
              return DISTRICTS[x][y]
            }
          }
        }
      },
      getParentCode(code) {
        for (let x in DISTRICTS) {
          for (let y in DISTRICTS[x]) {
            if (code === y) {
              return x
            }
          }
        }
      },
      determineType(value) {
        if (typeof value === 'number') {
          return this.getCodeValue(value)
        }

        return value
      }
    },
    components: {
      picker
    },
    watch: {
      province() {
        this.districtsSlots = this.formatDistricts()
      },
      currentProvince() {
        this.districtsSlots = this.formatDistricts()
      },
      city(newValue) {
        const municipalities = ['重庆']
        if (!this.province && newValue) {
          if (municipalities.indexOf(newValue) >= 0) {
            this.currentProvince = newValue
            return
          }
          this.currentProvince = this.getCodeValue(this.getParentCode(this.getAreaCode(newValue)))
        }
      }
    }
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">

</style>