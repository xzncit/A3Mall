<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wechat;

use mall\library\oauth\qq\QQ;
use mall\library\wechat\chat\WeChat;
use mall\library\wechat\chat\WeConfig;
use mall\library\wechat\mini\WeMini;
use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use mall\basic\Token;
use mall\basic\Users;

class OAuth extends Base {

    /**
     * 公众号登录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        $appid = WeConfig::get("wechat.appid");
        if(Request::param("state","") != $appid){
            $domain = trim(Request::domain(),"/") . (in_array(Config::get("base.app_type"),["pc","page"]) ? "/wap" : "");
            return $this->returnAjax("ok",1,[
                "url"=>$domain,
                "appid"=>$appid,
                "state"=>$appid
            ]);
        }

        try{
            $token = WeChat::Oauth()->getOauthAccessToken(Request::param("code"));
        }catch (\Exception $e){
            //return $this->returnAjax("获取授权信息失败，请稍后在试",0);
            return $this->returnAjax($e->getMessage(),0);
        }

        if (isset($token['openid'])) {
            $user = WeChat::Oauth()->getUserInfo($token['access_token'],$token['openid']);

            $condition = [];
            if(isset($user["unionid"])){
                $condition["unionid"] = $user["unionid"];
            }else{
                $condition["openid"] = $user['openid'];
            }

            if(($row=Db::name("wechat_users")->where($condition)->find()) != false){
                if($row["user_id"] == 0){
                    $group_id = Db::name("users_group")->order('minexp','ASC')->value("id");
                    $password = md5($appid . $user["openid"]);
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
                        'openid' => $user['openid']
                    ])->update(["user_id"=>$row["user_id"]]);
                }else{
                    $users = Db::name("users")->where('id',$row["user_id"])->find();
                    Db::name("wechat_users")->where(["user_id"=>$users["id"]])->where('openid=""')->update(['openid' => $user['openid']]);
                }

                $info = Users::info($row["user_id"]);
                $token = Token::set($info["id"]);

                return $this->returnAjax("登录成功！",2,[
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
                ]);
            }else{
                $group_id = Db::name("users_group")->order('minexp','ASC')->value("id");
                $password = md5($appid . $user["openid"]);

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

                $token = Token::set($user_id);
                $info = Users::info($user_id);
                return $this->returnAjax("注册成功！",2,[
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
                ]);
            }
        }else{
            return $this->returnAjax("获取授权信息失败",0);
        }
    }

    public function login(){
        try{
            $user_id = Token::check();
            $info = Users::info($user_id);
            return $this->returnAjax("登录成功！",1000,[
                "id"=>$info["id"],
                "token"=>Request::header("Auth-Token"),
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
        }catch(\Exception $ex){
            return json(["info"=>$ex->getMessage(),"status"=>"-1002"]);
        }
    }

}