import { getPaymentMethod } from "./http";
import * as utils from "./utils";
import store from "../store/index";
import storage from 'common/storage';
// #ifdef H5
import wx from "weixin-js-sdk";
// #endif

export default {
	
	// pay,order,rechange
	payType: 'pay',
	
	setPayType(str){
		this.payType = str;
		return this;
	},
	
	getPaymentType(){
		let platformAgent = utils.platformAgent();
		if(platformAgent.isMini){
			return 3;
		}else if(platformAgent.type == "h5"){
			if(platformAgent.isWechat){
				return 2;
			}
			
			return 1;
		}else if(platformAgent.type == "app"){
			return 4;
		}
	},
	
	getPaymentList(pay_type){
		let payType = pay_type || "order";
		let providerList = [];
		
		let type = "";
		let platformAgent = utils.platformAgent();
		if(platformAgent.isMini){
			type = "mp";
		}else if(platformAgent.type == "h5"){
			type = platformAgent.isWechat ? "wechat" : "h5";
		}else if(platformAgent.type == "app"){
			type = "app";
		}
		
		return new Promise((resolve, reject)=>{
			getPaymentMethod({
				type: type,pay_type: payType
			}).then(res=>{
				this.getAppPayment(res.data).then(rs=>{
					resolve(rs);
				}).catch(err=>{
					reject(err);
				});
			}).catch(err=>{
				reject(err);
			});
		});
	},
	
	getAppPayment(providerList){
		return new Promise((resolve, reject)=>{
			uni.getProvider({
				service: 'payment',
				success: (e) => {
					let data = []
					for (let i = 0; i < e.provider.length; i++) {
						switch (e.provider[i]) {
							case 'wxpay':
								data.push("wechat")
								break;
							case 'alipay':
								data.push("alipay")
								break;
							default:
								break;
						}
					}
					
					let array = [];
					if(data.length <= 0){
						resolve(providerList);
					}else{
						for(let i=0; i<providerList.length; i++){
							if(utils.in_array(providerList[i].id,data)){
								array.push(providerList[i]);
							}
						}
						resolve(array);
					}
				},
				fail: (e) => {
					reject("获取支付通道失败");
				}
			});
		});
	},
	
	crreateOrder(data,updateCart){
		let updateCartCount = updateCart || false;
		if(updateCartCount){
			store.commit("UPDATECART",data.shop_count);
			if(data.shop_count > 0){
				uni.setTabBarBadge({ index: 3, text: data.shop_count.toString() });
			}else{
				uni.removeTabBarBadge({ index: 3 });
			}
		}
		
		if(data.pay == 0){
			utils.redirectTo('order/detail',{ id: data.order_id });
			return true;
		}else if(data.pay == 99){
			if(this.payType == "pay"){
				storage.set("order_msg",data.msg);
				storage.set("order_id",data.order_id);
				utils.redirectTo('cart/info');
			}else if(this.payType == "order" || this.payType == "rechange"){
				utils.msg(data.msg);
			}
			return true;
		}
		
		let platformAgent = utils.platformAgent();
		if(platformAgent.isMini){
			this.createMpPayment(data);
		}else if(platformAgent.type == "h5"){
			this.createWebPayment(data);
		}else if(platformAgent.type == "app"){
			this.createAppPayment(data);
		}
	},
	
	createMpPayment(data){
		let that = this;
		switch (data.pay+"") {
			case "1":
				let params = data.result.params;
				wx.requestPayment({
					timeStamp: params.timeStamp,
					nonceStr: params.nonceStr,
					package: params.package,
					signType: params.signType,
					paySign: params.paySign,
					success (res) { 
						uni.showToast({
							title: "您己支付成功!",
							success: function (res){
								if(that.payType == "pay" || that.payType == "order"){
									utils.redirectTo('order/detail',{ id: data.order_id });
								}else{
									utils.redirectTo('bill/fund');
								}
							}
						});
					},
					fail (res) { 
						uni.showModal({
							content: "支付失败，原因：" + JSON.stringify(res),
							showCancel: false
						})
					}
				});
				break;
		}
	},
	
	createWebPayment(data){
		let that = this;
		switch (data.pay+"") {
			case "1":
				wx.config(data.result.config);
				let options = data.result.options;
				let that = this;
				options.success = function(){
					uni.showToast({
						title: "您己支付成功!",
						success: function (res){
							if(that.payType == "pay" || that.payType == "order"){
								utils.redirectTo('order/detail',{ id: data.order_id });
							}else{
								utils.redirectTo('bill/fund');
							}
						}
					});
				}
				wx.chooseWXPay(options);
				break;
			case "2":
				if(that.payType == "pay" || that.payType == "order"){
					location.href = data.result.url+"&redirect_url="+location.origin+'/wap/order/detail/'+data.order_id;
				}else{
					location.href = data.result.url+"&redirect_url="+location.origin+'/wap/bill/fund';
				}
				break;
		}
	},
	
	createAppPayment(data){
		switch (data.pay+"") {
			case "1":
				this.requestPayment(data.result.params,"wxpay",{ id: data.order_id });
				break;
		}
	},
	
	/**
	 * 调起APP支付
	 */
	async requestPayment(orderInfo,type,orderParams) {
		if (!orderInfo) {
			uni.showModal({
				content: '获取支付信息失败',
				showCancel: false
			})
			return
		}
		
		let that = this;
		uni.requestPayment({
			provider: type,
			orderInfo: orderInfo,
			success: (e) => {
				uni.showToast({
					title: "您己支付成功!",
					success: function (res){
						if(that.payType == "pay" || that.payType == "order"){
							utils.redirectTo('order/detail',orderParams);
						}else{
							utils.redirectTo('bill/fund');
						}
					}
				})
			},
			fail: (e) => {
				uni.showModal({
					content: "支付失败,原因为: " + e.errMsg,
					showCancel: false
				})
			}
		})
	}
	
}