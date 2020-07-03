import request from "../utils/Request";

let globalConf;

export function config() {
    return new Promise((resolve, reject)=>{
        if(globalConf){
            return resolve(globalConf);
        }

        
    });
}

export function oAuth() {
    request.post("/oauth").then(res=>{
        if(res.status == 1){
            location.href = `https://open.weixin.qq.com/connect/oauth2/authorize?appid=${res.data.appid}&redirect_uri=${res.data.url}/public/oauth&response_type=code&scope=snsapi_userinfo&state=${res.data.state}#wechat_redirect`;
        }
    });
}
