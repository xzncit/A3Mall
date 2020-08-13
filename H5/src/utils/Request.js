import axios from 'axios';
import Storage from "./Storage";
import tools from "./Tools";
import {oAuth} from "../libs/Wechat";
import router from "../router";

export default {

    domain(){
        return process.env.VUE_APP_WEB_API_URL;
    },

    create(options={}){
        // create http service
        let config = {
            baseURL: this.domain() + "api", // url前缀
            timeout: 3000 // 请求超时时间
        };

        let arr = Object.getOwnPropertyNames(options);
        if(arr.length > 0 ){
            Object.assign(config,options);
        }

        const httpService = axios.create(config);

        // 拦截器
        httpService.interceptors.request.use(function (config) {
            // 在发送请求之前做些什么
            let users = Storage.get("users",true);
            if(users != null){
                config.headers["Auth-Token"] = users.token;
            }
            return config;
        }, function (error) {
            return Promise.reject(error);
        });

        // 添加响应拦截器
        httpService.interceptors.response.use(function (response) {
            return response;
        }, function (error) {
            return Promise.reject(error);
        });

        return httpService;
    },

    get(url="",params={}){
        let httpService = this.create();
        return new Promise(function (resolve, reject) {
            httpService.get(url,{
                params: params
            }).then(function (response) {
                if(response.data.status == '-1001'){
                    Storage.clear();
                    if(tools.isWeiXin()){
                        oAuth();
                    }else{
                        router.push('/public/login');
                    }
                }else{
                    resolve(response.data);
                }
            }).catch(function (error) {
                //console.log(error)
                reject(error);
            });
        });
    },

    post(url="",params={}){
        let httpService = this.create();
        return new Promise(function (resolve, reject) {
            httpService.post(url,params).then(function (response) {
                if(response.data.status == '-1001'){
                    Storage.clear();
                    if(tools.isWeiXin()){
                        oAuth();
                    }else{
                        router.push('/public/login');
                    }
                }else{
                    resolve(response.data);
                }
            }).catch(function (error) {
                reject(error);
            });
        });
    },

    /**
     axios.get(url[, config])
     axios.delete(url[, config])
     axios.head(url[, config])
     axios.post(url[, data[, config]])
     axios.put(url[, data[, config]])
     axios.patch(url[, data[, config]])
     */
    instance(){
        return this.create();
    },

    uploadfiy: function(form,url){
      let http = this.instance();
      return new Promise(function (resolve, reject) {
          http.post(url,form).then(function (response) {
              if(response.data.status == '-1001'){
                  Storage.clear();
                  if(tools.isWeiXin()){
                      oAuth();
                  }else{
                      router.push('/public/login');
                  }
              }else{
                  resolve(response.data);
              }
          }).catch(function (error) {
              reject(error);
          });
      });
    }
}