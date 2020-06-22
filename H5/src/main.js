import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import tools from './utils/Tools'
import request from './utils/Request';
import Storage from "./utils/Storage";
import RequestQuery from "./utils/RequestQuery";
import wx from "weixin-js-sdk";
import './assets/css/font-awesome.min.css';
//import './assets/css/style.css'

Vue.config.devtools = process.env.NODE_ENV === 'development'
Vue.config.productionTip = false
Vue.prototype.$tools = tools;
Vue.prototype.$request = request;
Vue.prototype.$storage = Storage;
Vue.prototype.$wx = wx;

let type = RequestQuery.get("type");
if(type != null && type == 'logout'){
  Storage.clear();
}

router.beforeEach(function(to,from,next){
  let users = Storage.get("users",true);
  if(to.meta.auth && users == null){
    if(tools.in_array(from.name,["Login","Register","Forget"])){
      return ;
    }

    Storage.set("VUE_REFERER",to.path);
    if(tools.isWeiXin()){
      request.post("/oauth").then(res=>{
        if(res.status == 1){
          location.href = `https://open.weixin.qq.com/connect/oauth2/authorize?appid=${res.data.appid}&redirect_uri=${res.data.url}/public/oauth&response_type=code&scope=snsapi_userinfo&state=${res.data.state}#wechat_redirect`;
        }
      });
    }else{
      router.push('/public/login');
    }
    return ;
  }else if(users != null && users.token){
    if(tools.in_array(to.name,["Login","Register","Forget","Oauth"])){
      return router.push('/');
    }
  }

  document.title = to.meta.title || process.env.WEB_NAME || "A3Mall B2C商城";
  store.commit("UPDATETABBAR",to.meta.tabbar);
  next();
});

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app')
