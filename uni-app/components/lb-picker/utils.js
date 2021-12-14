/**
 * 判断是否是对象
 *
 * @export
 * @param {*} val
 * @returns true/false
 */
export function isObject (val) {
  return Object.prototype.toString.call(val) === '[object Object]'
}

/**
 * 根据value获取columns信息
 *
 * @export
 * @param {*} { value, list, mode, props, level }
 * @param {number} [type=2] 查询不到value数据返回数据类型 1空值null 2默认第一个选项
 * @returns
 */
export function getColumns ({ value, list, mode, props, level }, type = 2) {
  let pickerValue = []
  let pickerColumns = []
  let selectValue = []
  let selectItem = []
  let columnsInfo = null
  switch (mode) {
    case 'selector':
      let index = list.findIndex(item => {
        return isObject(item) ? item[props.value] === value : item === value
      })
      if (index === -1 && type === 1) {
        columnsInfo = null
      } else {
        index = index > -1 ? index : 0
        selectItem = list[index]
        selectValue = isObject(selectItem)
          ? selectItem[props.value]
          : selectItem
        pickerColumns = list
        pickerValue = [index]
        columnsInfo = {
          index: pickerValue,
          value: selectValue,
          item: selectItem,
          columns: pickerColumns
        }
      }
      break
    case 'multiSelector':
      const setPickerItems = (data = [], index = 0) => {
        if (!data.length) return
        const defaultValue = value || []
        if (index < level) {
          const value = defaultValue[index] || ''
          let i = data.findIndex(item => item[props.value] === value)
          if (i === -1 && type === 1) return
          i = i > -1 ? i : 0
          pickerValue[index] = i
          pickerColumns[index] = data
          if (data[i]) {
            selectValue[index] = data[i][props.value]
            selectItem[index] = data[i]
            setPickerItems(data[i][props.children] || [], index + 1)
          }
        }
      }
      setPickerItems(list)
      if (!selectValue.length && type === 1) {
        columnsInfo = null
      } else {
        columnsInfo = {
          index: pickerValue,
          value: selectValue,
          item: selectItem,
          columns: pickerColumns
        }
      }
      break
    case 'unlinkedSelector':
      list.forEach((item, i) => {
        let index = item.findIndex(item => {
          return isObject(item)
            ? item[props.value] === value[i]
            : item === value[i]
        })
        if (index === -1 && type === 1) return
        index = index > -1 ? index : 0
        const columnItem = list[i][index]
        const valueItem = isObject(columnItem)
          ? columnItem[props.value]
          : columnItem
        pickerValue[i] = index
        selectValue[i] = valueItem
        selectItem[i] = columnItem
      })
      pickerColumns = list
      if (!selectValue.length && type === 1) {
        columnsInfo = null
      } else {
        columnsInfo = {
          index: pickerValue,
          value: selectValue,
          item: selectItem,
          columns: pickerColumns
        }
      }
      break
  }
  return columnsInfo
}
