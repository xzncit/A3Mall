import storage from './storage';

export default {
	
	getSubscribeTemplate(field=null){
		let cache = storage.getJson('template');
		if(field == null){
		  return cache;
		}
		
		return cache[field] != undefined ? cache[field] : null;
	},
	
	order(){
		let that = this;
		// #ifdef MP-WEIXIN
		let subscribeTemplate = this.getSubscribeTemplate();
		if(Object.keys(subscribeTemplate).length > 0){
			wx.getSetting({
			  withSubscriptions: true,
			  success(res){
				if(res.subscriptionsSetting && res.subscriptionsSetting.mainSwitch){
					let delivery_notice = that.getSubscribeTemplate("delivery_notice");
					let order_complete = that.getSubscribeTemplate("order_complete");
					let order_pay_success = that.getSubscribeTemplate("order_pay_success");
					let flag = true;
					let arr = [];
				  
					if (!res.subscriptionsSetting.itemSettings) {
						arr.push(delivery_notice);
						arr.push(order_complete);
						arr.push(order_pay_success);
					}else if(res.subscriptionsSetting.itemSettings){
						if(!res.subscriptionsSetting.itemSettings[delivery_notice]){
							arr.push(delivery_notice);
						}
		
						if(!res.subscriptionsSetting.itemSettings[order_complete]){
						  arr.push(order_complete);
						}
		
						if(!res.subscriptionsSetting.itemSettings[order_pay_success]){
						  arr.push(order_pay_success);
						}
		
						if(arr.length <= 0){
						  flag = false;
						}
				  }
		
				  if(flag){
					wx.showModal({
					  title: "授权",
					  content: "订阅消息授权",
					  success(res){
						if (res.confirm) {
						  wx.requestSubscribeMessage({
							tmplIds: arr,
							success(res){
							  console.log(res)
							}
						  });
						}
					  }
					});
				  }
				}
			  }
			});
		}
		// #endif
	},
	
	refund(){
		let that = this;
		// #ifdef MP-WEIXIN
		if(this.getSubscribeTemplate("refund_notice") != null){
			wx.getSetting({
			  withSubscriptions: true,
			  success(res){
				if(res.subscriptionsSetting && res.subscriptionsSetting.mainSwitch){
				  let refund_notice = that.getSubscribeTemplate("refund_notice");
				  let flag = true;
				  let arr = [];
				  
				  if (!res.subscriptionsSetting.itemSettings) {
					arr.push(refund_notice);
				  }else if(res.subscriptionsSetting.itemSettings){
					if(!res.subscriptionsSetting.itemSettings[refund_notice]){
					  arr.push(refund_notice);
					}
			
					if(arr.length <= 0){
					  flag = false;
					}
				  }
			
				  if(flag){
					wx.showModal({
					  title: "授权",
					  content: "订阅消息授权",
					  success(res){
						if (res.confirm) {
						  wx.requestSubscribeMessage({
							tmplIds: arr,
							success(res){
							  console.log(res)
							}
						  });
						}
					  }
					});
				  }
				}
			  }
			})
		}
		// #endif
	}
	
}
