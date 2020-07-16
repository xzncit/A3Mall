import request from '../utils/Request'
import router from '../router';
import {Toast} from "vant";

export function getHomeCommon() {
    return request.get("/index");
}

export function getHomeList(param) {
    return request.get("/index/list",param);
}

export function getActivityList(param) {
    return new Promise((resolve,reject)=>{
        request.get("/activity",param).then(res=>{
            resolve(res)
        }).catch(error=>{
            router.replace("/404");
        });
    });
}

export function getActivityDetail(param) {
    return new Promise((resolve, reject)=>{
        request.get("/activity/viewa",param).then(res=>{
            resolve(res);
        }).catch(error=>{
            router.replace("/404");
        })
    });
}

export function getCartList(params) {
    return new Promise((resolve,reject)=>{
        request.get("/cart",params).then((result)=>{
            resolve(result);
        }).catch((error)=>{
            reject(error);
        });
    });
}

export function deleteCart(params) {
    return new Promise((resolve,reject)=>{
        request.post("/cart/delete",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        })
    });
}

export function updateCartGoods(params) {
    return new Promise((resolve,reject)=>{
        request.post("/cart/change",params).then(res=>{
            resolve(res);
        }).catch(err=>{
            reject(err);
        });
    });
}

export function getCartConfirm(params) {
    return new Promise((resolve,reject)=>{
        request.get("/order/confirm",params).then((res)=>{
            resolve(res)
        }).catch((err)=>{
            router.replace("/404");
        });
    });
}

export function createOrder(params) {
    return new Promise((resolve,reject)=>{
        request.post("/order/create",params).then((res)=>{
            resolve(res);
        }).catch((err)=>{
            reject(err);
        });
    });
}

export function getCategoryList() {
    return new Promise((resolve, reject)=>{
        request.get("/category").then((result)=>{
            resolve(result)
        }).catch(err=>{
            reject(err)
        });
    });
}

export function getCouponList(params) {
    return new Promise((resolve, reject) => {
        request.get("/bonus/receive",params).then(res=>{
            resolve(res);
        }).catch(err=>{
            router.replace("/404");
        });
    });
}

export function getCouponLoad(params) {
    return new Promise((resolve, reject) => {
        request.get("/bonus",params).then(result=>{
            resolve(result);
        }).catch((error)=>{
            router.replace("/404");
        });
    });
}

export function getGoodsHot(params) {
    return new Promise((resolve, reject) => {
        request.get("/products/hot",params).then((result)=>{
            resolve(result);
        }).catch((error)=>{
            reject(error);
        });
    });
}

export function getGoodsList(params) {
    return new Promise((resolve, reject) => {
        request.get("/goods/list",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    });
}

export function getGoodsRecommend(params) {
    return new Promise((resolve, reject) => {
        request.get("/products/recommend",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error);
        });
    });
}

export function getGoodsDetail(params) {
    return new Promise((resolve, reject) => {
        request.get("/goods/view",params).then((result)=>{
            resolve(result);
        }).catch(err=>{
            reject(err);
        });
    });
}

export function goodsDetailFavorite(params) {
    return new Promise((resolve, reject) => {
        request.get("/goods/favorite",params).then((result)=>{
            resolve(result)
        }).catch(err=>{
            reject(err);
        });
    });
}

export function goodsDetailAddCart(params) {
    return new Promise((resolve, reject) => {
        request.post("/cart/add",params).then((result)=>{
            resolve(result);
        }).catch((error)=>{
            reject(error);
        });
    });
}

