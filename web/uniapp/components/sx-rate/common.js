export function getClientRect(selector, component) {
  return new Promise((resolve, reject) => {
      let query = component ? uni.createSelectorQuery().in(component) : uni.createSelectorQuery();
      return query.select(selector).boundingClientRect(resolve).exec()
    }
  )
}
