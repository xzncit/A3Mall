<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wechat;

use mall\library\wechat\chat\WeChat;
use mall\library\wechat\chat\WeConfig;
use think\facade\Db;
use think\facade\Request;

class OAuth extends Base {

    public function index(){
        $appid = WeConfig::get("wechat.appid");
        if(Request::param("state","") != $appid){
            return $this->returnAjax("ok",1,[
                "url"=>trim(Request::domain(),"/"),
                "appid"=>$appid,
                "state"=>$appid
            ]);
        }

        try{
            $token = WeChat::Oauth()->getOauthAccessToken(Request::param("code"));
        }catch (\Exception $e){
            return $this->returnAjax("获取授权信息失败，请稍后在试",0);
        }

        if (isset($token['openid'])) {
            $user = WeChat::Oauth()->getUserInfo($token['access_token'],$token['openid']);
            if(($row=Db::name("wechat_users")->where([
                    'openid' => $token['openid'],
                    'appid' => $appid
                ])->find()) != false){
                if($row["user_id"] == 0){
                    $group_id = Db::name("users_group")->order('minexp','ASC')->value("id");
                    $password = md5($appid . $user["openid"]);
                    Db::name("users")->insert([
                        "group_id"=>$group_id,
                        "username"=>'wx_'.uniqid(),
                        "nickname"=>$user["nickname"],
                        "mobile"=>"",
                        "password"=>$password,
                        "status"=>0,
                        "create_ip"=>Request::ip(),
                        "last_ip"=>Request::ip(),
                        "create_time"=>time(),
                        "last_login"=>time()
                    ]);

                    $row["user_id"] = Db::name("users")->getLastInsID();
                }

                $info = \mall\basic\Users::info($row["user_id"]);

                $salt = mt_rand(100,10000);
                $token = sha1($info["id"].$user["openid"].$info["password"].$salt.time());
                Db::name("users_token")->insert([
                    "user_id"=>$info["id"],
                    "token"=>$token,
                    "salt"=>$salt,
                    "ip"=>Request::ip(),
                    "create_time"=>time()
                ]);

                return $this->returnAjax("登录成功！",2,[
                    "token"=>$token,
                    "username"=>$info["username"],
                    "nickname"=>$info["nickname"],
                    "group_name"=>$info["group_name"],
                    "shop_count"=>$info["shop_count"],
                    "coupon_count"=>$info["coupon_count"],
                    "mobile"=>$info["mobile"],
                    "sex"=>$info["sex"],
                    "point"=>$info["point"],
                    "amount"=>$info["amount"],
                    "last_ip"=>$info["last_ip"],
                    "last_login"=>$info["last_login"]
                ]);
            }else{
                $group_id = Db::name("users_group")->order('minexp','ASC')->value("id");
                $password = md5($appid . $user["openid"]);
                Db::name("users")->insert([
                    "group_id"=>$group_id,
                    "username"=>'wx_'.uniqid(),
                    "nickname"=>$user["nickname"],
                    "mobile"=>"",
                    "password"=>$password,
                    "status"=>0,
                    "create_ip"=>Request::ip(),
                    "last_ip"=>Request::ip(),
                    "create_time"=>time(),
                    "last_login"=>time()
                ]);

                $user_id = Db::name("users")->getLastInsID();

                if (!empty($user['subscribe_time'])) {
                    $user['subscribe_create_time'] = $user['subscribe_time'];
                }

                if (isset($user['tagid_list']) && is_array($user['tagid_list'])) {
                    $user['tagid_list'] = is_array($user['tagid_list']) ? join(',', $user['tagid_list']) : '';
                }

                unset($user['privilege'], $user['groupid']);
                Db::name("wechat_users")->insert(array_merge($user,[
                    'subscribe' => '1', 'appid' => $appid,
                    'user_id'=>$user_id
                ]));

                $salt = mt_rand(100,10000);
                $token = sha1($user_id.$user["openid"].$password.$salt.time());
                Db::name("users_token")->insert([
                    "user_id"=>$user_id,
                    "token"=>$token,
                    "salt"=>$salt,
                    "ip"=>Request::ip(),
                    "create_time"=>time()
                ]);

                $info = \mall\basic\Users::info($user_id);
                return $this->returnAjax("注册成功！",2,[
                    "token"=>$token,
                    "username"=>$info["username"],
                    "nickname"=>$info["nickname"],
                    "group_name"=>$info["group_name"],
                    "shop_count"=>$info["shop_count"],
                    "coupon_count"=>$info["coupon_count"],
                    "mobile"=>$info["mobile"],
                    "sex"=>$info["sex"],
                    "point"=>$info["point"],
                    "amount"=>$info["amount"],
                    "last_ip"=>$info["last_ip"],
                    "last_login"=>$info["last_login"]
                ]);
            }
        }else{
            return $this->returnAjax("获取授权信息失败",0);
        }
    }

}