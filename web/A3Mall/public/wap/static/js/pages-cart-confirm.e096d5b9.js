(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-cart-confirm"],{"04c6":function(t,a,o){"use strict";o.r(a);var i=o("5d24"),e=o("8016");for(var n in e)"default"!==n&&function(t){o.d(a,t,(function(){return e[t]}))}(n);o("0f69");var s,d=o("f0c5"),r=Object(d["a"])(e["default"],i["b"],i["c"],!1,null,"c13f37d8",null,!1,i["a"],s);a["default"]=r.exports},"05c1":function(t,a,o){"use strict";var i=o("e6c3"),e=o.n(i);e.a},"0971":function(t,a,o){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i={name:"loading",props:{text:{type:String,default:""},isShowLoading:{type:Boolean,default:!0},layer:{type:Boolean,default:!1},color:{type:String,default:"rgba(255,255,255,0.1)"}},mounted:function(){}};a.default=i},"0f69":function(t,a,o){"use strict";var i=o("b460"),e=o.n(i);e.a},"117e":function(t,a,o){"use strict";var i=o("4ea4");o("e25e"),Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var e,n=i(o("ade3")),s=i(o("ed15")),d=i(o("04c6")),r=i(o("a71d")),c=i(o("9d2c")),l={components:{MallInfo:s.default,loading:d.default,navbar:r.default},data:function(){return{scrollNum:0,shippingType:1,isShipping:0,store:{id:"",name:"",tel:"",address:""},isLoading:!0,loadingColor:"rgba(255,255,255,1)",loadingText:"正在加载订单中",isCouponStatus:!1,isAddressStatus:!1,bonusText:"请选择",address:{id:"",name:"",tel:"",address:""},chosenAddressId:"0",bonusId:"0",addressList:[],orderData:{item:{},real_amount:0,real_freight:0,payable_amount:0,order_amount:0,users_price:0,real_point:0,users_point:0,type:0},remarks:"",payment:"wechat",coupons:[],params:null,orderBtnFlag:!1,providerList:[]}},onLoad:function(t){var a=this,o=t.type,i=t.id,e={id:i,type:o};this.$utils.in_array(o,["buy"])&&(e.sku_id=t.sku_id,e.num=t.num,t.kid&&(e.kid=t.kid)),e.shipping_type=this.shippingType,this.params=e,c.default.getPaymentList().then((function(t){a.providerList=t}))},onShow:function(){var t=this;this.$nextTick((function(){var a=t.$storage.getJson("users");null==a?t.$utils.navigateTo("public/login"):t.onLoadOrder()}))},onPageScroll:function(t){this.scrollNum=t.scrollTop},methods:(e={onSelectedAddress:function(t){this.isAddressStatus=!1,this.chosenAddressId=t.id,this.params.address_id=this.chosenAddressId,this.address=t,delete this.params.store_id,this.onLoadOrder()},onLoadOrder:function(){var t=this;this.$utils.showLoading(),this.$http.getCartConfirm(this.params).then((function(a){t.$utils.hideLoading(),a.status?(t.orderData=a.data,t.isShipping=parseInt(a.data.is_shipping),t.storeList=a.data.store,t.addressList=a.data.address.list,void 0==a.data.address.default||a.data.address.default.length<=0?void 0!=a.data.address.list[0]&&(t.address=a.data.address.list[0],t.chosenAddressId=t.address.id):(t.chosenAddressId=a.data.address.default.id,t.address=a.data.address.default),t.coupons=a.data.bonus,"请选择"==t.bonusText&&(t.bonusText=a.data.bonus.length<=0?"暂无优惠劵":a.data.bonus.length+"张可用"),t.isLoading=!1):(t.isLoading=!1,t.$storage.set("order_msg",a.info),t.$utils.redirectTo("cart/msg"))}))},onOrderSubmit:function(){var t=this;if(this.orderBtnFlag)return!1;if(this.orderData.real_point>this.orderData.users_point)return this.$utils.msg("您的积分不足，不能购买此商品"),!1;if(2==this.shippingType&&""==this.store.id)return this.$utils.msg("请先选择自提门店"),!1;this.orderBtnFlag=!0,this.isLoading=!0,this.loadingColor="rgba(255,255,255,0.3)",this.loadingText="正在提交订单中";var a={};Object.assign(a,{id:this.params.id,type:this.params.type,address_id:this.chosenAddressId,bonus_id:this.bonusId,payment:this.payment,remarks:this.remarks,source:c.default.getPaymentType(),url:document.location.href},this.params),this.$http.createOrder(a).then((function(a){t.isLoading=!1,a.status?c.default.crreateOrder(a.data,!0):t.$utils.msg(a.info),t.orderBtnFlag=!1})).catch((function(a){t.isLoading=!1,t.$utils.msg("网络连接错误，请检查网络是否可用"),t.orderBtnFlag=!1}))},selectPayment:function(t){this.payment=t},onCoupons:function(t){this.isCouponStatus=!1,this.params.bonus_id=t.id,this.bonusText=t.value,this.bonusId=t.id,this.onLoadOrder()}},(0,n.default)(e,"onSelectedAddress",(function(t){this.isAddressStatus=!1,this.chosenAddressId=t.id,this.params.address_id=this.chosenAddressId,this.address=t,delete this.params.store_id,this.onLoadOrder()})),(0,n.default)(e,"onAdd",(function(){this.$storage.set("ORDER_CONFIRM_SELECT",!0),this.$utils.navigateTo("ucenter/address_editor")})),e)};a.default=l},1335:function(t,a,o){"use strict";var i;o.d(a,"b",(function(){return e})),o.d(a,"c",(function(){return n})),o.d(a,"a",(function(){return i}));var e=function(){var t=this,a=t.$createElement,o=t._self._c||a;return o("div",{staticClass:"mask",class:{hide:0==t.value,show:1==t.value},on:{click:function(a){a.stopPropagation(),arguments[0]=a=t.$handleEvent(a),t.onClose.apply(void 0,arguments)}}},[t._t("default")],2)},n=[]},1476:function(t,a,o){"use strict";o.r(a);var i=o("2047"),e=o.n(i);for(var n in i)"default"!==n&&function(t){o.d(a,t,(function(){return i[t]}))}(n);a["default"]=e.a},"1b91":function(t,a,o){"use strict";var i=o("5163"),e=o.n(i);e.a},2047:function(t,a,o){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i={props:{value:{type:Boolean,default:!1},add:{type:Boolean,default:!0},tips:{type:String,default:"您还没有添加地址哦"},array:{type:Array,default:function(){return[]}}},data:function(){return{maxHeight:0,active:0}},mounted:function(){var t=this.$utils.getSystemInfo();this.maxHeight=t.h-this.$utils.px2rpx(200)},methods:{onClose:function(){this.$emit("input",!this.value)},onSelect:function(t){this.active=t.id,this.$emit("address-event",t)},onAddAddress:function(){this.$emit("onAdd",{})}}};a.default=i},"37b7":function(t,a,o){"use strict";o.r(a);var i=o("a5db"),e=o("900d9");for(var n in e)"default"!==n&&function(t){o.d(a,t,(function(){return e[t]}))}(n);o("05c1");var s,d=o("f0c5"),r=Object(d["a"])(e["default"],i["b"],i["c"],!1,null,"166575ef",null,!1,i["a"],s);a["default"]=r.exports},5163:function(t,a,o){var i=o("6bb8");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var e=o("4f06").default;e("3164f2a1",i,!0,{sourceMap:!1,shadowMode:!1})},5761:function(t,a,o){"use strict";o.d(a,"b",(function(){return e})),o.d(a,"c",(function(){return n})),o.d(a,"a",(function(){return i}));var i={popup:o("aa96").default},e=function(){var t=this,a=t.$createElement,o=t._self._c||a;return o("v-uni-view",[o("v-uni-view",{staticClass:"address-action",class:{"address-show":1==t.value},staticStyle:{"background-color":"#f8f8f8"}},[o("v-uni-view",{staticClass:"address-title"},[t._v("请选择地址")]),o("v-uni-view",{staticClass:"address-body",style:{"max-height":t.maxHeight+"px"}},[t.array.length<=0?o("v-uni-view",{staticClass:"address-empty"},[t._v(t._s(t.tips))]):t._e(),t.array.length?o("v-uni-view",{staticClass:"address-list"},t._l(t.array,(function(a,i){return o("v-uni-view",{key:i,staticClass:"address-box",on:{click:function(o){arguments[0]=o=t.$handleEvent(o),t.onSelect(a)}}},[o("v-uni-view",{staticClass:"address-r-box"},[o("v-uni-view",{staticClass:"address-name"},[t._v(t._s(a.name)+" "+t._s(a.tel))]),o("v-uni-view",{staticClass:"address-valid"},[t._v(t._s(a.address))])],1),o("v-uni-view",{staticClass:"address-corner-checkbox"},[o("v-uni-text",{staticClass:"iconfont",class:{active:t.active==a.id}},[t._v("")])],1)],1)})),1):t._e(),o("v-uni-view",{staticStyle:{width:"100%",height:"60px",float:"left"}})],1),o("v-uni-text",{staticClass:"iconfont close",on:{click:function(a){a.stopPropagation(),arguments[0]=a=t.$handleEvent(a),t.onClose.apply(void 0,arguments)}}},[t._v("")]),t.add?o("v-uni-view",{staticClass:"address-button",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.onAddAddress.apply(void 0,arguments)}}},[o("v-uni-text",[t._v("新增地址")])],1):t._e()],1),o("popup",{model:{value:t.value,callback:function(a){t.value=a},expression:"value"}})],1)},n=[]},"5d24":function(t,a,o){"use strict";var i;o.d(a,"b",(function(){return e})),o.d(a,"c",(function(){return n})),o.d(a,"a",(function(){return i}));var e=function(){var t=this,a=t.$createElement,o=t._self._c||a;return o("v-uni-view",[t.isShowLoading?o("v-uni-view",{staticClass:"iconfont loading"},[t._v("")]):t._e(),""!=t.text?o("v-uni-view",{staticClass:"loading-text"},[o("v-uni-view",[t._v(t._s(t.text)),o("v-uni-text",{staticClass:"dotting"})],1)],1):t._e(),t.layer?o("v-uni-view",{staticClass:"layer-box",style:"background-color:"+t.color}):t._e()],1)},n=[]},"63db":function(t,a,o){"use strict";o.r(a);var i=o("117e"),e=o.n(i);for(var n in i)"default"!==n&&function(t){o.d(a,t,(function(){return i[t]}))}(n);a["default"]=e.a},"6bb8":function(t,a,o){var i=o("24fb");a=i(!1),a.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.mask[data-v-6b9d0864]{position:fixed;top:0;left:0;right:0;bottom:0;z-index:8999;background-color:#000;opacity:.5}.hide[data-v-6b9d0864]{display:none}.show[data-v-6b9d0864]{display:block}',""]),t.exports=a},"743c":function(t,a,o){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i={props:{value:{type:Boolean,default:!1},coupons:{type:Array,default:function(){return[]}}},data:function(){return{maxHeight:0,active:0}},mounted:function(){var t=this.$utils.getSystemInfo();this.maxHeight=t.h-this.$utils.px2rpx(200)},methods:{onClose:function(){this.$emit("input",!this.value)},onCoupon:function(t){this.active=t.id,this.$emit("coupon-event",{id:t.id,value:"-￥"+t.valueDesc+t.unitDesc})},onCancelBonus:function(){this.active=0,this.$emit("coupon-event",{id:0,value:this.coupons.length<=0?"暂无优惠劵":this.coupons.length+"张可用"})}}};a.default=i},8016:function(t,a,o){"use strict";o.r(a);var i=o("0971"),e=o.n(i);for(var n in i)"default"!==n&&function(t){o.d(a,t,(function(){return i[t]}))}(n);a["default"]=e.a},8692:function(t,a,o){var i=o("24fb");a=i(!1),a.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.distribution[data-v-ac265850]{width:100%;height:%?90?%;line-height:%?90?%;background-color:#fff}.distribution uni-text[data-v-ac265850]{display:inline-block;font-size:%?30?%;width:50%;height:%?90?%;line-height:%?90?%;text-align:center}.distribution uni-text.active[data-v-ac265850]{color:#b91922}.distribution-placeholder[data-v-ac265850]{width:100%;height:%?12?%}.money[data-v-ac265850]{color:#fc4141}.van-address-item__edit[data-v-ac265850]{display:none}.top[data-v-ac265850]{background-color:#fff;position:relative}.top[data-v-ac265850]:before{position:absolute;right:0;bottom:0;left:0;height:%?4?%;background:-webkit-repeating-linear-gradient(135deg,#ff6c6c,#ff6c6c 20%,transparent 0,transparent 25%,#1989fa 0,#1989fa 45%,transparent 0,transparent 50%);background:repeating-linear-gradient(-45deg,#ff6c6c,#ff6c6c 20%,transparent 0,transparent 25%,#1989fa 0,#1989fa 45%,transparent 0,transparent 50%);background-size:%?160?%;content:""}.top .top-map[data-v-ac265850]{width:%?60?%;height:%?60?%;position:absolute;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%);left:%?20?%}.top .top-map uni-text[data-v-ac265850]{font-size:%?32?%}.top .arrow-right[data-v-ac265850]{width:%?60?%;height:%?60?%;position:absolute;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%);right:0}.top .arrow-right uni-text[data-v-ac265850]{-webkit-transform:rotate(180deg);transform:rotate(180deg);display:inline-block}.top .address[data-v-ac265850]{font-size:%?28?%;width:85%;margin:0 auto;padding:%?20?% 0;padding-left:%?40?%;position:relative}.top .address .info[data-v-ac265850]{height:%?60?%;line-height:%?60?%}.top .address .info span[data-v-ac265850]:first-child{padding-right:%?20?%}.top .address .address-info[data-v-ac265850]{height:auto!important;height:%?60?%;min-height:%?40?%;line-height:%?40?%}.goods[data-v-ac265850]{background-color:#fff;margin-top:%?30?%;padding-bottom:%?20?%}.goods .title[data-v-ac265850]{width:100%;margin:0 auto;color:#666;font-size:%?28?%;height:%?80?%;line-height:%?80?%;border-bottom:%?2?% solid #eee}.goods .title uni-text[data-v-ac265850]{padding-left:%?20?%}.goods .goods-box[data-v-ac265850]{padding:0 %?32?%}.goods .goods-box .goods-item[data-v-ac265850]{padding-top:%?20?%}.goods .goods-box .goods-item .goods-img[data-v-ac265850]{width:%?154?%;height:%?154?%;display:inline-block;float:left}.goods .goods-box .goods-item .goods-img uni-image[data-v-ac265850]{width:100%;height:100%}.goods .goods-box .goods-item .goods-info[data-v-ac265850]{display:inline-block;width:72%;font-size:%?28?%;float:right}.goods .goods-box .goods-item .goods-info uni-text[data-v-ac265850]{font-style:normal}.goods .goods-box .goods-item .goods-info .t[data-v-ac265850]{width:100%;height:45px}.goods .goods-box .goods-item .goods-info .t uni-text[data-v-ac265850]:first-child{float:left;display:-webkit-box;overflow:hidden;-webkit-line-clamp:2;-webkit-box-orient:vertical;width:70%}.goods .goods-box .goods-item .goods-info .t uni-text[data-v-ac265850]:last-child{width:30%;float:right;text-align:right}.goods .goods-box .goods-item .goods-info .b[data-v-ac265850]{width:100%;height:40px;font-size:13px}.goods .goods-box .goods-item .goods-info .b uni-view[data-v-ac265850]{float:left;color:#999}.goods .goods-box .goods-item .goods-info .b .goods-nums[data-v-ac265850]{float:right;color:#666}.order[data-v-ac265850]{background-color:#fff;margin-top:%?30?%;padding-bottom:%?20?%}.order .title[data-v-ac265850]{width:100%;margin:0 auto;color:#666;font-size:%?30?%;height:%?80?%;line-height:%?80?%;border-bottom:1px solid #eee}.order .title uni-text[data-v-ac265850]{padding-left:%?30?%}.order .list[data-v-ac265850]{width:100%}.order .list .list-box[data-v-ac265850]{width:92%;height:auto!important;height:%?80?%;min-height:%?80?%;line-height:%?80?%;margin:0 auto;font-size:%?26?%;color:#333;border-bottom:%?2?% solid #ebedf0}.order .list .list-box uni-view[data-v-ac265850]{display:inline-block}.order .list .list-box uni-view[data-v-ac265850]:first-child{float:left}.order .list .list-box uni-view[data-v-ac265850]:last-child{float:right}.order .list .list-box uni-textarea[data-v-ac265850]{height:%?150?%}.payment-box .payment-item[data-v-ac265850]{padding:%?20?% %?32?%;border-bottom:%?2?% solid #eee}.payment-box .payment-item uni-view[data-v-ac265850]{display:inline}.payment-box .payment-item uni-view[data-v-ac265850]:first-child{font-size:%?28?%}.payment-box .payment-item uni-view:first-child uni-text[data-v-ac265850]{width:%?40?%;height:%?40?%;line-height:%?40?%;text-align:center;border-radius:50%;padding:%?4?%}.payment-box .payment-item uni-view[data-v-ac265850]:nth-child(2){font-size:%?28?%;padding-left:%?20?%}.payment-box .payment-item uni-view:nth-child(2) i[data-v-ac265850]{font-size:%?24?%;font-style:normal;color:#999;padding-left:%?20?%}.payment-box .payment-item uni-view[data-v-ac265850]:nth-child(3){float:right;display:none;color:#999}.payment-box .payment-item uni-view.active[data-v-ac265850]{display:block}.payment-box .payment-item uni-view.activeColor[data-v-ac265850]{color:red}.payment-box #wechat[data-v-ac265850]{position:relative;top:%?2?%;width:%?40?%;height:%?40?%;display:inline-block;color:#fff;background-color:#41b035}.payment-box #alipay[data-v-ac265850]{position:relative;top:%?2?%;width:%?40?%;height:%?40?%;display:inline-block;color:#fff;background-color:#1296db}.payment-box #appleiap[data-v-ac265850]{position:relative;top:%?2?%;width:%?40?%;height:%?40?%;display:inline-block;color:#333;background-color:#fff;border:1px solid #eee}.payment-box #balance[data-v-ac265850]{position:relative;top:%?2?%;width:%?40?%;height:%?40?%;background-repeat:no-repeat;background-size:%?40?% %?40?%;display:inline-block;color:#fff;background-color:#fe960f}.payment-box .check[data-v-ac265850]{position:relative;top:%?12?%;width:%?40?%;height:%?40?%;display:inline-block}.operation-placeholder[data-v-ac265850]{width:100%;height:%?140?%;line-height:%?140?%}.operation[data-v-ac265850]{width:100%;height:%?110?%;line-height:%?110?%;text-align:right;background-color:#fff;position:fixed;left:0;bottom:0;border-top:%?2?% solid #eee}.operation .amount[data-v-ac265850]{float:left;padding-top:0;font-size:%?28?%;text-align:center;background-color:#fff;padding:%?6?% %?30?%;display:inline;margin-right:%?20?%}.operation .amount uni-text[data-v-ac265850]{font-style:normal;font-size:%?32?%;color:#555}.operation .amount uni-text[data-v-ac265850]:last-child{color:#db1111;font-size:%?34?%;position:relative;top:%?2?%}.operation .pay[data-v-ac265850]{font-size:%?28?%;text-align:center;border-radius:%?30?%;background-color:#fff;padding:%?16?% %?30?%;display:inline;background-color:#e93323;margin-right:%?20?%;color:#fff}',""]),t.exports=a},"86ba":function(t,a,o){"use strict";o.d(a,"b",(function(){return e})),o.d(a,"c",(function(){return n})),o.d(a,"a",(function(){return i}));var i={navbar:o("a71d").default,couponList:o("37b7").default,addressList:o("a42a").default},e=function(){var t=this,a=t.$createElement,o=t._self._c||a;return o("v-uni-view",[o("navbar",{attrs:{scroll:t.scrollNum,"title-color":"#ffffff",background:"#1b43c4",iSimmersive:!1,placeholder:!0,title:"订单详情"}}),o("v-uni-view",{staticClass:"top"},[o("v-uni-view",{staticClass:"top-map"},[o("v-uni-text",{staticClass:"iconfont"},[t._v("")])],1),o("v-uni-view",{staticClass:"address",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.isAddressStatus=!0}}},[t.address.tel?o("v-uni-view",{staticClass:"info"},[t.address.name?o("v-uni-text",[t._v("收件人："+t._s(t.address.name))]):t._e(),t.address.tel?o("v-uni-text",[t._v("手机号："+t._s(t.address.tel))]):t._e()],1):t._e(),t.address.tel?t._e():o("v-uni-view",{staticClass:"info"},[o("v-uni-text",{staticStyle:{position:"relative",top:"-10rpx"}},[t._v("请选择地址")])],1),t.address.address?o("v-uni-view",{staticClass:"address-info"},[t._v(t._s(t.address.address))]):t._e()],1),o("v-uni-view",{staticClass:"arrow-right"},[o("v-uni-text",{staticClass:"iconfont"},[t._v("")])],1)],1),o("v-uni-view",{staticClass:"goods"},[o("v-uni-view",{staticClass:"title"},[o("v-uni-text",[t._v("共"+t._s(t.orderData.item.length)+"件商品")])],1),o("v-uni-view",{staticClass:"goods-box"},t._l(t.orderData.item,(function(a,i){return o("v-uni-view",{key:i,staticClass:"goods-item clear"},[o("v-uni-view",{staticClass:"goods-img"},[o("v-uni-image",{attrs:{src:a.thumb_image}})],1),o("v-uni-view",{staticClass:"goods-info"},[o("v-uni-view",{staticClass:"t"},[o("v-uni-text",[t._v(t._s(a.title))]),o("v-uni-text",[t._v("￥"+t._s(a.sell_price))])],1),o("v-uni-view",{staticClass:"b"},[a.goods_array?o("v-uni-view",t._l(a.goods_array,(function(a,i){return o("v-uni-text",{key:i},[t._v(t._s(a.name)+"："+t._s(a.value))])})),1):t._e(),o("v-uni-text",{staticClass:"goods-nums"},[t._v("× "+t._s(a.goods_nums))])],1)],1)],1)})),1)],1),o("v-uni-view",{staticClass:"order"},[o("v-uni-view",{staticClass:"title"},[o("v-uni-text",[t._v("订单信息")])],1),o("v-uni-view",{staticClass:"list clear"},[o("v-uni-view",{staticClass:"list-box clear",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.isCouponStatus=!t.isCouponStatus}}},[o("v-uni-view",[t._v("优惠劵：")]),o("v-uni-view",[t._v(t._s(t.bonusText))])],1),o("v-uni-view",{staticClass:"list-box clear"},[o("v-uni-view",[t._v("商品金额：")]),o("v-uni-view",[t._v("￥"+t._s(t.orderData.real_amount))])],1),o("v-uni-view",{staticClass:"list-box clear"},[o("v-uni-view",[t._v("运费金额：")]),o("v-uni-view",[t._v("￥"+t._s(t.orderData.real_freight))])],1),t.orderData.real_point>0?o("v-uni-view",{staticClass:"list-box clear"},[o("v-uni-view",[t._v("需要积分：")]),o("v-uni-view",{staticClass:"money"},[t._v(t._s(t.orderData.real_point)+"积分")])],1):t._e(),o("v-uni-view",{staticClass:"list-box clear"},[o("v-uni-view",[t._v("订单总额：")]),o("v-uni-view",{staticClass:"money"},[t._v("￥"+t._s(t.orderData.payable_amount))])],1)],1)],1),o("v-uni-view",{staticClass:"order"},[o("v-uni-view",{staticClass:"title"},[o("v-uni-text",[t._v("备注内容")])],1),o("v-uni-view",{staticClass:"list clear"},[o("v-uni-view",{staticStyle:{padding:"20rpx 25rpx"}},[o("v-uni-textarea",{staticStyle:{width:"100%",height:"100rpx"},attrs:{value:t.remarks,placeholder:"请输入留言"}})],1)],1)],1),o("v-uni-view",{staticClass:"order"},[o("v-uni-view",{staticClass:"title"},[o("v-uni-text",[t._v("支付方式")])],1),o("v-uni-view",{staticClass:"payment-box"},t._l(t.providerList,(function(a,i){return o("v-uni-view",{key:i,staticClass:"payment-item",on:{click:function(o){arguments[0]=o=t.$handleEvent(o),t.selectPayment(a.id)}}},[o("v-uni-view",[o("v-uni-text",{staticClass:"iconfont pay",class:a.class,attrs:{id:a.id}})],1),o("v-uni-view",{class:{activeColor:t.payment==a.id}},[t._v(t._s(a.name)),"balance"==a.id?o("v-uni-text",{staticStyle:{"padding-left":"15rpx","padding-top":"2rpx","font-size":"24rpx"}},[t._v("可用余额: ￥"+t._s(t.orderData.users_price)+"元")]):t._e()],1),o("v-uni-view",{class:{active:t.payment==a.id}},[o("v-uni-text",{staticClass:"iconfont"},[t._v("")])],1)],1)})),1)],1),o("coupon-list",{attrs:{coupons:t.coupons},on:{"coupon-event":function(a){arguments[0]=a=t.$handleEvent(a),t.onCoupons.apply(void 0,arguments)}},model:{value:t.isCouponStatus,callback:function(a){t.isCouponStatus=a},expression:"isCouponStatus"}}),o("address-list",{attrs:{array:t.addressList},on:{onAdd:function(a){arguments[0]=a=t.$handleEvent(a),t.onAdd.apply(void 0,arguments)},"address-event":function(a){arguments[0]=a=t.$handleEvent(a),t.onSelectedAddress.apply(void 0,arguments)}},model:{value:t.isAddressStatus,callback:function(a){t.isAddressStatus=a},expression:"isAddressStatus"}}),o("v-uni-view",{staticClass:"operation-placeholder"}),o("v-uni-view",{staticClass:"operation"},[o("v-uni-view",{staticClass:"amount"},[o("v-uni-text",[t._v("合计：")]),t.orderData.order_amount?o("v-uni-text",[t._v("￥"+t._s(t.orderData.order_amount))]):o("v-uni-text",[t._v("￥"+t._s(t.orderData.payable_amount))])],1),o("v-uni-view",{staticClass:"pay",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.onOrderSubmit.apply(void 0,arguments)}}},[t._v("提交订单")])],1),t.isLoading?o("loading",{attrs:{color:t.loadingColor,text:t.loadingText,layer:!0}}):t._e()],1)},n=[]},"88d9":function(t,a,o){var i=o("f76b");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var e=o("4f06").default;e("42820aee",i,!0,{sourceMap:!1,shadowMode:!1})},9008:function(t,a,o){"use strict";var i=o("88d9"),e=o.n(i);e.a},"900d9":function(t,a,o){"use strict";o.r(a);var i=o("743c"),e=o.n(i);for(var n in i)"default"!==n&&function(t){o.d(a,t,(function(){return i[t]}))}(n);a["default"]=e.a},"9acb":function(t,a,o){var i=o("24fb");a=i(!1),a.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */:root .dotting[data-v-c13f37d8]{margin-right:8px}.loading[data-v-c13f37d8]{font-size:%?50?%;position:fixed;left:47%;top:40%;z-index:100002;background-size:100%;-webkit-transform:translateX(-47%);transform:translateX(-47%);-webkit-transform:translateY(-40%);transform:translateY(-40%);-webkit-animation:aaa-spin-data-v-c13f37d8 2s infinite linear;animation:aaa-spin-data-v-c13f37d8 2s infinite linear;display:inline-block}.loading-text[data-v-c13f37d8]{width:100%;font-size:%?29?%;text-align:center;position:fixed;top:47%;color:#333;z-index:100002;background-size:100%;-webkit-transform:translateY(-40%);transform:translateY(-40%)}.loading-text uni-view[data-v-c13f37d8]{width:80%;margin:0 auto}.loading-text uni-view .dotting[data-v-c13f37d8]{display:inline-block;min-width:2px;min-height:2px;-webkit-animation:dot-data-v-c13f37d8 4s infinite step-start both;animation:dot-data-v-c13f37d8 4s infinite step-start both;font-size:%?29?%}.layer-box[data-v-c13f37d8]{width:100%;height:100%;position:fixed;top:0;left:0;background-color:hsla(0,0%,100%,.1);z-index:100001}@-webkit-keyframes aaa-spin-data-v-c13f37d8{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(359deg);transform:rotate(359deg)}}@keyframes aaa-spin-data-v-c13f37d8{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(359deg);transform:rotate(359deg)}}@-webkit-keyframes dot-data-v-c13f37d8{25%{box-shadow:none}50%{box-shadow:2px 0 #666}75%{box-shadow:2px 0 #666,6px 0 #666}}@keyframes dot-data-v-c13f37d8{25%{box-shadow:none}50%{box-shadow:2px 0 #666}75%{box-shadow:2px 0 #666,6px 0 #666}}',""]),t.exports=a},"9bf6":function(t,a,o){var i=o("8692");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var e=o("4f06").default;e("0d478ec0",i,!0,{sourceMap:!1,shadowMode:!1})},a42a:function(t,a,o){"use strict";o.r(a);var i=o("5761"),e=o("1476");for(var n in e)"default"!==n&&function(t){o.d(a,t,(function(){return e[t]}))}(n);o("9008");var s,d=o("f0c5"),r=Object(d["a"])(e["default"],i["b"],i["c"],!1,null,"a11af922",null,!1,i["a"],s);a["default"]=r.exports},a5db:function(t,a,o){"use strict";o.d(a,"b",(function(){return e})),o.d(a,"c",(function(){return n})),o.d(a,"a",(function(){return i}));var i={popup:o("aa96").default},e=function(){var t=this,a=t.$createElement,o=t._self._c||a;return o("v-uni-view",[o("v-uni-view",{staticClass:"coupon-action",class:{"coupon-show":1==t.value},staticStyle:{"background-color":"#f8f8f8"}},[o("v-uni-view",{staticClass:"coupon-title"},[t._v("选择优惠劵")]),o("v-uni-view",{staticClass:"coupon-body",style:{"max-height":t.maxHeight+"px"}},[t.coupons.length<=0?o("v-uni-view",{staticClass:"coupon-empty"},[t._v("暂无优惠劵")]):t._e(),t.coupons.length?o("v-uni-view",{staticClass:"coupon-list"},t._l(t.coupons,(function(a,i){return o("v-uni-view",{key:i,staticClass:"coupon-box",on:{click:function(o){arguments[0]=o=t.$handleEvent(o),t.onCoupon(a)}}},[o("v-uni-view",{staticClass:"coupon-l-box"},[o("v-uni-view",{staticClass:"coupon-amount"},[t._v(t._s(a.price)),o("v-uni-text",[t._v("元")])],1),o("v-uni-view",{staticClass:"coupon-condition"},[t._v(t._s(a.condition))])],1),o("v-uni-view",{staticClass:"coupon-r-box"},[o("v-uni-view",{staticClass:"coupon-name"},[t._v(t._s(a.name))]),o("v-uni-view",{staticClass:"coupon-valid"},[t._v(t._s(a.startAt)+" - "+t._s(a.endAt))])],1),o("v-uni-view",{staticClass:"coupon-corner-checkbox"},[o("v-uni-text",{staticClass:"iconfont",class:{active:t.active==a.id}},[t._v("")])],1)],1)})),1):t._e(),o("v-uni-view",{staticStyle:{width:"100%",height:"60px",float:"left"}})],1),o("v-uni-text",{staticClass:"iconfont close",on:{click:function(a){a.stopPropagation(),arguments[0]=a=t.$handleEvent(a),t.onClose.apply(void 0,arguments)}}},[t._v("")]),o("v-uni-view",{staticClass:"coupon-button",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.onCancelBonus.apply(void 0,arguments)}}},[o("v-uni-text",[t._v("不使用优惠劵")])],1)],1),o("popup",{model:{value:t.value,callback:function(a){t.value=a},expression:"value"}})],1)},n=[]},aa96:function(t,a,o){"use strict";o.r(a);var i=o("1335"),e=o("b7ba");for(var n in e)"default"!==n&&function(t){o.d(a,t,(function(){return e[t]}))}(n);o("1b91");var s,d=o("f0c5"),r=Object(d["a"])(e["default"],i["b"],i["c"],!1,null,"6b9d0864",null,!1,i["a"],s);a["default"]=r.exports},b460:function(t,a,o){var i=o("9acb");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var e=o("4f06").default;e("ed3f911e",i,!0,{sourceMap:!1,shadowMode:!1})},b7ba:function(t,a,o){"use strict";o.r(a);var i=o("cb11"),e=o.n(i);for(var n in i)"default"!==n&&function(t){o.d(a,t,(function(){return i[t]}))}(n);a["default"]=e.a},cb11:function(t,a,o){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i={props:{value:{type:Boolean,default:!1}},methods:{onClose:function(){}}};a.default=i},cbd4:function(t,a,o){"use strict";o.r(a);var i=o("86ba"),e=o("63db");for(var n in e)"default"!==n&&function(t){o.d(a,t,(function(){return e[t]}))}(n);o("efc6");var s,d=o("f0c5"),r=Object(d["a"])(e["default"],i["b"],i["c"],!1,null,"ac265850",null,!1,i["a"],s);a["default"]=r.exports},e6c3:function(t,a,o){var i=o("eb10");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var e=o("4f06").default;e("50af4ca0",i,!0,{sourceMap:!1,shadowMode:!1})},eb10:function(t,a,o){var i=o("24fb");a=i(!1),a.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.coupon-action[data-v-166575ef]{position:fixed;left:0;bottom:0;background-color:#fff;width:100%;border-radius:%?20?% %?20?% 0 0;display:flex;flex-direction:column;align-items:stretch;min-height:50%;max-height:80%;font-size:%?28?%;z-index:9999;overflow:hidden;transition:all .3s cubic-bezier(.65,.7,.7,.9);-webkit-transform:translate3d(0,100%,0);transform:translate3d(0,100%,0)}.coupon-action .coupon-title[data-v-166575ef]{font-size:%?32?%;text-align:center;width:100%;height:%?100?%;background-color:#fff;line-height:%?100?%}.coupon-action .coupon-button[data-v-166575ef]{width:100%;height:%?120?%;line-height:%?120?%;position:absolute;left:0;bottom:0;background-color:#fff}.coupon-action .coupon-button uni-text[data-v-166575ef]{text-align:center;background-color:#c21313;width:90%;height:%?100?%;line-height:%?100?%;margin:5px auto;display:block;font-size:%?30?%;color:#fff;border-radius:%?40?%}.coupon-action .coupon-body[data-v-166575ef]{flex:1 1 auto;min-height:%?88?%;overflow-y:scroll;-webkit-overflow-scrolling:touch}.coupon-action .coupon-body .coupon-empty[data-v-166575ef]{width:100%;text-align:center;font-size:%?36?%;height:%?100?%;line-height:%?100?%;position:absolute;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.coupon-action .coupon-body .coupon-list[data-v-166575ef]{width:95%;margin-left:2.5%;float:left;margin-top:10px}.coupon-action .coupon-body .coupon-list .coupon-box[data-v-166575ef]{float:left;margin-bottom:%?20?%;width:100%;height:%?170?%;background-color:#fff;border-radius:%?20?%;position:relative}.coupon-action .coupon-body .coupon-list .coupon-box .coupon-l-box[data-v-166575ef]{position:absolute;left:0;top:0;height:%?170?%;width:%?220?%;padding:0 %?10?%}.coupon-action .coupon-body .coupon-list .coupon-box .coupon-l-box .coupon-amount[data-v-166575ef]{width:100%;text-align:center;padding-top:%?30?%;font-size:%?50?%;color:#c21313}.coupon-action .coupon-body .coupon-list .coupon-box .coupon-l-box .coupon-amount uni-text[data-v-166575ef]{font-size:%?28?%}.coupon-action .coupon-body .coupon-list .coupon-box .coupon-l-box .coupon-condition[data-v-166575ef]{text-align:center;color:#c21313;width:100%;padding-top:%?10?%}.coupon-action .coupon-body .coupon-list .coupon-box .coupon-r-box[data-v-166575ef]{float:left;margin-left:%?260?%;height:%?170?%;position:relative}.coupon-action .coupon-body .coupon-list .coupon-box .coupon-r-box .coupon-name[data-v-166575ef]{font-size:%?30?%;padding-top:%?36?%}.coupon-action .coupon-body .coupon-list .coupon-box .coupon-r-box .coupon-valid[data-v-166575ef]{padding-top:%?24?%;font-size:%?28?%}.coupon-action .coupon-body .coupon-list .coupon-box .coupon-corner-checkbox[data-v-166575ef]{position:absolute;color:#999;right:%?30?%;height:%?40?%;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.coupon-action .coupon-body .coupon-list .coupon-box .coupon-corner-checkbox uni-text[data-v-166575ef]{font-size:%?40?%}.coupon-action .coupon-body .coupon-list .coupon-box .coupon-corner-checkbox .active[data-v-166575ef]{color:#c21313}.coupon-action .close[data-v-166575ef]{position:absolute;top:%?30?%;right:%?30?%;z-index:1;color:#c8c9cc;font-size:%?44?%;cursor:pointer}.coupon-show[data-v-166575ef]{-webkit-transform:translateZ(0);transform:translateZ(0)}',""]),t.exports=a},efc6:function(t,a,o){"use strict";var i=o("9bf6"),e=o.n(i);e.a},f76b:function(t,a,o){var i=o("24fb");a=i(!1),a.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.address-action[data-v-a11af922]{position:fixed;left:0;bottom:0;background-color:#fff;width:100%;border-radius:%?20?% %?20?% 0 0;display:flex;flex-direction:column;align-items:stretch;min-height:50%;max-height:80%;font-size:%?28?%;z-index:9999;overflow:hidden;transition:all .3s cubic-bezier(.65,.7,.7,.9);-webkit-transform:translate3d(0,100%,0);transform:translate3d(0,100%,0)}.address-action .address-title[data-v-a11af922]{font-size:%?32?%;text-align:center;width:100%;height:%?100?%;background-color:#fff;line-height:%?100?%}.address-action .address-button[data-v-a11af922]{width:100%;height:%?120?%;line-height:%?120?%;position:absolute;left:0;bottom:0;background-color:#fff}.address-action .address-button uni-text[data-v-a11af922]{text-align:center;background-color:#c21313;width:90%;height:%?100?%;line-height:%?100?%;margin:%?10?% auto;display:block;font-size:%?30?%;color:#fff;border-radius:%?40?%}.address-action .address-body[data-v-a11af922]{flex:1 1 auto;min-height:%?88?%;overflow-y:scroll;-webkit-overflow-scrolling:touch}.address-action .address-body .address-empty[data-v-a11af922]{width:100%;text-align:center;font-size:%?36?%;height:%?100?%;line-height:%?100?%;position:absolute;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.address-action .address-body .address-list[data-v-a11af922]{width:95%;margin-left:2.5%;float:left;margin-top:%?20?%}.address-action .address-body .address-list .address-box[data-v-a11af922]{float:left;margin-bottom:%?20?%;width:100%;height:%?170?%;background-color:#fff;border-radius:%?20?%;position:relative}.address-action .address-body .address-list .address-box .address-r-box[data-v-a11af922]{float:left;margin-left:%?60?%;height:%?170?%;position:relative}.address-action .address-body .address-list .address-box .address-r-box .address-name[data-v-a11af922]{font-size:%?30?%;padding-top:%?36?%}.address-action .address-body .address-list .address-box .address-r-box .address-valid[data-v-a11af922]{padding-top:%?24?%;font-size:%?28?%}.address-action .address-body .address-list .address-box .address-corner-checkbox[data-v-a11af922]{position:absolute;color:#999;right:%?30?%;height:%?40?%;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.address-action .address-body .address-list .address-box .address-corner-checkbox uni-text[data-v-a11af922]{font-size:%?40?%}.address-action .address-body .address-list .address-box .address-corner-checkbox .active[data-v-a11af922]{color:#c21313}.address-action .close[data-v-a11af922]{position:absolute;top:%?30?%;right:%?30?%;z-index:1;color:#c8c9cc;font-size:%?44?%;cursor:pointer}.address-show[data-v-a11af922]{-webkit-transform:translateZ(0);transform:translateZ(0)}',""]),t.exports=a}}]);