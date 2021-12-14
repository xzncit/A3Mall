import storage from './storage';

export function msg(content,time=3000){
	uni.showToast({
		icon:'none',
		title: content,
		duration: time
	});
}

export function showLoading(content="加载数据中...",mask=true){
	uni.showLoading({
	    title: content,
		mask: mask
	});
}

export function hideLoading(timer=0){
	if(timer > 0){
		var t = setTimeout(function () {
		    uni.hideLoading();
			clearTimeout(t);
		}, timer);
	}else{
		uni.hideLoading();
	}
}

export function in_array(search,array){
	let flag = false;
	for(let i in array){
		if(array[i]==search){
			flag = true;
			break;
		}
	}

	return flag;
}

export function isDataType(data,type){
	return Object.prototype.toString.call(data) === '[object '+type+']';
}

export function ltrim(str,char){
	let pos = str.indexOf(char);
	let sonstr = str.substr(pos+1);
	return sonstr;
}

export function rtrim(str,char){
	let pos = str.lastIndexOf(char);
	let sonstr = str.substr(0,pos);
	return sonstr;
}

/**
 * 保留当前页面，跳转到应用内的某个页面，使用uni.navigateBack可以返回到原页面。
 */
export function navigateTo(url,params){
	uni.navigateTo({
		url: parseUrl(url,params)
	})
}

/**
 * 关闭当前页面，跳转到应用内的某个页面。
 */
export function redirectTo(url,params){
	uni.redirectTo({
		url: parseUrl(url,params)
	});
}

/**
 * 关闭所有页面，打开到应用内的某个页面。
 */
export function reLaunch(url,params){
	uni.reLaunch({
		url: parseUrl(url,params)
	});
}

/**
 * 跳转到 tabBar 页面，并关闭其他所有非 tabBar 页面。
 */
export function switchTab(url,params){
	uni.switchTab({
		url: parseUrl(url,params)
	});
}

/**
 * 关闭当前页面，返回上一页面或多级页面
 */
export function navigateBack(delta){
	uni.navigateBack({
		delta: delta
	});
}

/**
 * 预加载页面，是一种性能优化技术。被预载的页面，在打开时速度更快。
 */
export function preloadPage(){
	uni.preloadPage({
		url: parseUrl(url,params)
	});
}

export function prePage(){
	let pages = getCurrentPages();
	let prePage = pages[pages.length - 2];
	// #ifdef H5
	return prePage;
	// #endif
	return prePage.$vm;
}

/**
 * rpx转px
 * @param int|float num
 */
export function rpx2px(num){
	// const info = uni.getSystemInfoSync()
	// let scale = 750 / info.windowWidth;
	// return (Number.isNaN(parseFloat(num)) ? 0 : parseFloat(num)) / scale;
	return uni.upx2px(num);
}

/**
 * @param int|float num
 */
export function px2rpx(num){
	return num/(uni.upx2px(num)/num);
}

export function getSystemInfo(){
	const info = uni.getSystemInfoSync();
	return {
		w: info.windowWidth,
		h: info.windowHeight
	};
}

function parseUrl(url,params){
	let arr = [];
	let string = '';
	for(let i in params){
		arr.push(i + "=" + params[i]);
	}
	
	string = "/pages/" + url;
	if(arr.length > 0){
		string += "?" + arr.join("&");
	}
	
	return string;
}

export function platformAgent(){
	let obj = { type: "", name: "", isWechat: false, isMini: false };
	
	// #ifdef APP-PLUS
	let platform = uni.getSystemInfoSync().platform;
	obj.type = "app";
	obj.name = platform;
	// #endif
	
	// #ifdef H5
	obj.type = "h5";
	obj.name = window.navigator.userAgent;
	obj.isWechat = window.navigator.userAgent.toLowerCase().indexOf("micromessenger") !== -1; // 检查是否在微信公众号打开
	// #endif
	
	// #ifdef MP
	obj.isMini = true;
	// #endif
	
	// #ifdef MP-WEIXIN
	obj.type = "mp-weixin";
	obj.name = "微信小程序";
	// #endif
	
	// #ifdef MP-ALIPAY
	obj.type = "mp-alipay";
	obj.name = "支付宝小程序";
	// #endif
	
	// #ifdef MP-BAIDU
	obj.type = "mp-baidu";
	obj.name = "百度小程序";
	// #endif
	
	// #ifdef MP-TOUTIAO
	obj.type = "mp-toutiao";
	obj.name = "字节跳动小程序";
	// #endif
	
	// #ifdef MP-QQ
	obj.type = "mp-qq";
	obj.name = "QQ小程序";
	// #endif
	
	// #ifdef MP-360
	obj.type = "mp-360";
	obj.name = "360小程序";
	// #endif
	
	return obj;
}

export function isIOS(){
	return !!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
}

export function getShareUrl(url){
	let users = storage.getJson("users");
	
	// #ifdef H5 || APP-PLUS
	if(users != null){
		url += '#u=' + users.id;
	}
	// #endif
	
	return url;
}

export function iCopy(content){ 
	return new Promise((resolve, reject)=>{
		if(!content) {
			reject('复制的内容不能为空 !');
		}else{
			// H5端的复制逻辑
			// #ifdef H5
			if (!document.queryCommandSupported('copy')) {
				reject('浏览器不支持');
			}else{
				let textarea = document.createElement("textarea")
				textarea.value = content
				textarea.readOnly = "readOnly"
				document.body.appendChild(textarea)
				textarea.select()
				textarea.setSelectionRange(0, content.length)
				let result = document.execCommand("copy")
				if(result){
					resolve("内容复制成功")
				}else{
					reject("复制失败，请检查h5中调用该方法的方式，是不是用户点击的方式调用的，如果不是请改为用户点击的方式触发该方法，因为h5中安全性，不能js直接调用！")
				}	
				textarea.remove()
			}
			// #endif
			
			// 小程序端 和 app端的复制功能
			//#ifndef H5
			uni.setClipboardData({
				data: content,
				success: function() {
					resolve("复制成功")
				},
				fail:function(){
					reject("复制失败")
				}
			});
			//#endif
		}
	});
}