(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-order-list"],{"0268":function(t,e,i){"use strict";i.r(e);var o=i("1dc5"),a=i.n(o);for(var n in o)"default"!==n&&function(t){i.d(e,t,(function(){return o[t]}))}(n);e["default"]=a.a},"0dca":function(t,e,i){"use strict";var o=i("6530"),a=i.n(o);a.a},"1dc5":function(t,e,i){"use strict";var o=i("4ea4");i("99af"),i("c740"),i("a434"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=o(i("3186")),n=o(i("cbc9")),s={mixins:[a.default],components:{navbar:n.default},data:function(){return{screenHeight:0,activeId:1,result:[]}},onLoad:function(t){this.activeId=t.id},onBackPress:function(t){return this.$utils.switchTab("ucenter/index"),!0},methods:{go:function(t){this.activeId=t,this.result=[],this.mescroll.triggerDownScroll()},cancel:function(t){var e=this;this.$utils.showLoading(),this.$http.getOrderListCancel({order_id:t}).then((function(i){if(e.$utils.hideLoading(),i.status){var o=e.result.findIndex((function(e){return e.order_id==t}));e.result.splice(o,1),e.$utils.msg(i.info)}else e.$utils.msg(i.info)})).catch((function(t){e.$utils.hideLoading(),e.$utils.msg("网络出错，请检查网络是否连接")}))},downCallback:function(){var t=this;setTimeout((function(){t.mescroll.resetUpScroll()}),1200)},triggerDownScroll:function(){this.mescroll.triggerDownScroll()},upCallback:function(t){var e=this;this.$http.getOrderList({type:this.activeId,page:t.num}).then((function(i){e.mescroll.endByPage(i.data.list.length,i.data.total),1==i.status?(1==t.num&&(e.result=[]),e.result=e.result.concat(i.data.list)):-1==i.status&&e.mescroll.endErr()})).catch((function(t){e.mescroll.endErr()}))}}};e.default=s},6530:function(t,e,i){var o=i("a8d8");"string"===typeof o&&(o=[[t.i,o,""]]),o.locals&&(t.exports=o.locals);var a=i("4f06").default;a("1fe59a08",o,!0,{sourceMap:!1,shadowMode:!1})},"909e":function(t,e,i){"use strict";i.r(e);var o=i("d2e9"),a=i("0268");for(var n in a)"default"!==n&&function(t){i.d(e,t,(function(){return a[t]}))}(n);i("0dca");var s,l=i("f0c5"),d=Object(l["a"])(a["default"],o["b"],o["c"],!1,null,"d9e0a0be",null,!1,o["a"],s);e["default"]=d.exports},a8d8:function(t,e,i){var o=i("24fb");e=o(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.placeholder-box[data-v-d9e0a0be]{width:100%;height:%?100?%}.addBorder[data-v-d9e0a0be]{border-top:%?2?% solid #eee}.menu[data-v-d9e0a0be]{background-color:#1b43c4;height:%?100?%;line-height:%?100?%;position:fixed;width:100%;top:calc(44px + env(safe-area-inset-top))rpx;left:0;font-size:%?30?%;z-index:999999}.menu .menu-wrap[data-v-d9e0a0be]{display:flex;flex-wrap:nowrap;flex-direction:row}.menu .menu-wrap uni-text[data-v-d9e0a0be]{flex:1;text-align:center;position:relative;color:#fff}.menu .menu-wrap .active[data-v-d9e0a0be]{color:#fff000}.list-wrap[data-v-d9e0a0be]{margin-top:%?20?%}.list-box[data-v-d9e0a0be]{display:flex;flex-direction:column;flex-wrap:wrap}.list-box .list-item-box[data-v-d9e0a0be]{width:95%;margin:%?20?% 2.5%;background-color:#fff;border-radius:%?12?%}.list-box .list-item-box .top[data-v-d9e0a0be]{height:%?90?%;line-height:%?90?%;font-size:%?30?%;border-bottom:%?2?% solid #eee}.list-box .list-item-box .top .order-type[data-v-d9e0a0be]{font-size:%?28?%;margin-right:%?10?%;color:#666}.list-box .list-item-box .top uni-text[data-v-d9e0a0be]:first-child{float:left;padding-left:%?20?%}.list-box .list-item-box .top uni-text[data-v-d9e0a0be]:last-child{font-size:%?28?%;float:right;padding-right:%?20?%}.list-box .list-item-box .goods-box[data-v-d9e0a0be]{padding:0 %?20?%}.list-box .list-item-box .goods-box .goods-item[data-v-d9e0a0be]{padding-top:%?20?%}.list-box .list-item-box .goods-box .goods-item .goods-img[data-v-d9e0a0be]{width:%?154?%;height:%?154?%;display:inline-block;float:left}.list-box .list-item-box .goods-box .goods-item .goods-img uni-image[data-v-d9e0a0be]{width:100%;height:100%}.list-box .list-item-box .goods-box .goods-item .goods-info[data-v-d9e0a0be]{display:inline-block;width:72%;font-size:%?28?%;float:right}.list-box .list-item-box .goods-box .goods-item .goods-info .t[data-v-d9e0a0be]{width:100%;height:%?90?%}.list-box .list-item-box .goods-box .goods-item .goods-info .t uni-text[data-v-d9e0a0be]:first-child{float:left;display:-webkit-box;overflow:hidden;-webkit-line-clamp:2;-webkit-box-orient:vertical;width:70%}.list-box .list-item-box .goods-box .goods-item .goods-info .t uni-text[data-v-d9e0a0be]:last-child{width:30%;float:right;text-align:right}.list-box .list-item-box .goods-box .goods-item .goods-info .b[data-v-d9e0a0be]{width:100%;height:%?80?%;font-size:%?26?%}.list-box .list-item-box .goods-box .goods-item .goods-info .b uni-text[data-v-d9e0a0be]:first-child{float:left;color:#999}.list-box .list-item-box .goods-box .goods-item .goods-info .b uni-text[data-v-d9e0a0be]:last-child{float:right;color:#666}.list-box .list-item-box .order[data-v-d9e0a0be]{width:100%;height:%?90?%;line-height:%?90?%;border-bottom:%?2?% solid #eee}.list-box .list-item-box .order .total[data-v-d9e0a0be]{height:%?90?%;line-height:%?90?%;text-align:right;font-size:%?28?%;padding-right:%?20?%}.list-box .list-item-box .order .total uni-view[data-v-d9e0a0be]{display:inline-block;color:red}.list-box .list-item-box .order .total uni-view uni-text[data-v-d9e0a0be]{font-style:normal;font-size:%?32?%}.list-box .list-item-box .botttom[data-v-d9e0a0be]{width:100%;height:%?110?%;line-height:%?110?%;text-align:right}.list-box .list-item-box .botttom uni-text[data-v-d9e0a0be]{font-size:%?28?%;text-align:center;border-radius:%?30?%;background-color:#fff;padding:%?16?% %?30?%;margin-right:%?20?%}.list-box .list-item-box .botttom uni-text.cancel[data-v-d9e0a0be]{color:#333;border:%?2?% solid #ddd}.list-box .list-item-box .botttom uni-text.pay[data-v-d9e0a0be]{background-color:#e93323;color:#fff}',""]),t.exports=e},d2e9:function(t,e,i){"use strict";i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return n})),i.d(e,"a",(function(){return o}));var o={navbar:i("cbc9").default,mescrollBody:i("38a3").default},a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("navbar",{attrs:{"title-color":"#ffffff",background:"#1b43c4",iSimmersive:!1,placeholder:!0,title:"订单列表"},model:{value:t.screenHeight,callback:function(e){t.screenHeight=e},expression:"screenHeight"}}),i("v-uni-view",{},[i("v-uni-view",{staticClass:"menu"},[i("v-uni-view",{staticClass:"menu-wrap"},[i("v-uni-text",{class:{active:"1"==t.activeId},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.go("1")}}},[t._v("待付款")]),i("v-uni-text",{class:{active:"2"==t.activeId},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.go("2")}}},[t._v("待发货")]),i("v-uni-text",{class:{active:"3"==t.activeId},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.go("3")}}},[t._v("待收货")]),i("v-uni-text",{class:{active:"4"==t.activeId},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.go("4")}}},[t._v("待评价")]),i("v-uni-text",{class:{active:"5"==t.activeId},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.go("5")}}},[t._v("已完成")])],1)],1),i("v-uni-view",{staticClass:"placeholder-box"})],1),i("mescroll-body",{ref:"mescrollRef",attrs:{height:t.screenHeight-50+"px"},on:{init:function(e){arguments[0]=e=t.$handleEvent(e),t.mescrollInit.apply(void 0,arguments)},down:function(e){arguments[0]=e=t.$handleEvent(e),t.downCallback.apply(void 0,arguments)},up:function(e){arguments[0]=e=t.$handleEvent(e),t.upCallback.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"list-wrap"},[i("v-uni-view",{staticClass:"list-box"},t._l(t.result,(function(e,o){return i("v-uni-view",{key:o,staticClass:"list-item-box"},[i("v-uni-view",{staticClass:"top"},[i("v-uni-text",{staticClass:"order-type"},[t._v(t._s(e.type))]),i("v-uni-text",{staticClass:"time"},[t._v(t._s(e.create_time))]),i("v-uni-text",{staticClass:"satus"},[t._v(t._s(e.order_status))])],1),i("v-uni-view",{staticClass:"goods-box",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.$utils.navigateTo("order/detail",{id:e.order_id})}}},t._l(e.item,(function(e,o){return i("v-uni-view",{key:o,staticClass:"goods-item clear"},[i("v-uni-view",{staticClass:"goods-img"},[i("v-uni-image",{attrs:{src:e.thumb_image}})],1),i("v-uni-view",{staticClass:"goods-info"},[i("v-uni-view",{staticClass:"t"},[i("v-uni-text",[t._v(t._s(e.title))]),i("v-uni-text",[t._v("￥"+t._s(e.price))])],1),i("v-uni-view",{staticClass:"b"},[i("v-uni-text",[t._v(t._s(e.spec))]),i("v-uni-text",[t._v("× "+t._s(e.nums))])],1)],1)],1)})),1),i("v-uni-view",{staticClass:"order",class:{addBorder:6==e.active}},[i("v-uni-view",{staticClass:"total"},[t._v("共"+t._s(e.item.length)+"件商品，总金额"),i("v-uni-view",[t._v("￥"),i("v-uni-text",[t._v(t._s(e.order_amount))])],1)],1)],1),6!=e.active?i("v-uni-view",{staticClass:"botttom"},[1==e.active?i("v-uni-text",{staticClass:"cancel",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.cancel(e.order_id)}}},[t._v("取消订单")]):t._e(),1==e.active?i("v-uni-text",{staticClass:"pay",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.$utils.navigateTo("order/detail",{id:e.order_id})}}},[t._v("立即付款")]):t._e(),2==e.active||3==e.active||4==e.active?i("v-uni-text",{staticClass:"cancel",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.$utils.navigateTo("order/refund",{id:e.order_id})}}},[t._v("申请退款")]):t._e(),3==e.active||4==e.active?i("v-uni-text",{staticClass:"cancel",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.$utils.navigateTo("order/express",{id:e.order_id})}}},[t._v("查看物流")]):t._e(),2==e.active||3==e.active||4==e.active?i("v-uni-text",{staticClass:"pay",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.$utils.navigateTo("order/confirm_delivery",{id:e.order_id})}}},[t._v("确认收货")]):t._e(),5==e.active?i("v-uni-text",{staticClass:"pay",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.$utils.navigateTo("order/evaluate",{id:e.order_id})}}},[t._v("待评价")]):t._e()],1):t._e()],1)})),1)],1)],1)],1)},n=[]}}]);