import { wechatLogin,getShareConfig } from './http';
import { getShareUrl,isIOS,platformAgent } from './utils';
import wx from "weixin-js-sdk";

export function login(){
	return new Promise((resolve, reject)=>{
		wechatLogin().then(res=>{
			if(res.status == 1){
				location.href = `https://open.weixin.qq.com/connect/oauth2/authorize?appid=${res.data.appid}&redirect_uri=${res.data.url}/pages/public/login&response_type=code&scope=snsapi_userinfo&state=${res.data.state}#wechat_redirect`;
			}
		}).catch(err=>{
			reject(err);
		});
	});
}

