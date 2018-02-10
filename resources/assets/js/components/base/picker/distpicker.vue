<template>
    <div>
        <picker title="工作城市" :slots="districtsSlots" @onValuesChange="onValuesChange" @confirm="confirm" ref="picker"></picker>
    </div>
</template>

<script type="text/ecmascript-6">
  import picker from './picker'
  import DISTRICTS from './districts'

  const DEFAULT_CODE = 100000

  export default {
    data() {
      return {
        showFlag: false,
        values: [],
        districtsSlots: [
          {
            flex: 1,
            values: []
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
      let provinces = []
      for (let i in DISTRICTS[DEFAULT_CODE]) {
        provinces.push(DISTRICTS[DEFAULT_CODE][i])
      }
      this.districtsSlots[0].values = provinces
      this.districtsSlots[1].values = this._getCity(this._getAreas(provinces[0]))
    },
    methods: {
      show() {
        this.$refs.picker.show()
      },
      hide() {
        this.$refs.picker.hide()
      },
      onValuesChange(picker, values) {
        this.values = values
        let provinceKey = this._getAreas(values[0])
        picker.setSlotValues(1, this._getCity(provinceKey))
      },
      confirm() {
        this.$emit('selected', this.values[1])
        this.hide()
      },
      _getAreas(name) {
        for (let x in DISTRICTS) {
          for (let y in DISTRICTS[x]) {
            if (DISTRICTS[x][y] === name) {
              return y
            }
          }
        }
      },
      _getCity(provinceKey) {
        let citys = []
        for (let cityKey in DISTRICTS[provinceKey]) {
          citys.push(DISTRICTS[provinceKey][cityKey])
        }
        return citys
      }
    },
    components: {
      picker
    }
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">

</style>