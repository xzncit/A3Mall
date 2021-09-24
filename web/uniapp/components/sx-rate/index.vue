<!--
sx-rate  uni-app滑选星星组件
版本 1.1.2
日期 2020-6-4
作者 sunxi1997
链接 https://ext.dcloud.net.cn/plugin?id=1027
-->
<template>
  <view
    class="rate-box"
    :class="[{animation},containerClass]"
    @touchmove="ontouchmove"
    @touchend="touchMoving=false"
  >
    <view
      v-for="(val,i) in list"
      :key="val"
      class="rate"
      :style="{fontSize, paddingLeft: i!==0 ? rateMargin : 0, paddingRight: i<list.length-1 ? rateMargin : 0, color: val<=rateValue ? activeColor : defaultColor}"
      :class="[{scale: !disabled && val<=rateValue && animation && touchMoving},`rate-${i}`,rateClass]"
      :data-val="val"
      @click="onItemClick"
    >
      <text class="iconfont iconiconstar" />
    </view>
  </view>
</template>

<script>
  import { getClientRect } from "./common";

  export default {
    name: 'sx-rate',
    props: {
      // 当前值
      value: {
        type: Number,
        default: 3
      },
      // 最大星星数量
      max: {
        type: Number,
        default: 5
      },
      // 禁用
      disabled: {
        type: Boolean,
        default: false
      },
      // 动画效果
      animation: {
        type: Boolean,
        default: true
      },
      // 默认星星颜色
      defaultColor: {
        type: String,
        default: '#ccc'
      },
      // 滑选后星星颜色
      activeColor: {
        type: String,
        default: '#FFB700'
      },
      // 星星大小
      fontSize: {
        type: String,
        default: 'inherit'
      },
      // 星星间距
      margin: {
        type: String,
        default: ''
      },
      // 自定义类名-容器
      containerClass: {
        type: String,
        default: ''
      },
      // 自定义类名-星星
      rateClass: {
        type: String,
        default: ''
      }
    },
    data() {
      return {
        rateValue: 0,
        touchMoving: false,
        startX: [],
        startW: 30
      }
    },
    computed: {
      list() {
        return [...new Array(this.max)].map((_, i) => i + 1)
      },
      rateMargin() {
        let margin = this.margin;
        if (!margin)
          return 0;

        switch (typeof margin) {
          case "number":
            margin += 'px';
          case "string":
            break;
          default:
            return 0;
        }

        let reg = /^(\d+)([^\d]*)/;
        let result = reg.exec(margin);
        if (!result)
          return 0;

        let [_, num, unit] = result;
        return num / 2 + unit;
      }
    },
    watch: {
      value: {
        handler(val) {
          this.rateValue = val;
        },
        immediate: true
      }
    },
    methods: {
      // 计算星星位置
      async initStartX() {
        let {max} = this;
        this.startX = [];

        for (let i = 0; i < max; i++) {

          let selector = `.rate-${ i }`;
          let {left, width} = await getClientRect(selector, this);

          this.startX.push(left);
          this.startW = width;
        }
      },

      /**
       * 手指滑动事件回调
       * https://github.com/sunxi1997/uni-app-sx-rate/pull/1
       * 原本的触摸处理在自定了样式后可能会出现bug, 已解决
       */
      async ontouchmove(e) {
        if (!this.touchMoving) {
          this.touchMoving = true;
          // 开始手指滑动时重新计算星星位置,防止星星位置意外变化
          await this.initStartX();
        }

        let {startX, startW, max} = this;
        let {touches} = e;

        // 触摸焦点停留的位置
        let {pageX} = touches[touches.length - 1];


        // 超出最左边, 0 星
        if (pageX <= startX[0])
          return this.toggle(0);

        // 刚好在第一颗星
        else if (pageX <= startX[0] + startW)
          return this.toggle(1);

        // 超出最右边, 最大星
        else if (pageX >= startX[max - 1])
          return this.toggle(max);

        //计算星星停留的位置
        let startXHash = startX.concat(pageX).sort((a, b) => a - b);
        this.toggle(startXHash.indexOf(pageX));
      },
      // 点击回调
      onItemClick(e) {
        let {val} = e.currentTarget.dataset;
        this.toggle(+val)
      },
      // 修改值
      toggle(val) {
        let {disabled} = this;
        val = +val;
        if (disabled || isNaN(val))
          return;
        if (this.rateValue !== val) {
          this.rateValue = val;
          this.$emit('update:value', val);
          this.$emit('change', val)
        }
      }
    },
  }
</script>

<style scoped>
  .rate-box {
    min-height: 1.4em;
    display: flex;
    align-items: center;
  }

  .rate {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 1.2em;
    transition: all .15s linear;
  }

  .rate.scale {
    transform: scale(1.1);
  }
</style>
