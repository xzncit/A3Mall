<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\library\oauth\wechat;

use mall\basic\Token;
use mall\basic\Users;
use mall\utils\HttpClient;
use think\facade\Db;
use think\facade\Request;

class WeChat {

    /**
     * 用户允许授权后，将会重定向到redirect_uri的网址上，并且带上code和state参数
     * redirect_uri?code=CODE&state=STATE
     * 若用户禁止授权，则重定向后不会带上code参数，仅会带上state参数
     * redirect_uri?state=STATE
     * @param $appid
     * @param $redirect_uri
     * @param string $scope
     * @param string $state
     * @return string
     */
    public static function qrconnect($appid,$redirect_uri,$state="STATE",$scope="snsapi_login"){
        $url = "https://open.weixin.qq.com/connect/qrconnect?appid={$appid}&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope={$scope}&state={$state}#wechat_redirect";
        return $url;
    }

    /**
     * 通过code获取access_token
     * @param $appid
     * @param $secret
     * @param $code
     * @param string $grant_type
     * @return array
     * 正确返回 {
     * "access_token":"ACCESS_TOKEN",
     * "expires_in":7200,
     * "refresh_token":"REFRESH_TOKEN","openid":"OPENID",
     * "scope":"SCOPE"
     * }
     * 错误返回样例：
     * {
     * "errcode":40029,"errmsg":"invalid code"
     * }
     */
    public static function access_token($appid,$secret,$code,$grant_type="authorization_code"){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type={$grant_type}";
        $result = HttpClient::get($url);
        return json_decode($result,true);
    }

    /**
     * @param $access_token
     * @param $openid
     * @return array
     * openid	普通用户的标识，对当前开发者帐号唯一
     * nickname	普通用户昵称
     * sex	普通用户性别，1为男性，2为女性
     * province	普通用户个人资料填写的省份
     * city	普通用户个人资料填写的城市
     * country	国家，如中国为CN
     * headimgurl	用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空
     * privilege	用户特权信息，json数组，如微信沃卡用户为（chinaunicom）
     * unionid	用户统一标识。针对一个微信开放平台帐号下的应用，同一用户的unionid是唯一的。
     */
    public static function userinfo($access_token,$openid){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}";
        $result = HttpClient::get($url);
        return json_decode($result,true);
    }

    public static function auth($access_token,$openid){
        if(empty($access_token) || empty($openid)){
            throw new \Exception("获取授权失败，请稍后重试",0);
        }

        $user = self::userinfo($access_token,$openid);
        if(isset($user["errcode"]) && !empty($user["errmsg"])){
            throw new \Exception($user["errcode"] . " : " . $user["errmsg"],0);
        }

        $condition = [];
        if(isset($user["unionid"])){
            $condition["unionid"] = $user["unionid"];
        }else{
            $condition["weixin_openid"] = $user['openid'];
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