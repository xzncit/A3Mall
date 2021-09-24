import { getColumns } from '../utils'
export const commonMixin = {
  data () {
    return {
      indicatorStyle: `height: 34px`
    }
  },
  created () {
    this.init('init')
  },
  methods: {
    init (changeType) {
      if (this.list && this.list.length) {
        const column = getColumns({
          value: this.value,
          list: this.list,
          mode: this.mode,
          props: this.props,
          level: this.level
        })
        const { columns, value, item, index } = column
        this.selectValue = value
        this.selectItem = item
        this.pickerColumns = columns
        this.pickerValue = index
        this.$emit('change', {
          value: this.selectValue,
          item: this.selectItem,
          index: this.pickerValue,
          change: changeType
        })
      }
    }
  },
  watch: {
    value () {
      if (!this.isConfirmChange) {
        this.init('value')
      }
    },
    list () {
      this.init('list')
    }
  }
}
