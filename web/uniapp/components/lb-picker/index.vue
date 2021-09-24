<template>
  <view v-if="visible || inline"
    :class="['lb-picker', inline ? 'lb-picker-inline' : '']">
    <view v-if="showMask && !inline"
      :class="['lb-picker-mask', animation ? 'lb-picker-mask-animation' : '']"
      :style="{
        backgroundColor: maskBgColor,
        zIndex: zIndex - 1
      }"
      @tap.stop="handleMaskTap"
      @touchmove.stop.prevent="moveHandle">
    </view>
    <view :class="[
        'lb-picker-container',
        !inline ? 'lb-picker-container-fixed' : '',
        animation ? 'lb-picker-container-animation' : '',
        containerVisible ? 'lb-picker-container-show' : ''
      ]"
      :style="{
        borderRadius: `${radius} ${radius} 0 0`,
        zIndex: zIndex
      }">
      <view v-if="showHeader"
        class="lb-picker-header">
        <view class="lb-picker-action lb-picker-left">
          <view class="lb-picker-action-item lb-picker-action-cancel"
            @tap.stop="handleCancel">
            <slot v-if="$slots['cancel-text']"
              name="cancel-text">
            </slot>
            <text v-else
              class="lb-picker-action-cancel-text"
              :style="{ color: cancelColor }">
              {{ cancelText }}
            </text>
          </view>
        </view>

        <view class="lb-picker-action lb-picker-center"
          v-if="$slots['action-center']">
          <slot name="action-center"></slot>
        </view>

        <view class="lb-picker-action lb-picker-right">
          <view class="lb-picker-action-item lb-picker-action-confirm"
            @tap.stop="handleConfirm">
            <slot v-if="$slots['confirm-text']"
              name="confirm-text">
            </slot>
            <text v-else
              class="lb-picker-action-confirm-text"
              :style="{ color: confirmColor }">
              {{ confirmText }}
            </text>
          </view>
        </view>
      </view>
      <view class="lb-picker-content"
        :style="{ height: pickerContentHeight }">
        <!-- loading -->
        <view v-if="loading"
          class="lb-picker-loading">
          <slot name="loading">
            <image class="lb-picker-loading-img"
              src="data:image/png;base64,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">
            </image>
          </slot>
        </view>

        <!-- 暂无数据 -->
        <view v-if="isEmpty && !loading"
          class="lb-picker-empty">
          <slot name="empty">
            <text class="lb-picker-empty-text"
              :style="{ color: emptyColor }">
              {{ emptyText }}
            </text>
          </slot>
        </view>

        <!-- 单选 -->
        <selector-picker v-if="mode === 'selector' && !loading && !isEmpty"
          :value="value"
          :list="list"
          :mode="mode"
          :props="pickerProps"
          :height="pickerContentHeight"
          :inline="inline"
          :is-confirm-change="isConfirmChange"
          @change="handleChange">
        </selector-picker>

        <!-- 多列联动 -->
        <multi-selector-picker v-if="mode === 'multiSelector' && !loading && !isEmpty"
          :value="value"
          :list="list"
          :mode="mode"
          :level="level"
          :visible="visible"
          :props="pickerProps"
          :height="pickerContentHeight"
          :inline="inline"
          :is-confirm-change="isConfirmChange"
          @change="handleChange">
        </multi-selector-picker>

        <!-- 非联动选择 -->
        <unlinked-selector-picker v-if="mode === 'unlinkedSelector' && !loading && !isEmpty"
          :value="value"
          :list="list"
          :mode="mode"
          :visible="visible"
          :props="pickerProps"
          :height="pickerContentHeight"
          :inline="inline"
          :is-confirm-change="isConfirmChange"
          @change="handleChange">
        </unlinked-selector-picker>
      </view>
    </view>
  </view>
</template>

