import * as utils from './utils';

export function jump(value){
	if(value.url == ""){
		return ;
	}
	
	switch (value.type+"") {
		case "1":
			//window.location.href = value.url;
			break;
		case "2":
			utils.navigateTo("goods/view",{ id: value.url });
			break;
		case "3":
			utils.navigateTo("news/view",{ id: value.url });
			break;
		case "4":
			utils.navigateTo("news/list",{ id: value.url });
			break;
		case "5":
			if(value.url == "collect"){
				utils.navigateTo("ucenter/collect");
			}else if(value.url == "category"){
				utils.navigateTo("category/index");
			}else if(value.url == "sign"){
				utils.navigateTo("sign/index");
			}else if(value.url == "luckdraw"){
				utils.navigateTo("luckdraw/index");
			}else{
				utils.navigateTo(value.url+"/index");
			}
			break;
		case "6":
			utils.navigateTo("index/custom",{ id: value.url });
			break;
	}
}