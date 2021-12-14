import request from './request'
import storage from './storage'

export function getHomeData(param) {
    return request.get("/index",param);
}

export function getHomeList(param) {
    return request.get("/index/list",param);
}

export function getCustomData(param) {
    return request.get("/custom",param);
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

export function getSignConfig() {
    return new Promise((resolve, reject)=>{
        request.get("/sign/index").then((result)=>{
            resolve(result)
        }).catch(err=>{
            reject(err)
        });
    });
}

export function getSignPointList(params) {
    return new Promise((resolve, reject)=>{
        request.get("/sign/point",params).then((result)=>{
            resolve(result)
        }).catch(err=>{
            reject(err)
        });
    });
}

export function sendSign() {
    return new Promise((resolve, reject)=>{
        request.get("/sign/send").then((result)=>{
            resolve(result)
        }).catch(err=>{
            reject(err)
        });
    });
}

export function receiveSignCoupon(params) {
    return new Promise((resolve, reject)=>{
        request.get("/sign/receive",params).then((result)=>{
            resolve(result)
        }).catch(err=>{
            reject(err)
        });
    });
}

export function getGroupList(param) {
    return new Promise((resolve,reject)=>{
        request.get("/group",param).then(res=>{
            resolve(res)
        }).catch(error=>{
            reject(error);
        });
    });
}

export function getGroupDetail(param) {
    return new Promise((resolve, reject)=>{
        request.get("/group/view",param).then(res=>{
            if(res.status != 1){
                reject();
            }else{
                resolve(res);
            }
        }).catch(error=>{
            reject(error);
        })
    });
}

export function getCouponList(params) {
    return new Promise((resolve, reject) => {
        request.get("/bonus/receive",params).then(res=>{
            resolve(res);
        }).catch(err=>{
            reject(err);
        });
    });
}

export function getCouponLoad(params) {
    return new Promise((resolve, reject) => {
        request.get("/bonus",params).then(result=>{
            resolve(result);
        }).catch((error)=>{
            reject(err);
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

export function getGoodsComments(params) {
    return new Promise((resolve, reject) => {
        request.get("/comments/list",params).then((result)=>{
            resolve(result);
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

export function getRegimentList(params) {
    return new Promise((resolve, reject) => {
        request.get("/regiment",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    });
}

export function getRegimentDetail(params) {
    return new Promise((resolve, reject) => {
        request.get("/regiment/view",params).then((result)=>{
            resolve(result);
        }).catch(err=>{
            reject(error)
        });
    });
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
            reject(error)
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
            reject(error)
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
            reject(error)
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
            reject(err);
        });
    })
}

export function searchKeywords(params) {
    return new Promise((resolve, reject) => {
        request.get("/search/keywords",params).then((result)=>{
            resolve(result)
        }).catch(err=>[
            reject(err)
        ]);
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
        request.get("/search/list",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    })
}

export function sendLogin(params) {
    return new Promise((resolve, reject) => {
		let spread_id = parseInt(storage.get('spread_id'));
		if(spread_id > 0){
			params.spread_id = spread_id;
		}
        request.post('/public/login',params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    });
}

export function sendRegister(params) {
    return new Promise((resolve, reject) => {
		let spread_id = parseInt(storage.get('spread_id'));
		if(spread_id > 0){
			params.spread_id = spread_id;
		}
        request.post("/register",params).then((result)=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
        });
    })
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

export function sendSMS(params) {
    return new Promise((resolve, reject) => {
        request.get("/send_sms",params).then(function (result) {
            resolve(result)
        }).catch(function (error) {
            reject(error)
        });
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
            reject(err);
        });
    });
}

export function getCartInfo(params) {
    return new Promise((resolve,reject)=>{
        request.get("/order/info",params).then((res)=>{
            resolve(res)
        }).catch((err)=>{
            reject(err);
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
            reject(err)
        });
    })
}

export function getOrderExpress(params) {
    return new Promise((resolve, reject) => {
        request.post("/order/express",params).then((res)=>{
            resolve(res)
        }).catch((err)=>{
            reject(err)
        });
    })
}

export function getOrderDetailPayment(params) {
    return new Promise((resolve, reject) => {
        request.get("/order/payment",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getOrderDetailCancel(params) {
    return new Promise((resolve, reject) => {
        request.get("/order/cancel",params).then(res=>{
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

export function getUcenter() {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/info").then((res)=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    });
}

export function getWallet() {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/wallet").then((res)=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function paymentWallet(params) {
    return new Promise((resolve, reject) => {
        request.post("/ucenter/rechange",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getWalletFund(params) {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/wallet/fund",params).then((res)=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getWalletCashlist(params) {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/wallet/cashlist",params).then((res)=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getWalletSettlement() {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/settlement").then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function editWalletSettlement(params) {
    return new Promise((resolve, reject) => {
        request.post("/ucenter/settlement_save",params).then(res=>{
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

export function getAddressData(params) {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/address",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err);
        });
    });
}

export function getAddress() {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/address/list").then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err);
        });
    });
}

export function editorAddress(params) {
    return new Promise((resolve, reject) => {
        request.post("/ucenter/address/save",params).then((res)=>{
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

export function getCoupon(params) {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/coupon",params).then(result=>{
            resolve(result)
        }).catch((error)=>{
            reject(error)
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

export function getUserInfo() {
    return new Promise((resolve, reject) => {
        request.get("/ucenter/get_setting").then((res)=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
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

export function domain(){
	return request.domain();
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

export function getSpreadCashrecord(params) {
    return new Promise((resolve, reject) => {
        request.get("/spread/cashrecord",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getCommission(params) {
    return new Promise((resolve, reject) => {
        request.get("/spread/commission",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getSpreadIndex() {
    return new Promise((resolve, reject) => {
        request.get("/spread/index").then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getSpreadPromotionList(params) {
    return new Promise((resolve, reject) => {
        request.get("/spread/promotion_list",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getSpreadPromotionOrder(params) {
    return new Promise((resolve, reject) => {
        request.get("/spread/promotion_order",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getSpreadSettlement() {
    return new Promise((resolve, reject) => {
        request.get("/spread/settlement").then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function editSpreadSettlement(params) {
    return new Promise((resolve, reject) => {
        request.post("/spread/settlement_save",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getShareData(params) {
    return new Promise((resolve, reject) => {
        request.get("/share/index",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function otherLogin(params) {
    return new Promise((resolve, reject) => {
        request.post("/qq",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function wxLogin(params) {
    return new Promise((resolve, reject) => {
		let spread_id = parseInt(storage.get('spread_id'));
		if(spread_id > 0){
			params.spread_id = spread_id;
		}
        request.post("/auth",params).then(result=>{
            resolve(result)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getWxPoster(params) {
    return new Promise((resolve, reject) => {
        request.post("/spread/qrcode",params,false).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}
 
export function getShareConfig(params){
    return new Promise((resolve, reject) => {
        request.post("/share/config",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    });
}

export function autoLogin(){
	return new Promise((resolve, reject) => {
	    request.post("/autologin").then(res=>{
	        resolve(res)
	    }).catch(err=>{
	        reject(err)
	    });
	});
}

export function getServiceList(){
	return new Promise((resolve, reject) => {
	    request.post("/service_list").then(res=>{
	        resolve(res)
	    }).catch(err=>{
	        reject(err)
	    });
	});
}

export function updateMessageInfo(params){
	return new Promise((resolve, reject) => {
	    request.post("/update_message",params).then(res=>{
	        resolve(res)
	    }).catch(err=>{
	        reject(err)
	    });
	});
}

export function getMessageList(params){
	return new Promise((resolve, reject) => {
	    request.get("/get_message_list",params).then(res=>{
	        resolve(res)
	    }).catch(err=>{
	        reject(err)
	    });
	});
}

export function getRechangePrice(){
    return new Promise((resolve, reject) => {
        request.get("/ucenter/rechange_price").then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getTemplateConfig(){
    return request.get("/template");
}

export function getPaymentMethod(params){
	return new Promise((resolve, reject) => {
	    request.post("/payment/index",params).then(res=>{
	        resolve(res)
	    }).catch(err=>{
	        reject(err)
	    });
	})
}

export function getActivityData(){
    return new Promise((resolve, reject) => {
        request.get("/activity/index").then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getActivityPrize(params){
    return new Promise((resolve, reject) => {
        request.post("/activity/prize",params).then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getCopyData(){
    return new Promise((resolve, reject) => {
        request.get("/copy").then(res=>{
            resolve(res)
        }).catch(err=>{
            reject(err)
        });
    })
}

export function getShopData(){
	return new Promise((resolve, reject) => {
	    request.get("/shop/index").then(res=>{
	        resolve(res)
	    }).catch(err=>{
	        reject(err)
	    });
	})
}

export function checkOrderCode(params){
	return new Promise((resolve, reject) => {
	    request.post("/shop/check",params).then(res=>{
	        resolve(res)
	    }).catch(err=>{
	        reject(err)
	    });
	})
}

export function getShopOrderDetail(params){
	return new Promise((resolve, reject) => {
	    request.post("/shop/order_detail",params).then(res=>{
	        resolve(res)
	    }).catch(err=>{
	        reject(err)
	    });
	})
}

export function confirmShopOrder(params){
	return new Promise((resolve, reject) => {
	    request.post("/shop/order",params).then(res=>{
	        resolve(res)
	    }).catch(err=>{
	        reject(err)
	    });
	})
}

export function getUpdateAPK(params){
	return new Promise((resolve, reject) => {
	    request.post("/update",params).then(res=>{
	        resolve(res)
	    }).catch(err=>{
	        reject(err)
	    });
	})
}