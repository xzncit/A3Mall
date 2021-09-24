<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\library\oauth\qq;

use mall\basic\Token;
use mall\basic\Users;
use mall\utils\HttpClient;
use think\facade\Db;
use think\facade\Request;

class QQ {

    /**
     * @param $oauth_consumer_key
     * @param $access_token
     * @param $openid
     * @return array
         "ret": 0,
         "msg": "",
         "is_lost": 0,
         "nickname": "、",
         "gender": "男",
         "gender_type": 1,
         "province": "广东",
         "city": "深圳",
         "year": "2017",
         "constellation": "",
         "figureurl": "",
         "figureurl_1": "",
         "figureurl_2": "",
         "figureurl_qq_1": "",
         "figureurl_qq_2": "",
         "figureurl_qq": "",
         "figureurl_type": "1",
         "is_yellow_vip": "0",
         "vip": "0",
         "yellow_vip_level": "0",
         "level": "0",
         "is_yellow_year_vip": "0",
         "headimgurl": "",
         "unionid": "",
         "openId": "",
         "nickName": "",
         "avatarUrl": ""
     */
    public static function getUserInfo($oauth_consumer_key,$access_token,$openid){
        $url = "https://graph.qq.com/user/get_user_info?access_token={$access_token}&oauth_consumer_key={$oauth_consumer_key}&openid={$openid}";
        $result = HttpClient::get($url);
        return json_decode($result,true);
    }

    public static function auth($access_token,$openid){
        if(empty($access_token) || empty($openid)){
            throw new \Exception("获取授权失败，请稍后重试",0);
        }

        $oauth = Db::name("oauth")->where("code","qq")->find();
        $config = json_decode($oauth["config"],true);
        if(empty($config["appid"])){
            throw new \Exception("获取授权信息失败",0);
        }

        $user = self::getUserInfo($config["appid"],$access_token,$openid);
        // $user["openid"] = strtolower($user["openId"]);

        $condition = [];
        if(isset($user["unionid"])){
            $condition["unionid"] = $user["unionid"];
        }else{
            $condition["open_qq"] = $user['openid'];
        }

        // 用户己存在，登录操作
        if(($row=Db::name("wechat_users")->where($condition)->find()) != false){
            if($row["user_id"] == 0){
                $group_id = Db::name("users_group")->order('minexp','ASC')->value("id");
                $password = md5($user['openid']);
                $data = [
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
                ];

                Db::name("users")->insert($data);

                $row["user_id"] = Db::name("users")->getLastInsID();
                Db::name("wechat_users")->where([
                    'open_qq' => $user['openid']
                ])->update(["user_id"=>$row["user_id"]]);
            }

            $info = Users::info($row["user_id"]);
            $token = Token::set($info["id"]);

            return [
                "id"=>$info["id"],
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
            ];
        }

        // 注册
        $group_id = Db::name("users_group")->order('minexp','ASC')->value("id");
        $password = md5($user['openid']);

        $data = [
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
        ];

        Db::name("users")->insert($data);
        $user_id = Db::name("users")->getLastInsID();

        $qq = [
            "user_id"=>$user_id,
            "open_qq"=>$user['openid'],
            "nickname"=>$user["nickname"],
            "sex"=>$user["gender"] == "男" ? 1 : 2,
            "province"=>$user["province"],
            "city"=>$user["city"],
            "headimgurl"=>$user["figureurl_qq"],
            "create_time"=>time()
        ];

        Db::name("wechat_users")->insert($qq);
        $token = Token::set($user_id);
        $info = Users::info($user_id);

        return [
            "id"=>$info["id"],
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
        ];
    }

}