import { oAuth } from './Wechat'
import tools from '../utils/Tools'
import RequestQuery from "../utils/RequestQuery";
import Storage from "../utils/Storage";
import Cookie from "../utils/Cookie";
import router from "../router";

export function login(obj,from) {
    let users = Storage.get("users",true);
    if(tools.isWeiXin() && users == null){
        if(tools.in_array(obj.name,["Oauth"])){
            return false;
        }

        Storage.set("VUE_REFERER",obj.path);
        oAuth();
        return true;
    }else if(obj.meta.auth && users == null){
        if(tools.in_array(from.name,["Login","Register","Forget"])){
            return true;
        }

        Storage.set("VUE_REFERER",obj.path);
        // if(tools.isWeiXin()){
        //     oAuth();
        // }else{
        //     router.push('/public/login');
        // }
        router.push('/public/login');

        return true;
    }else if(users != null && users.token){
        if(tools.in_array(obj.name,["Login","Register","Forget","Oauth"])){
            router.push('/');
        }
    }

    return false;
}

export function init() {
    let type = RequestQuery.get("type");
    if(type != null && type == 'logout'){
        Storage.clear();
    }

    let spread_id = RequestQuery.get("u");
    if(spread_id != null){
        Cookie.set("spread_id",spread_id);
    }
}