(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0f52bc5e"],{"43a4":function(t,s,e){"use strict";e.r(s);var a,i=function(){var t=this,s=t.$createElement,e=t._self._c||s;return e("div",[e("nav-bar",{attrs:{title:"商品详情","left-arrow":"",fixed:!0,placeholder:!0,"z-index":9999},on:{"click-left":t.prev}}),e("div",{style:"height:"+t.clientHeight+"px"},[e("van-pull-refresh",{on:{refresh:t.onRefresh},model:{value:t.isRefresh,callback:function(s){t.isRefresh=s},expression:"isRefresh"}},[e("van-swipe",{staticClass:"swiper-box",attrs:{loop:!0},on:{change:t.onChange},scopedSlots:t._u([{key:"indicator",fn:function(){return[e("div",{staticClass:"custom-indicator"},[t._v(" "+t._s(t.current+1)+" / "+t._s(t.images.length)+" ")])]},proxy:!0}])},t._l(t.images,(function(t,s){return e("van-swipe-item",{key:s},[e("img",{attrs:{src:t}})])})),1),e("div",{staticClass:"goods-price"},[e("div",{staticClass:"price"},[e("span",[t._v("￥"),e("i",[t._v(t._s(t.products.sell_price))])]),e("span",[t._v("原价格"),e("i",[t._v("￥"+t._s(t.products.market_price))])])]),e("div",{staticClass:"count-down-box"},[e("count-down",{attrs:{"now-time":t.products.now_time,"start-time":t.products.start_time,"end-time":t.products.end_time,status:t.isActivityStatus},on:{"update:status":function(s){t.isActivityStatus=s}}})],1)]),e("div",{staticClass:"goods-info clear"},[e("div",{staticClass:"title"},[t._v(" "+t._s(t.products.title)+" ")]),e("div",{staticClass:"goods-info-box"},[e("span",[t._v("库存: "+t._s(t.products.store_nums)+"件")]),e("span",[t._v("销量: "+t._s(t.products.sale)+"件")])])]),e("div",{staticClass:"goods-comments clear"},[e("div",{staticClass:"title"},[e("span",[t._v("商品评价")]),t.comments.length>0?e("span",{on:{click:function(s){return t.$router.push("/comments/regiment/"+t.products.goods_id)}}},[t._v("更多 >")]):t._e()]),t.comments.length<=0?e("div",{staticClass:"comments-empty"},[t._v("该商品还没有评论哦！")]):t._e(),t.comments.length>0?e("div",{staticClass:"goods-comments-list clear"},t._l(t.comments,(function(s,a){return e("div",{key:a,staticClass:"goods-comments-box clear"},[e("div",{staticClass:"t"},[e("div",{staticClass:"u"},[e("span",[e("img",{attrs:{src:s.avatar}})]),e("span",[t._v(t._s(s.username))])]),e("div",{staticClass:"time"},[t._v(t._s(s.time))])]),e("div",{staticClass:"c"},[t._v(t._s(s.content))]),s.reply_content?e("div",{staticClass:"d"},[e("div",{staticClass:"d-1"},[t._v("商家回复")]),e("div",{staticClass:"d-2"},[t._v(t._s(s.reply_content))])]):t._e()])})),0):t._e()]),e("div",{staticClass:"goods-content clear"},[e("div",{staticClass:"title"},[t._v("图文详情")]),e("div",{directives:[{name:"lazy-container",rawName:"v-lazy-container",value:{selector:"img",loading:"../../assets/images/loader.gif"},expression:"{ selector: 'img', loading: '../../assets/images/loader.gif' }"}],staticClass:"clear",domProps:{innerHTML:t._s(t.html)}})])],1)],1),e("sku-action",{attrs:{goods:t.products,attribute:t.attribute,item:t.item,"goods-info":t.selectedGoodsInfo,fields:t.fields},on:{"update:goodsInfo":function(s){t.selectedGoodsInfo=s},"update:goods-info":function(s){t.selectedGoodsInfo=s}},model:{value:t.isSkuStatus,callback:function(s){t.isSkuStatus=s},expression:"isSkuStatus"}}),e("goods-action",[e("goods-action-icon",{attrs:{icon:"home",text:"首页"},on:{click:function(s){return t.$router.replace("/")}}}),e("goods-action-button",{attrs:{type:"buy",text:"立即购买"},on:{click:t.onBuyClicked}})],1)],1)},o=[],n=(e("b0c0"),e("ac1f"),e("5319"),e("ade3")),c=(e("4b0a"),e("2bb1")),r=(e("7844"),e("5596")),d=(e("ab71"),e("58e6")),u=(e("66cf"),e("343b")),l=(e("e7e5"),e("d399")),m=e("2b0e"),v=e("d434"),p=e("85b3"),f=e("3050"),g=e("7cc0"),h=e("ed72"),_=e("a65e");l["a"].setDefaultOptions({duration:5e3}),m["a"].use(u["a"]);var b={name:"RegimentView",components:(a={},Object(n["a"])(a,d["a"].name,d["a"]),Object(n["a"])(a,f["a"].name,f["a"]),Object(n["a"])(a,r["a"].name,r["a"]),Object(n["a"])(a,c["a"].name,c["a"]),Object(n["a"])(a,g["a"].name,g["a"]),Object(n["a"])(a,_["a"].name,_["a"]),Object(n["a"])(a,h["a"].name,h["a"]),Object(n["a"])(a,v["a"].name,v["a"]),Object(n["a"])(a,p["a"].name,p["a"]),a),data:function(){return{fields:["id","goods_id"],isSkuStatus:!1,selectedGoodsInfo:{},products:{},attribute:[],comments:[],item:{},images:[],cartCount:0,current:0,isRefresh:!1,isActivityStatus:0,clientHeight:window.outerHeight-50,html:""}},created:function(){var t=this.$storage.get("users",!0);this.cartCount=null!=t?t.shop_count:0,this.onLoad()},methods:{onLoad:function(){var t=this;this.$http.getRegimentDetail({id:this.$route.params.id}).then((function(s){t.products=s.data.goods;var e=t.$request.domain()+"static/images/loader.gif";t.html=s.data.goods.content.replace(/src=/g," data-loading='"+e+"' data-src="),t.attribute=s.data.attr,t.item=s.data.item,t.images=s.data.photo,t.comments=s.data.comments}))},onChange:function(t){this.current=t},onRefresh:function(){var t=this;setTimeout((function(){t.isRefresh=!1,t.onLoad()}),1500)},onBuyClicked:function(){var t=this;return 0==this.isActivityStatus?(Object(l["a"])("活动已结束！"),!1):2==this.isActivityStatus?(Object(l["a"])("活动未开始！"),!1):0!=this.isSkuStatus?this.selectedGoodsInfo.isSubmit?void this.$store.dispatch("isUsers").then((function(){t.$router.push({path:"/cart/confirm",query:{id:t.selectedGoodsInfo.id,sku_id:t.selectedGoodsInfo.selectedSku.id,num:t.selectedGoodsInfo.num,type:"regiment"}})})).catch((function(){t.$storage.set("VUE_REFERER","/regiment/view/"+t.$route.params.id),t.$router.push("/public/login")})):(Object(l["a"])("请选择规格！"),!1):void(this.isSkuStatus=!0)},prev:function(){this.$tools.prev()}}},C=b,k=(e("ffb0"),e("2877")),y=Object(k["a"])(C,i,o,!1,null,"e0572a62",null);s["default"]=y.exports},a6aa:function(t,s,e){},ffb0:function(t,s,e){"use strict";var a=e("a6aa"),i=e.n(a);i.a}}]);