import { wxLogin,getShareConfig } from './http';
import { getShareUrl,isIOS,platformAgent } from './utils';
import wx from "weixin-js-sdk";

export function login(){
	return new Promise((resolve, reject)=>{
		wxLogin({source:1}).then(res=>{
			if(res.status == 1){
				location.href = `https://open.weixin.qq.com/connect/oauth2/authorize?appid=${res.data.appid}&redirect_uri=${res.data.url}/pages/public/login&response_type=code&scope=snsapi_userinfo&state=${res.data.state}#wechat_redirect`;
			}
		}).catch(err=>{
			reject(err);
		});
	});
}

export function shareConfig(page){
	if(!platformAgent().isWechat){
		return false;
	}
	
	if(isIOS() && page != "home"){
		return false;
	}
	
	let url = getShareUrl(window.location.href);
	getShareConfig({
		url: url
	}).then((res)=>{
		if(res.status){
			wx.config(res.data);
		}else{
			console.log("获取wx分享配置失败");
		}
	});
}

export function setShareData(data){
	if(!platformAgent().isWechat){
		return false;
	}
	
	let url = getShareUrl(window.location.href);
	wx.ready(function () {
		wx.updateAppMessageShareData({
			title: data.title, // 分享标题
			desc: data.desc || '', // 分享描述
			link: url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
			imgUrl: data.imgUrl, // 分享图标
			success: function () {}
		});

		wx.updateTimelineShareData({
			title: data.title, // 分享标题
			link: url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
			imgUrl: data.imgUrl, // 分享图标
			success: function () {}
		});
	});
}

