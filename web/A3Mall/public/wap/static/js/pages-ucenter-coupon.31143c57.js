(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-ucenter-coupon"],{"25b8":function(t,i,e){var n=e("24fb"),a=e("1de5"),o=e("64a5");i=n(!1);var l=a(o);i.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.top-placeholder[data-v-17b7d407]{height:%?90?%;width:100%}.top[data-v-17b7d407]{width:100%;position:fixed;display:flex;flex-wrap:nowrap;flex-direction:row;height:%?90?%;line-height:%?90?%;text-align:center;background-color:#1b43c4;font-size:%?28?%;color:#fff;z-index:999999}.top uni-text[data-v-17b7d407]{width:50%}.top uni-text[data-v-17b7d407]:first-child{width:49%;border-right:%?1?% solid #1b43c4}.top .active[data-v-17b7d407]{color:#fff000}.list-wrap[data-v-17b7d407]{width:100%;margin-top:%?20?%}.list-wrap .list-item[data-v-17b7d407]{width:92%;height:%?200?%;border-radius:%?10?%;background-color:#fff;margin:0 auto %?20?% auto;font-size:%?26?%;position:relative}.list-wrap .list-item .l[data-v-17b7d407]{position:absolute;width:%?220?%;height:%?160?%;top:%?20?%;left:0;border-right:1px dashed #ccc}.list-wrap .list-item .l uni-view[data-v-17b7d407]{color:#1b43c4;display:block;text-align:center;height:%?60?%;line-height:%?40?%}.list-wrap .list-item .l uni-view[data-v-17b7d407]:first-child{font-size:%?32?%;height:%?100?%;line-height:%?120?%;color:#1b43c4}.list-wrap .list-item .l uni-view:first-child uni-text[data-v-17b7d407]{font-size:%?50?%;font-style:normal;font-weight:700}.list-wrap .list-item .m[data-v-17b7d407]{padding:0 %?110?% 0 %?220?%;height:%?160?%;text-align:center}.list-wrap .list-item .m uni-view[data-v-17b7d407]{display:block}.list-wrap .list-item .m uni-view[data-v-17b7d407]:first-child{padding-top:%?50?%;line-height:%?50?%;font-size:%?35?%;color:#333}.list-wrap .list-item .m uni-view[data-v-17b7d407]:last-child{height:%?50?%;line-height:%?50?%;font-size:%?28?%;color:#999}.list-wrap .list-item .r[data-v-17b7d407]{z-index:1;position:absolute;right:0;top:0;width:%?110?%;height:%?200?%;line-height:%?200?%;float:right;text-align:center;background-color:#1b43c4;background-image:url('+l+');background-repeat:repeat-y;background-position:%?-4?% 50%;background-size:%?12?%}.list-wrap .list-item .r[data-v-17b7d407]:before{z-index:11;content:" ";position:absolute;top:%?-16?%;left:%?-16?%;width:%?32?%;height:%?24?%;background-color:#f6f6f6;border-radius:%?100?%}.list-wrap .list-item .r[data-v-17b7d407]:after{z-index:11;content:" ";position:absolute;bottom:%?-16?%;left:%?-16?%;width:%?32?%;height:%?24?%;background-color:#f6f6f6;border-radius:%?100?%}.list-wrap .list-item .r uni-view[data-v-17b7d407]{font-size:%?30?%;color:#fff;display:block;text-align:center}.list-wrap .list-item .active[data-v-17b7d407]{background-color:#6b8df9}.list-wrap .list-item .disable[data-v-17b7d407]{background-color:#dbdadd}',""]),t.exports=i},"42ca":function(t,i,e){var n=e("25b8");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=e("4f06").default;a("202e0152",n,!0,{sourceMap:!1,shadowMode:!1})},"64a5":function(t,i,e){t.exports=e.p+"static/img/coupon-circle.97d33892.png"},"66c9":function(t,i,e){"use strict";e.d(i,"b",(function(){return a})),e.d(i,"c",(function(){return o})),e.d(i,"a",(function(){return n}));var n={navbar:e("674b").default,mescrollBody:e("5e0f").default},a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-uni-view",[e("navbar",{attrs:{"title-color":"#ffffff",background:"#1b43c4",iSimmersive:!1,placeholder:!0,title:"优惠劵"},model:{value:t.screenHeight,callback:function(i){t.screenHeight=i},expression:"screenHeight"}}),e("v-uni-view",{staticClass:"top"},[e("v-uni-text",{class:{active:1==t.isActive},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.changeCoupon(1)}}},[t._v("未使用")]),e("v-uni-text",{class:{active:2==t.isActive},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.changeCoupon(2)}}},[t._v("己使用/己过期")])],1),e("v-uni-view",{staticClass:"top-placeholder"}),e("mescroll-body",{ref:"mescrollRef",attrs:{height:t.screenHeight+"px"},on:{init:function(i){arguments[0]=i=t.$handleEvent(i),t.mescrollInit.apply(void 0,arguments)},down:function(i){arguments[0]=i=t.$handleEvent(i),t.downCallback.apply(void 0,arguments)},up:function(i){arguments[0]=i=t.$handleEvent(i),t.upCallback.apply(void 0,arguments)}}},[e("v-uni-view",{staticClass:"list-wrap"},[e("v-uni-view",{staticClass:"list-box"},t._l(t.result,(function(i,n){return e("v-uni-view",{key:n,staticClass:"list-item"},[e("v-uni-view",{staticClass:"l"},[e("v-uni-view",[t._v("￥"),e("v-uni-text",[t._v(t._s(i.amount))])],1),i.price>0?e("v-uni-view",[t._v("满"+t._s(i.price)+"可用")]):e("v-uni-view",[t._v("无门槛")])],1),e("v-uni-view",{staticClass:"m"},[e("v-uni-view",[t._v(t._s(i.name))]),e("v-uni-view",[t._v("到期："+t._s(i.end_time))])],1),e("v-uni-view",{staticClass:"r",class:{disable:2==t.isActive}},[e("v-uni-view",{on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.go.apply(void 0,arguments)}}},[t._v("使用")])],1)],1)})),1)],1)],1)],1)},o=[]},7090:function(t,i,e){"use strict";e.r(i);var n=e("bec8"),a=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(i,t,(function(){return n[t]}))}(o);i["default"]=a.a},bec8:function(t,i,e){"use strict";var n=e("4ea4");e("99af"),Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var a=n(e("3801")),o=n(e("674b")),l={mixins:[a.default],components:{navbar:o.default},data:function(){return{screenHeight:0,isActive:1,result:[]}},methods:{go:function(){2!=this.isActive&&this.$utils.navigateTo("goods/index")},changeCoupon:function(t){var i=this;this.page=1,this.isActive=t,this.result=[],setTimeout((function(){i.mescroll.resetUpScroll()}),1200)},downCallback:function(){var t=this;setTimeout((function(){t.mescroll.resetUpScroll()}),1200)},triggerDownScroll:function(){this.mescroll.triggerDownScroll()},upCallback:function(t){var i=this;this.$http.getCoupon({type:this.isActive,page:t.num}).then((function(e){i.mescroll.endByPage(e.data.list.length,e.data.total),1==e.status?(1==t.num&&(i.result=[]),i.result=i.result.concat(e.data.list)):-1==e.status&&i.mescroll.endErr()})).catch((function(t){i.mescroll.endErr()}))}}};i.default=l},db48:function(t,i,e){"use strict";var n=e("42ca"),a=e.n(n);a.a},f1b2:function(t,i,e){"use strict";e.r(i);var n=e("66c9"),a=e("7090");for(var o in a)"default"!==o&&function(t){e.d(i,t,(function(){return a[t]}))}(o);e("db48");var l,s=e("f0c5"),r=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"17b7d407",null,!1,n["a"],l);i["default"]=r.exports}}]);