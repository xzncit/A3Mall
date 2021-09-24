<template>
  <view class="lb-selector-picker lb-picker-item"
    :style="{ height: height }">
    <picker-view :value="pickerValue"
      :style="{ height: height }"
      :indicator-style="indicatorStyle"
      @change="handleChange">
      <picker-view-column>
        <view v-for="(item, i) in list"
          :class="[
            'lb-picker-column',
            (item[props.value] || item) === selectValue
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
    value: [String, Number],
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
      selectValue: '',
      selectItem: null
    }
  },
  methods: {
    handleChange (item) {
      const index = item.detail.value[0] || 0
      this.selectItem = this.list[index]
      this.selectValue = isObject(this.selectItem)
        ? this.selectItem[this.props.value]
        : this.selectItem
      this.pickerValue = item.detail.value
      this.$emit('change', {
        value: this.selectValue,
        item: this.selectItem,
        index: index,
        change: 'scroll'
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../style/picker-item.scss";
</style>
