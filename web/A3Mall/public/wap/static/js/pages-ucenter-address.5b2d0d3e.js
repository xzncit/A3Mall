(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-ucenter-address"],{"0a76":function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.placeholder-box[data-v-58a7c612]{height:35px}.navbar-box[data-v-58a7c612]{position:fixed;z-index:100000;topL:0;left:0;width:100%;height:35px}.navbar-box .status-bar[data-v-58a7c612]{width:100%;float:left}.navbar-box .search-box[data-v-58a7c612]{width:100%;height:45px;float:left}.navbar-box .search-box .search-input[data-v-58a7c612]{position:relative;color:#fff;height:35px;line-height:35px;border-radius:%?50?%;margin:%?10?% %?20?%;background-color:#fff;color:#666}.navbar-box .search-box .search-input[data-v-58a7c612]::before{position:absolute;content:"\\e629";left:%?30?%;top:%?0?%;font-size:%?38?%;color:#aaa}.navbar-box .search-box .search-input uni-text[data-v-58a7c612]{padding-left:%?90?%;font-size:%?30?%}.navbar-box .navbar[data-v-58a7c612]{float:left;width:100%;position:relative}.navbar-box .navbar .title[data-v-58a7c612]{width:100%;text-align:center;font-size:%?33?%;font-size:%?29?%}.navbar-box .navbar .prevPage[data-v-58a7c612]{position:absolute;left:%?20?%;top:2%;width:%?60?%;height:%?60?%}.navbar-box .navbar .prevPage uni-text[data-v-58a7c612]{color:#666;font-size:%?65?%;font-weight:700}.navbar-box .navbar .backPage[data-v-58a7c612]{background-color:rgba(0,0,0,.5);border-radius:50%}.navbar-box .navbar .backPage uni-text[data-v-58a7c612]{color:#fff;position:absolute;left:30%;top:50%;-webkit-transform:translate(-30%,-50%);transform:translate(-30%,-50%)}.navbar-box .navbar .statusLine[data-v-58a7c612]{top:%?20?%}',""]),t.exports=e},"0ea3":function(t,e,i){"use strict";var a=i("a07a"),n=i.n(a);n.a},"232f":function(t,e,i){"use strict";i.r(e);var a=i("43a4"),n=i("aee7");for(var s in n)"default"!==s&&function(t){i.d(e,t,(function(){return n[t]}))}(s);i("0ea3");var o,r=i("f0c5"),l=Object(r["a"])(n["default"],a["b"],a["c"],!1,null,"2665e8c5",null,!1,a["a"],o);e["default"]=l.exports},"2d48":function(t,e,i){var a=i("0a76");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=i("4f06").default;n("3b19c9d6",a,!0,{sourceMap:!1,shadowMode:!1})},"43a4":function(t,e,i){"use strict";i.d(e,"b",(function(){return n})),i.d(e,"c",(function(){return s})),i.d(e,"a",(function(){return a}));var a={navbar:i("a9f4").default,emptyBox:i("ed56").default},n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("navbar",{attrs:{iSimmersive:!1,"title-color":"#ffffff",background:"#1b43c4",placeholder:!0,title:"我的地址"}}),t.isLoading||0!=t.isError?t._e():i("v-uni-view",[i("v-uni-view",{staticClass:"address-list"},t._l(t.list,(function(e,a){return i("v-uni-view",{key:a,staticClass:"address-box"},[i("v-uni-view",{staticClass:"t"},[i("v-uni-view",{staticClass:"t-1"},[i("v-uni-text",[t._v(t._s(e.name))]),i("v-uni-text",[t._v(t._s(e.tel))])],1),i("v-uni-view",{staticClass:"desc"},[t._v(t._s(e.address))])],1),i("v-uni-view",{staticClass:"b"},[e.is_default?i("v-uni-view",{staticClass:"b-1"},[t._v("默认地址")]):t._e(),i("v-uni-view",{staticClass:"b-2"},[i("v-uni-text",{on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.onEdit(e.id)}}},[t._v("修改")]),i("v-uni-text",{on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.onDelete(e.id)}}},[t._v("删除")])],1)],1)],1)})),1),i("v-uni-view",{staticClass:"btn"},[i("v-uni-view",{staticClass:"add",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.onAdd.apply(void 0,arguments)}}},[t._v("新增地址")])],1)],1),t.isLoading?i("loading"):t._e(),t.isError&&0==t.isLoading?i("empty-box",{attrs:{type:"address"},on:{onEvents:function(e){arguments[0]=e=t.$handleEvent(e),t.onAdd.apply(void 0,arguments)}}}):t._e()],1)},s=[]},"623a":function(t,e,i){"use strict";i.r(e);var a=i("91fb"),n=i.n(a);for(var s in a)"default"!==s&&function(t){i.d(e,t,(function(){return a[t]}))}(s);e["default"]=n.a},"6a58":function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={set:function(t,e){sessionStorage.setItem(t,vlaue)},setJson:function(t,e){sessionStorage.setItem(t,JSON.stringify(e))},get:function(t){return sessionStorage.getItem(t)},getJson:function(t){var e=sessionStorage.getItem(t);return e?JSON.parse(e):null},remove:function(t){sessionStorage.removeItem(t)},clear:function(){sessionStorage.clear()}};e.default=a},"91fb":function(t,e,i){"use strict";var a=i("4ea4");i("99af"),i("fb6a"),i("a9e3"),i("e25e"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;a(i("6a58"));var n={props:{value:{type:[String,Number],default:0},scroll:{type:[String,Number],default:0},placeholder:{type:Boolean,default:!1},isShow:{type:Boolean,default:!0},isPrev:{type:Boolean,default:!0},isSearch:{type:Boolean,default:!1},isNavTitle:{type:Boolean,default:!0},title:{type:String,default:""},titleColor:{type:String,default:"#000000"},background:{type:String,default:"transparent"},iSimmersive:{type:Boolean,default:!1},onBack:{type:Function,default:null}},data:function(){return{statusBar:10,menuClientRect:{height:35,searchHeight:0},bg:"",fontColor:"",isTitle:!0,isNavbar:!0}},mounted:function(){var t=uni.getSystemInfoSync();this.isNavbar=this.isShow,this.bg=this.background,this.fontColor=this.titleColor,this.iSimmersive?(this.isTitle=!1,this.isNavbar=!1,this.setNavigationBarColor("#ffffff")):(this.bg="transparent"!=this.background?this.background:"#ffffff",this.setNavigationBarColor(this.titleColor)),this.isNavTitle||(this.menuClientRect.height=0),this.statusBar=0,this.isSearch&&(this.menuClientRect.searchHeight=45);var e=this.menuClientRect.height+this.statusBar;this.$emit("input",t.screenHeight-e-t.windowBottom)},methods:{onJumpSearch:function(){this.$utils.navigateTo("search/index")},prev:function(){if(this.onBack)this.onBack();else{var t=getCurrentPages();t&&t.length>1?uni.navigateBack():t.length<=1&&this.$utils.switchTab("index/index")}},setNavigationBarColor:function(t){this.fontColor=t},color2Rgb:function(t){var e=t.toLowerCase();if(/^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/.test(e)){if(4===e.length){for(var i="#",a=1;a<4;a+=1)i+=e.slice(a,a+1).concat(e.slice(a,a+1));e=i}for(var n=[],s=1;s<7;s+=2)n.push(parseInt("0x"+e.slice(s,s+2)));return n.join(",")}return t}},watch:{scroll:{handler:function(t,e){if(!this.iSimmersive)return!1;var i="#ffffff",a="#000000";"transparent"!=this.background&&(i=this.background,a="#ffffff");var n=this.color2Rgb(i);t>=10&&t<=50?(this.bg="rgba("+n+",.3)",this.setNavigationBarColor(a),this.isTitle=!0,this.isNavbar=!0):t>=51&&t<=99?(this.bg="rgba("+n+",.7)",this.setNavigationBarColor(a),this.isTitle=!0,this.isNavbar=!0):t>=100?(this.bg="rgba("+n+",1)",this.setNavigationBarColor(a),this.isTitle=!0,this.isNavbar=!0):t<10&&(this.bg="rgba("+n+",0)",this.setNavigationBarColor("#ffffff"),this.isTitle=!1,this.isNavbar=!1)},deep:!0}}};e.default=n},a07a:function(t,e,i){var a=i("a09a");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=i("4f06").default;n("04bb96b5",a,!0,{sourceMap:!1,shadowMode:!1})},a09a:function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.address-list .address-box[data-v-2665e8c5]{background-color:#fff;width:100%;height:auto!important;height:%?260?%;float:left;margin-top:%?30?%;min-height:%?260?%}.address-list .address-box .t[data-v-2665e8c5]{padding:%?30?%;border-bottom:%?2?% solid #e8e8e8;color:#777;font-size:%?30?%}.address-list .address-box .t .t-1[data-v-2665e8c5]{height:%?52?%;min-height:%?52?%}.address-list .address-box .t .t-1 uni-text[data-v-2665e8c5]:first-child{float:left}.address-list .address-box .t .t-1 uni-text[data-v-2665e8c5]:last-child{float:right}.address-list .address-box .b[data-v-2665e8c5]{height:%?100?%;color:#777;font-size:%?30?%;padding:0 %?30?%}.address-list .address-box .b .b-1[data-v-2665e8c5]{float:left;display:inline-block;color:#1b43c4;border:%?1?% solid #1b43c4;border-radius:%?10?%;font-size:%?24?%;width:%?130?%;height:%?50?%;line-height:%?50?%;text-align:center;margin-top:%?24?%}.address-list .address-box .b .b-2[data-v-2665e8c5]{float:right}.address-list .address-box .b .b-2 uni-text[data-v-2665e8c5]{display:inline-block;color:#777;border:%?1?% solid #777;border-radius:%?10?%;font-size:%?24?%;width:%?130?%;height:%?50?%;line-height:%?50?%;text-align:center;margin-top:%?24?%;margin-left:%?20?%}.btn[data-v-2665e8c5]{width:100%;background-color:#fff;position:fixed;left:0;bottom:0;height:%?120?%;padding-top:%?20?%}.btn .add[data-v-2665e8c5]{display:block;height:%?100?%;line-height:%?100?%;text-align:center;background-color:#1b43c4;color:#fff;margin:0 %?30?%;border-radius:%?10?%}',""]),t.exports=e},a9f4:function(t,e,i){"use strict";i.r(e);var a=i("f9a9"),n=i("623a");for(var s in n)"default"!==s&&function(t){i.d(e,t,(function(){return n[t]}))}(s);i("ed90");var o,r=i("f0c5"),l=Object(r["a"])(n["default"],a["b"],a["c"],!1,null,"58a7c612",null,!1,a["a"],o);e["default"]=l.exports},aee7:function(t,e,i){"use strict";i.r(e);var a=i("db09"),n=i.n(a);for(var s in a)"default"!==s&&function(t){i.d(e,t,(function(){return a[t]}))}(s);e["default"]=n.a},db09:function(t,e,i){"use strict";var a=i("4ea4");i("4de4"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=a(i("0732")),s=a(i("a9f4")),o={components:{loading:n.default,navbar:s.default},data:function(){return{isLoading:!0,isError:!1,list:[]}},onShow:function(){var t=this,e=this.$storage.getJson("users");null==e?this.$utils.navigateTo("public/login"):this.$http.getAddress().then((function(e){e.status?(t.list=e.data,t.list.length?(t.isLoading=!1,t.isError=!1):(t.isLoading=!1,t.isError=!0)):(t.isLoading=!1,t.isError=!0)})).catch((function(e){t.isLoading=!1,t.isError=!0}))},methods:{onDelete:function(t){this.list=this.list.filter((function(e){if(e.id!=t)return e})),this.$http.editorAddressDelete({id:t}),this.list.length?(this.isLoading=!1,this.isError=!1):(this.isLoading=!1,this.isError=!0)},onAdd:function(){this.$utils.navigateTo("ucenter/address_editor")},onEdit:function(t){this.$utils.navigateTo("ucenter/address_editor",{id:t})}}};e.default=o},ed90:function(t,e,i){"use strict";var a=i("2d48"),n=i.n(a);n.a},f9a9:function(t,e,i){"use strict";var a;i.d(e,"b",(function(){return n})),i.d(e,"c",(function(){return s})),i.d(e,"a",(function(){return a}));var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[t.isNavbar||t.isPrev?i("v-uni-view",{staticClass:"navbar-box",style:{height:t.menuClientRect.height+t.statusBar+t.menuClientRect.searchHeight+"px",background:t.bg}},[i("v-uni-view",{staticClass:"status-bar",style:{height:t.statusBar+"px"}}),t.isNavTitle?i("v-uni-view",{staticClass:"navbar",style:{color:t.fontColor,height:t.menuClientRect.height+"px","line-height":t.menuClientRect.height+"px"}},[t.isPrev?i("v-uni-view",{staticClass:"iconfont prevPage",class:{backPage:t.iSimmersive&&!t.isTitle,statusLine:t.iSimmersive&&t.scroll<10},style:{color:t.fontColor,"line-height":t.menuClientRect.height+"px"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.prev.apply(void 0,arguments)}}},[i("v-uni-text",{style:{color:t.fontColor}},[t._v("")])],1):t._e(),t.isTitle?i("v-uni-view",{staticClass:"title",style:{color:t.fontColor,height:t.menuClientRect.height+"px","line-height":t.menuClientRect.height+"px"}},[t._v(t._s(t.title))]):t._e()],1):t._e(),t.isSearch&&t.isTitle?i("v-uni-view",{staticClass:"search-box",style:{background:t.bg}},[i("v-uni-view",{staticClass:"iconfont search-input",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.onJumpSearch.apply(void 0,arguments)}}},[i("v-uni-text",[t._v("请输入关键字")])],1)],1):t._e()],1):t._e(),t.placeholder?i("v-uni-view",{staticClass:"placeholder-box",staticStyle:{width:"100%"},style:{height:t.menuClientRect.height-1+t.menuClientRect.searchHeight+t.statusBar+"px"}}):t._e()],1)},s=[]}}]);