export function getGroupList(params) {
    return new Promise((resolve, reject) => {
        request.get("/group",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    });
}

export function getGroupDetail(params) {
    return new Promise((resolve, reject) => {
        request.get("/group/view",params).then((result)=>{
            resolve(result);
        }).catch(err=>{
            router.replace("/404");
        });
    });
}

export function getNewsList(params) {
    return new Promise((resolve, reject) => {
        request.get("/news",params).then((result)=>{
            resolve(result);
        }).catch((error)=>{
            reject(error);
        });
    });
}

export function getNewsDetail(params) {
    return new Promise((resolve, reject) => {
        request.get("/news/view",params).then(res=>{
            resolve(res);
        }).catch(err=>{
            router.replace("/404");
        });
    })
}

export function getPointList(params) {
    return new Promise((resolve, reject) => {
        request.get("/point",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    });
}

export function getPointDetail(params) {
    return new Promise((resolve, reject) => {
        request.get("/point/view",params).then((result)=>{
            resolve(result);
        }).catch(error=>{
            router.replace("/404");
        });
    });
}

export function sendSMS(params) {
    return new Promise((resolve, reject) => {
        request.get("/send_sms",params).then(function (result) {
            resolve(result)
        }).catch(function (error) {
            reject(error)
        });
    });
}

export function sendForget(params) {
    return new Promise((resolve, reject) => {
        request.post("/forget",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    });
}

export function sendLogin(params) {
    return new Promise((resolve, reject) => {
        request.post('/public/login',params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    });
}

export function sendOauth(params) {
    return new Promise((resolve, reject) => {
        request.post("/oauth",params).then(result=>{
            resolve(result)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function register(params) {
    return new Promise((resolve, reject) => {
        request.post("/register",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    })
}

export function getSearchKeywords() {
    return new Promise((resolve, reject) => {
        request.get("/search").then((result)=>{
            resolve(result)
        }).catch(err=>[
            reject(err)
        ]);
    })
}

export function getSearchList(params) {
    return new Promise((resolve, reject) => {
        request.get("/list",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    })
}

export function getSecondList(params) {
    return new Promise((resolve, reject) => {
        request.get("/second",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    });
}

export function getSecondDetail(params) {
    return new Promise((resolve, reject) => {
        request.get("/second/view",params).then((result)=>{
            resolve(result)
        }).catch(err=>{
            router.replace("/404");
        });
    })
}

export function getSpecialList(params) {
    return new Promise((resolve, reject) => {
        request.get("/special",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    })
}

export function getSpeciaDetail(params) {
    return new Promise((resolve, reject) => {
        request.get("/special/view",params).then((result)=>{
            resolve(result)
        }).catch(err=>{
            router.replace("/404");
        });
    })
}

export function getUcenter() {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/info").then((res)=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    });
}

export function getAddress() {
    return new Promise((resolve, reject) => {
        request.get("ucenter/address/list").then(res=>{
            resolve(res)
        }).catch(err=>{
            router.replace("/404");
        });
    });
}

export function editorAddress(params) {
    return new Promise((resolve, reject) => {
        request.post("ucenter/address/save",params).then((res)=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    });
}

export function editorAddressDelete(params) {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/address/delete",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getCollectList(params) {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/favorite",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    })
}

export function deleteCollect(params) {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/favorite_delete",params).then((res) => {
            resolve(res)
        }).catch((error) => {
            reject(error);
        });
    })
}

export function getCoupon(params) {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/coupon",{
            type: this.isActive,
            page: this.page
        }).then(result=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    })
}

export function getUcenterGoodsList(params) {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/coupon/goods",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    })
}

export function gethelp() {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/help").then((res)=>{
            resolve(res)
        }).catch((error)=>{
            reject(error);
        });
    })
}

export function getUcenterPointList(params) {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/point",params).then((res) => {
            resolve(res)
        }).catch((error) => {
            reject(error)
        });
    })
}

export function getUserInfo() {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/info").then((res)=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function editUserInfo(params) {
    return new Promise((resolve, reject) => {
        request.post("/ucenter/setting",params).then((res)=>{
            resolve(res);
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getWallet() {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/info").then((res)=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getOrderConfirmDelivery(params) {
    return new Promise((resolve, reject) => {
        request.post("/order/confirm_delivery",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getOrderDeliveryList(params) {
    return new Promise((resolve, reject) => {
        request.post("/order/delivery",params).then((res)=>{
            resolve(res)
        }).catch((err)=>{
            reject(err)
        });
    })
}

export function getOrderDetail(params) {
    return new Promise((resolve, reject) => {
        request.post("/order/detail",params).then((res)=>{
            resolve(res)
        }).catch((err)=>{
            router.replace("/404");
        });
    })
}

export function getOrderDetailPayment(params) {
    return new Promise((resolve, reject) => {
        request.get("/order/payment",{
            order_id: this.$route.params.id
        }).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getOrderDetailCancel(params) {
    return new Promise((resolve, reject) => {
        request.get("/order/cancel",{
            order_id: this.$route.params.id
        }).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function sendOrderEvaluate(params) {
    return new Promise((resolve, reject) => {
        request.post("/order/do_evaluate",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getOrderEvaluate(params) {
    return new Promise((resolve, reject) => {
        request.post("/order/evaluate",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getOrderList(params) {
    return new Promise((resolve, reject) => {
        request.get("/order/list",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getOrderListCancel(params) {
    return new Promise((resolve, reject) => {
        request.get("/order/cancel",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getOrderRefund(params) {
    return new Promise((resolve, reject) => {
        request.post("/order/refund",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function sendOrderRefund(params) {
    return new Promise((resolve, reject) => {
        request.post("/order/apply_refund",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getOrderService(params) {
    return new Promise((resolve, reject) => {
        request.get("/order/service",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

