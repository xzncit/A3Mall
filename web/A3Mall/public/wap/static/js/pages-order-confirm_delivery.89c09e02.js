(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-order-confirm_delivery"],{"54fd":function(t,i,o){"use strict";var e=o("db8e"),s=o.n(e);s.a},6324:function(t,i,o){var e=o("24fb");i=e(!1),i.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.money[data-v-253f7cc8]{color:#fc4141}.goods[data-v-253f7cc8]{background-color:#fff;margin-top:%?30?%;padding-bottom:%?20?%}.goods .title[data-v-253f7cc8]{width:100%;margin:0 auto;color:#666;font-size:%?28?%;height:%?80?%;line-height:%?80?%;border-bottom:1px solid #eee}.goods .title uni-text[data-v-253f7cc8]{padding-left:%?20?%}.goods .goods-box[data-v-253f7cc8]{padding:0 %?30?%}.goods .goods-box .goods-item[data-v-253f7cc8]{padding-top:10px}.goods .goods-box .goods-item .goods-img[data-v-253f7cc8]{width:%?150?%;height:%?150?%;display:inline-block;float:left}.goods .goods-box .goods-item .goods-img uni-image[data-v-253f7cc8]{width:100%;height:100%}.goods .goods-box .goods-item .goods-info[data-v-253f7cc8]{display:inline-block;width:72%;font-size:%?28?%;float:right}.goods .goods-box .goods-item .goods-info .t[data-v-253f7cc8]{width:100%;height:%?90?%}.goods .goods-box .goods-item .goods-info .t uni-text[data-v-253f7cc8]:first-child{float:left;display:-webkit-box;overflow:hidden;-webkit-line-clamp:2;-webkit-box-orient:vertical;width:70%}.goods .goods-box .goods-item .goods-info .t uni-text[data-v-253f7cc8]:last-child{width:30%;float:right;text-align:right}.goods .goods-box .goods-item .goods-info .b[data-v-253f7cc8]{width:100%;height:%?80?%;font-size:%?26?%}.goods .goods-box .goods-item .goods-info .b uni-text[data-v-253f7cc8]:first-child{float:left;color:#999}.goods .goods-box .goods-item .goods-info .b uni-text[data-v-253f7cc8]:last-child{float:right;color:#666}.order[data-v-253f7cc8]{background-color:#fff;margin-top:%?30?%;padding-bottom:%?20?%}.order .list[data-v-253f7cc8]{width:100%}.order .list .list-box[data-v-253f7cc8]{width:92%;height:%?80?%;line-height:%?80?%;margin:0 auto;font-size:%?28?%;color:#333;border-bottom:1px solid #ebedf0}.order .list .list-box uni-view[data-v-253f7cc8]{display:inline-block}.order .list .list-box uni-view[data-v-253f7cc8]:first-child{float:left}.order .list .list-box uni-view[data-v-253f7cc8]:last-child{float:right}.btn[data-v-253f7cc8]{width:90%;margin:%?40?% auto;margin-top:%?40?%}.btn uni-view[data-v-253f7cc8]{background-color:#ee0a24;border-radius:%?30?%;font-size:%?28?%;text-align:center;height:%?80?%;line-height:%?80?%;color:#fff}',""]),t.exports=i},"80e3":function(t,i,o){"use strict";o.r(i);var e=o("b778"),s=o.n(e);for(var n in e)"default"!==n&&function(t){o.d(i,t,(function(){return e[t]}))}(n);i["default"]=s.a},"8eee":function(t,i,o){"use strict";o.r(i);var e=o("fe87"),s=o("80e3");for(var n in s)"default"!==n&&function(t){o.d(i,t,(function(){return s[t]}))}(n);o("54fd");var a,d=o("f0c5"),r=Object(d["a"])(s["default"],e["b"],e["c"],!1,null,"253f7cc8",null,!1,e["a"],a);i["default"]=r.exports},b778:function(t,i,o){"use strict";var e=o("4ea4");Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var s=e(o("ed15")),n=e(o("a71d")),a={components:{MallInfo:s.default,navbar:n.default},data:function(){return{isError:!1,isSubmit:!1,orderId:0,order:{item:[],order_amount:"",order_no:"",payable_freight:"",payable_amount:"",promotions:"",real_amount:""}}},onLoad:function(t){var i=this;this.isError=!1,this.orderId=t.id,this.$http.getOrderDeliveryList({id:this.orderId}).then((function(t){t.status?i.order=t.data:i.$utils.msg(t.info),i.isError=!1})).catch((function(t){i.isError=!0}))},methods:{onSubmit:function(){var t=this;if(this.isSubmit)return!1;this.$utils.showLoading(),this.isSubmit=!0,this.$http.getOrderConfirmDelivery({id:this.orderId}).then((function(i){t.$utils.hideLoading(),i.status?(t.$utils.msg(i.info),t.$utils.redirectTo("order/detail",{id:t.orderId})):t.$utils.msg(i.info),t.isSubmit=!1})).catch((function(i){t.$utils.hideLoading(),t.isSubmit=!1,t.$utils.msg("网络出错，请检查网络是否连接")}))}}};i.default=a},db8e:function(t,i,o){var e=o("6324");"string"===typeof e&&(e=[[t.i,e,""]]),e.locals&&(t.exports=e.locals);var s=o("4f06").default;s("06346882",e,!0,{sourceMap:!1,shadowMode:!1})},fe87:function(t,i,o){"use strict";o.d(i,"b",(function(){return s})),o.d(i,"c",(function(){return n})),o.d(i,"a",(function(){return e}));var e={navbar:o("a71d").default},s=function(){var t=this,i=t.$createElement,o=t._self._c||i;return o("v-uni-view",{staticClass:"wrap"},[o("navbar",{attrs:{iSimmersive:!1,"title-color":"#ffffff",background:"#1b43c4",placeholder:!0,title:"确认收货"}}),t.isError?o("info"):t._e(),t.isError?t._e():o("v-uni-view",[o("v-uni-view",{staticClass:"goods"},[o("v-uni-view",{staticClass:"title"},[o("v-uni-text",[t._v("共"+t._s(t.order.item.length)+"件商品")])],1),o("v-uni-view",{staticClass:"goods-box"},t._l(t.order.item,(function(i,e){return o("v-uni-view",{key:e,staticClass:"goods-item clear"},[o("v-uni-view",{staticClass:"goods-img"},[o("v-uni-image",{attrs:{src:i.thumb_image}})],1),o("v-uni-view",{staticClass:"goods-info"},[o("v-uni-view",{staticClass:"t"},[o("v-uni-text",[t._v(t._s(i.title))]),o("v-uni-text",[t._v("￥"+t._s(i.sell_price))])],1),o("v-uni-view",{staticClass:"b"},[o("v-uni-text",[t._v(t._s(i.spec))]),o("v-uni-text",[t._v("× "+t._s(i.nums))])],1)],1)],1)})),1)],1),o("v-uni-view",{staticClass:"order"},[o("v-uni-view",{staticClass:"list clear"},[o("v-uni-view",{staticClass:"list-box clear"},[o("v-uni-view",[t._v("商品金额：")]),o("v-uni-view",[t._v(t._s(t.order.real_amount))])],1),o("v-uni-view",{staticClass:"list-box clear"},[o("v-uni-view",[t._v("运费金额：")]),o("v-uni-view",[t._v(t._s(t.order.payable_freight))])],1),o("v-uni-view",{staticClass:"list-box clear"},[o("v-uni-view",[t._v("订单总额：")]),o("v-uni-view",{staticClass:"money"},[t._v(t._s(t.order.order_amount))])],1)],1)],1),o("v-uni-view",{staticClass:"btn"},[o("v-uni-view",{on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.onSubmit.apply(void 0,arguments)}}},[t._v("确认收货")])],1)],1)],1)},n=[]}}]);