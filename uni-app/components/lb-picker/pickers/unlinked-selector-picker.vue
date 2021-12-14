<template>
  <view class="lb-selector-picker lb-picker-item"
    :style="{ height: height }">
    <picker-view :value="pickerValue"
      :indicator-style="indicatorStyle"
      :style="{ height: height }"
      @change="handleChange">
      <picker-view-column v-for="(column, index) in pickerColumns"
        :key="index">
        <view v-for="(item, i) in column || []"
          :class="[
            'lb-picker-column',
            (item[props.value] || item) === selectValue[index]
              ? 'lb-picker-column-active'
              : ''
          ]"
          :key="i">
          <text class="lb-picker-column-label">
            {{ item[props.label] || item }}
          </text>
        </view>
      </picker-view-column>
    </picker-view>
  </view>
</template>

<script>
import { isObject } from '../utils'
import { commonMixin } from '../mixins'
export default {
  props: {
    value: Array,
    list: Array,
    mode: String,
    props: Object,
    visible: Boolean,
    height: String,
    isConfirmChange: Boolean
  },
  mixins: [commonMixin],
  data () {
    return {
      pickerValue: [],
      pickerColumns: [],
      selectValue: [],
      selectItem: []
    }
  },
  methods: {
    handleChange (item) {
      const pickerValue = item.detail.value
      const columnIndex = pickerValue.findIndex((item, i) => item !== this.pickerValue[i])
      if (columnIndex > -1) {
        const valueIndex = pickerValue[columnIndex]
        const columnItem = this.list[columnIndex][valueIndex]
        const valueItem = isObject(columnItem)
          ? columnItem[this.props.value]
          : columnItem
        this.pickerValue = pickerValue
        this.$set(this.selectValue, columnIndex, valueItem)
        this.$set(this.selectItem, columnIndex, columnItem)
        this.$emit('change', {
          value: this.selectValue,
          item: this.selectItem,
          index: this.pickerValue,
          change: 'scroll'
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../style/picker-item.scss";
</style>