<script>
const defaultProps = {
  label: 'label',
  value: 'value',
  children: 'children'
}
import { getColumns } from './utils'
import SelectorPicker from './pickers/selector-picker'
import MultiSelectorPicker from './pickers/multi-selector-picker'
import UnlinkedSelectorPicker from './pickers/unlinked-selector-picker'
export default {
  components: {
    SelectorPicker,
    MultiSelectorPicker,
    UnlinkedSelectorPicker
  },
  props: {
    value: [String, Number, Array],
    list: Array,
    mode: {
      type: String,
      default: 'selector'
    },
    level: {
      type: Number,
      default: 1
    },
    props: {
      type: Object
    },
    cancelText: {
      type: String,
      default: '取消'
    },
    cancelColor: String,
    confirmText: {
      type: String,
      default: '确定'
    },
    confirmColor: String,
    canHide: {
      type: Boolean,
      default: true
    },
    emptyColor: String,
    emptyText: {
      type: String,
      default: '暂无数据'
    },
    radius: String,
    columnNum: {
      type: Number,
      default: 5
    },
    loading: Boolean,
    closeOnClickMask: {
      type: Boolean,
      default: true
    },
    showMask: {
      type: Boolean,
      default: true
    },
    maskColor: {
      type: String,
      default: 'rgba(0, 0, 0, 0.4)'
    },
    dataset: Object,
    inline: Boolean,
    showHeader: {
      type: Boolean,
      default: true
    },
    animation: {
      type: Boolean,
      default: true
    },
    zIndex: {
      type: Number,
      default: 999
    }
  },
  data () {
    return {
      visible: false,
      containerVisible: false,
      maskBgColor: '',
      isConfirmChange: false,
      myValue: this.value,
      picker: {},
      pickerProps: Object.assign({}, defaultProps, this.props),
      pickerContentHeight: 34 * this.columnNum + 'px'
    }
  },
  computed: {
    isEmpty () {
      if (!this.list) return true
      if (this.list && !this.list.length) return true
      return false
    }
  },
  methods: {
    show () {
      if (this.inline) return
      this.visible = true
      setTimeout(() => {
        this.maskBgColor = this.maskColor
        this.containerVisible = true
      }, 20)
    },
    hide () {
      if (this.inline) return
      this.maskBgColor = ''
      this.containerVisible = false
      setTimeout(() => {
        this.visible = false
      }, 200)
    },
    handleCancel () {
      this.$emit('cancel', this.picker)
      if (this.canHide && !this.inline) {
        this.hide()
      }
    },
    handleConfirm () {
      if (this.isEmpty) {
        this.$emit('confirm', null)
        this.hide()
      } else {
        const picker = JSON.parse(JSON.stringify(this.picker))
        this.myValue = picker.value
        this.isConfirmChange = true
        this.$emit('confirm', this.picker)
        if (this.canHide) this.hide()
      }
    },
    handleChange ({ value, item, index, change }) {
      this.picker.value = value
      this.picker.item = item
      this.picker.index = index
      this.picker.change = change
      this.picker.dataset = this.dataset || {}
      this.isConfirmChange = false
      this.$emit('change', this.picker)
    },
    handleMaskTap () {
      if (this.closeOnClickMask) {
        this.hide()
      }
    },
    moveHandle () {},
    getColumnsInfo (value, type = 1) {
      let columnsInfo = getColumns({
        value,
        list: this.list,
        mode: this.mode,
        props: this.pickerProps,
        level: this.level
      }, type)
      if (columnsInfo) {
        if (this.mode === 'selector') {
          columnsInfo.index = columnsInfo.index[0]
        }
      } else {
        columnsInfo = {}
      }
      columnsInfo.dataset = this.dataset || {}
      return columnsInfo
    }
  },
  watch: {
    value (newVal) {
      this.myValue = newVal
    },
    myValue (newVal) {
      this.$emit('input', newVal)
    },
    visible (newVisible) {
      if (newVisible) {
        this.$emit('show')
      } else {
        this.$emit('hide')
      }
    },
    props (newProps) {
      this.pickerProps = Object.assign({}, defaultProps, newProps)
    }
  }
}
</script>

<style lang="scss" scoped>
@import "./style/picker.scss";
</style>
