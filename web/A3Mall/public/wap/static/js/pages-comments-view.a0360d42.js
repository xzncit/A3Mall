(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-comments-view"],{"4cff":function(t,o,n){"use strict";n.r(o);var e=n("6830"),s=n.n(e);for(var i in e)"default"!==i&&function(t){n.d(o,t,(function(){return e[t]}))}(i);o["default"]=s.a},"5b13":function(t,o,n){"use strict";n.r(o);var e=n("ef29"),s=n("4cff");for(var i in s)"default"!==i&&function(t){n.d(o,t,(function(){return s[t]}))}(i);n("7f4f");var a,d=n("f0c5"),c=Object(d["a"])(s["default"],e["b"],e["c"],!1,null,"510f2d4f",null,!1,e["a"],a);o["default"]=c.exports},6830:function(t,o,n){"use strict";var e=n("4ea4");n("99af"),Object.defineProperty(o,"__esModule",{value:!0}),o.default=void 0;var s=e(n("3801")),i=e(n("674b")),a={mixins:[s.default],components:{navbar:i.default},data:function(){return{screenHeight:0,options:null,comments:[]}},onLoad:function(t){this.options=t},methods:{downCallback:function(){var t=this;setTimeout((function(){t.mescroll.resetUpScroll()}),1200)},triggerDownScroll:function(){this.mescroll.triggerDownScroll()},upCallback:function(t){var o=this;this.$http.getGoodsComments({page:t.num,type:this.options.type,id:this.options.id}).then((function(n){o.mescroll.endByPage(n.data.list.length,n.data.total),1==n.status?(1==t.num&&(o.comments=[]),o.comments=o.comments.concat(n.data.list)):-1==n.status&&o.mescroll.removeEmpty()})).catch((function(t){o.mescroll.endErr()}))}}};o.default=a},"7f4f":function(t,o,n){"use strict";var e=n("9d24"),s=n.n(e);s.a},"9d24":function(t,o,n){var e=n("bc12");"string"===typeof e&&(e=[[t.i,e,""]]),e.locals&&(t.exports=e.locals);var s=n("4f06").default;s("037ba2a4",e,!0,{sourceMap:!1,shadowMode:!1})},bc12:function(t,o,n){var e=n("24fb");o=e(!1),o.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.goods-comments[data-v-510f2d4f]{margin-top:%?20?%;background-color:#fff;height:auto}.goods-comments .title[data-v-510f2d4f]{height:%?80?%;line-height:%?80?%;font-size:%?32?%;width:100%;border-bottom:%?2?% solid #e8e8e8}.goods-comments .title uni-view[data-v-510f2d4f]:nth-child(1){float:left;color:#333;padding-left:%?30?%}.goods-comments .title uni-view[data-v-510f2d4f]:nth-child(2){float:right;color:#999;padding-right:%?30?%}.goods-comments .comments-empty[data-v-510f2d4f]{padding:%?100?% %?30?%;text-align:center;font-size:%?32?%;color:#666}.goods-comments .goods-comments-list .goods-comments-box[data-v-510f2d4f]{border-bottom:1px solid #e8e8e8;min-height:%?240?%;background-color:#fff;padding-bottom:%?40?%}.goods-comments .goods-comments-list .goods-comments-box .t[data-v-510f2d4f]{padding:0 %?30?%;height:%?170?%;line-height:%?160?%;color:#666}.goods-comments .goods-comments-list .goods-comments-box .t .u[data-v-510f2d4f]{float:left;font-size:%?30?%}.goods-comments .goods-comments-list .goods-comments-box .t .u uni-view[data-v-510f2d4f]{float:left}.goods-comments .goods-comments-list .goods-comments-box .t .u uni-view[data-v-510f2d4f]:first-child{width:%?96?%;height:%?96?%;overflow:hidden;border-radius:50%;background-color:#eee;display:inline-block;position:relative;top:%?30?%}.goods-comments .goods-comments-list .goods-comments-box .t .u uni-view:first-child uni-image[data-v-510f2d4f]{width:%?96?%;height:%?96?%}.goods-comments .goods-comments-list .goods-comments-box .t .u uni-view[data-v-510f2d4f]:last-child{position:relative;left:%?20?%}.goods-comments .goods-comments-list .goods-comments-box .t .time[data-v-510f2d4f]{float:right;font-size:%?28?%}.goods-comments .goods-comments-list .goods-comments-box .c[data-v-510f2d4f]{padding:0 %?30?% %?10?% %?30?%;font-size:%?30?%;color:#333}.goods-comments .goods-comments-list .goods-comments-box .d[data-v-510f2d4f]{background-color:#f7f7f7;margin:0 %?30?%}.goods-comments .goods-comments-list .goods-comments-box .d .d-1[data-v-510f2d4f]{padding:%?10?% %?30?% 0 %?30?%;font-size:%?30?%}.goods-comments .goods-comments-list .goods-comments-box .d .d-2[data-v-510f2d4f]{padding:%?20?% %?30?% %?20?% %?30?%;font-size:%?28?%}',""]),t.exports=o},ef29:function(t,o,n){"use strict";n.d(o,"b",(function(){return s})),n.d(o,"c",(function(){return i})),n.d(o,"a",(function(){return e}));var e={navbar:n("674b").default,mescrollBody:n("5e0f").default},s=function(){var t=this,o=t.$createElement,n=t._self._c||o;return n("v-uni-view",[n("navbar",{attrs:{iSimmersive:!1,placeholder:!0,title:"商品评论"},model:{value:t.screenHeight,callback:function(o){t.screenHeight=o},expression:"screenHeight"}}),n("mescroll-body",{ref:"mescrollRef",attrs:{height:t.screenHeight+"px"},on:{init:function(o){arguments[0]=o=t.$handleEvent(o),t.mescrollInit.apply(void 0,arguments)},down:function(o){arguments[0]=o=t.$handleEvent(o),t.downCallback.apply(void 0,arguments)},up:function(o){arguments[0]=o=t.$handleEvent(o),t.upCallback.apply(void 0,arguments)}}},[n("v-uni-view",{staticClass:"goods-comments clear"},[t.comments.length<=0?n("v-uni-view",{staticClass:"comments-empty"},[t._v("该商品还没有评论哦！")]):t._e(),n("v-uni-view",{staticClass:"goods-comments-list clear"},t._l(t.comments,(function(o,e){return n("v-uni-view",{key:e,staticClass:"goods-comments-box clear"},[n("v-uni-view",{staticClass:"t"},[n("v-uni-view",{staticClass:"u"},[n("v-uni-view",[n("v-uni-image",{attrs:{src:o.avatar}})],1),n("v-uni-view",[t._v(t._s(o.username))])],1),n("v-uni-view",{staticClass:"time"},[t._v(t._s(o.time))])],1),n("v-uni-view",{staticClass:"c"},[t._v(t._s(o.content))]),o.reply_content?n("v-uni-view",{staticClass:"d"},[n("v-uni-view",{staticClass:"d-1"},[t._v("商家回复")]),n("v-uni-view",{staticClass:"d-2"},[t._v(t._s(o.reply_content))])],1):t._e()],1)})),1)],1)],1)],1)},i=[]}}]);