import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from "../views/home/home";
import store from "../store";
import { login } from "../libs/Users"

Vue.use(VueRouter);
const routes = [
    {
        path: '/',
        meta: { title: "", tabbar: true, auth: false },
        component:Home
    },
    {
        path:'/category/index',
        name: 'Category',
        meta: { title: "", tabbar: true, auth: false },
        component:()=>import("../views/category/index")
    },
    {
        path:'/public/oauth',
        name: 'Oauth',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/public/oauth")
    },
    {
        path:'/public/login',
        name: 'Login',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/public/login")
    },
    {
        path:'/public/register',
        name: 'Register',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/public/register")
    },
    {
        path:'/public/forget',
        name: 'Forget',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/public/forget")
    },
    {
        path:'/news',
        name:'News',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/news/index")
    },
    {
        path:'/news/view/:id',
        name:'NewsView',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/news/view")
    },
    {
        path:'/coupon',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/coupon/index")
    },
    {
        path:'/special',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/special/index")
    },
    {
        path:'/special/view/:id',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/special/view")
    },
    {
        path:'/point',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/point/index")
    },
    {
        path:'/point/view/:id',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/point/view")
    },
    {
        path:'/regiment',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/regiment/index")
    },
    {
        path:'/regiment/view/:id',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/regiment/view")
    },
    {
        path:'/second',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/second/index")
    },
    {
        path:'/second/view/:id',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/second/view")
    },
    {
        path:'/cart/index',
        name: 'Cart',
        meta: { title: "", tabbar: true, auth: true },
        component:()=>import("../views/cart/index")
    },
    {
        path:'/cart/confirm',
        name: 'CartConfirm',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/cart/confirm")
    },
    {
        path:'/cart/info',
        name: 'CartInfo',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/cart/info")
    },
    {
        path:'/cart/msg',
        name: 'CartMsg',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/cart/msg")
    },
    {
        path:'/search/index',
        name: 'Search',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/search/index")
    },
    {
        path:'/search/list',
        name: 'SearchList',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/search/list")
    },
    {
        path:'/order/list/:id',
        name: 'OrderList',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/order/list")
    },
    {
        path:'/order/service',
        name: 'OrderService',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/order/service")
    },
    {
        path:'/order/detail/:id',
        name: 'OrderDetail',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/order/detail")
    },
    {
        path:'/order/express/:id',
        name: 'OrderExpress',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/order/express")
    },
    {
        path:'/order/evaluate/:id',
        name: 'OrderEvaluate',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/order/evaluate")
    },
    {
        path:'/order/refund/:id',
        name: 'OrderRefund',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/order/refund")
    },
    {
        path:'/order/confirm_delivery/:id',
        name: 'OrderConfirmDelivery',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/order/confirm_delivery")
    },
    {
        path:'/ucenter/index',
        name: 'Ucenter',
        meta: { title: "", tabbar: true, auth: true },
        component:()=>import("../views/ucenter/index")
    },
    {
        path:'/ucenter/wallet',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/ucenter/wallet")
    },
    {
        path:'/rechange/view',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/rechange/view")
    },
    {
        path:'/ucenter/withdraw',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/ucenter/withdraw")
    },
    {
        path:'/ucenter/bill/cashlist',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/ucenter/cashlist")
    },
    {
        path:'/ucenter/bill/fund',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/ucenter/fund")
    },
    {
        path:'/ucenter/collect',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/ucenter/collect")
    },
    {
        path:'/ucenter/address',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/ucenter/address")
    },
    {
        path:'/ucenter/address/add',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/ucenter/address_editor")
    },
    {
        name: "AddressEditor",
        path:'/ucenter/address/editor/:id',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/ucenter/address_editor")
    },
    {
        path:'/ucenter/coupon',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/ucenter/coupon")
    },
    {
        path:'/ucenter/point',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/ucenter/point")
    },
    {
        path:'/ucenter/setting',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/ucenter/setting")
    },
    {
        path:'/ucenter/help',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/ucenter/help")
    },
    {
        path:'/ucenter/online',
        meta: { title: "", tabbar: false, auth: true },
        component:()=>import("../views/ucenter/online")
    },
    {
        path:'/ucenter/goods',
        name: 'UcenterGoods',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/ucenter/goods")
    },
    {
        path:'/goods/list/:id',
        name: 'GoodsList',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/goods/list")
    },
    {
        path:'/goods/view/:id',
        name: 'GoodsDetail',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/goods/view")
    },
    {
        path:'/comments/:type/:id/',
        name: 'Comments',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/comments/view")
    },
    {
        path:'/goods/hot',
        name: 'GoodsHot',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/goods/hot")
    },
    {
        path:'/goods/recommend',
        name: 'GoodsRecommend',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/goods/recommend")
    },
    {
        path:'/404',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/public/404")
    },
    {
        path:'/error',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/public/error")
    },
    {
        path:'/empty',
        meta: { title: "", tabbar: false, auth: false },
        component:()=>import("../views/public/empty")
    },
    {
        path: '*',
        redirect: '/',
        meta: { title: "", tabbar: true, auth: false },
        component: Home
    }
]

const router = new VueRouter({
    mode: "history",
    routes,
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
});

router.beforeEach(function(to,from,next){
    if(login(to,from)){
        return ;
    }

    document.title = to.meta.title || process.env.VUE_APP_WEB_NAME || "A3Mall B2C商城";
    store.commit("UPDATETABBAR",to.meta.tabbar);
    next();
});

export default router
