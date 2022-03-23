<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;

use app\common\models\users\Users as UsersModel;
use mall\library\tools\jwt\Token;
use think\facade\Db;
use think\facade\Request;
use mall\basic\Users as UsersUtils;
use app\common\models\users\UsersGroup as UsersGroupModel;
use app\common\models\Setting as SettingModel;
use app\common\models\users\UsersSms as UsersSmsModel;

class Users extends Service {

    /**
     * 会员登录
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function login($data){
        $username = $data["username"]??"";
        $password = $data["password"]??"";

        $users = UsersModel::where([ "mobile"=>$username ])->find();
        if(empty($users)){
            throw new \Exception("用户不存在！",0);
        }

        if($users["status"] == 3){
            throw new \Exception("您的用户已被管理员禁止登录！",0);
        }

        if($users["password"] != md5($password)){
            throw new \Exception("您填写的密码不正确！",0);
        }

        if(in_array($users["status"],[1,2])){
            $status = UsersModel::statusInfo($users["status"]);
            throw new \Exception("您当前用户状态：" . $status . "，如有疑问请联系管理员。",0);
        }

        $data = [ "last_ip"=>Request::ip(), "last_login"=>time() ];

        UsersModel::where("id",$users["id"])->update($data);
        $token = Token::get("id",$users["id"]);
        $info = UsersUtils::info($users["id"]);

        return [
            "id"            => $users["id"],
            "token"         => $token,
            "username"      => $info["username"],
            "nickname"      => $info["nickname"],
            "group_name"    => $info["group_name"],
            "shop_count"    => $info["shop_count"],
            "coupon_count"  => $info["coupon_count"],
            "mobile"        => $info["mobile"],
            "sex"           => $info["sex"],
            "point"         => $info["point"],
            "amount"        => $info["amount"],
            "last_ip"       => $info["last_ip"],
            "last_login"    => $info["last_login"]
        ];
    }

    /**
     * 注册
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function register($data){
        $username = strip_tags($data["username"]??"");
        $password = strip_tags($data["password"]??"");

        $group_id = UsersGroupModel::order('minexp','ASC')->value("id");
        $data = [
            "group_id"=>$group_id,
            "username"=>$username,
            "mobile"=>$username,
            "password"=>md5($password),
            "status"=>0,
            "create_ip"=>Request::ip(),
            "last_ip"=>Request::ip(),
            "create_time"=>time(),
            "last_login"=>time()
        ];

        $user_id = UsersModel::create($data)->id;
        $token = Token::get("id",$user_id);
        Db::name("users_sms")->where("mobile",$username)->delete();
        $info = UsersUtils::info($user_id);

        return [
            "id"            => $user_id,
            "token"         => $token,
            "username"      => $info["username"],
            "nickname"      => $info["nickname"],
            "group_name"    => $info["group_name"],
            "shop_count"    => $info["shop_count"],
            "coupon_count"  => $info["coupon_count"],
            "mobile"        => $info["mobile"],
            "sex"           => $info["sex"],
            "point"         => $info["point"],
            "amount"        => $info["amount"],
            "last_ip"       => $info["last_ip"],
            "last_login"    => $info["last_login"]
        ];
    }

    /**
     * 修改密码
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function forget($data){
        $username = strip_tags($data["username"]??"");
        $password = strip_tags($data["password"]??"");

        if(!$users = Db::name("users")->where("mobile",$username)->find()){
            throw new \Exception("您填写的手机号码不存在！",0);
        }

        Db::name("users")->where("mobile",$username)->save([ "password"=>md5($password), "last_ip"=>Request::ip(), "last_login"=>time() ]);
        $user_id = $users["id"];
        $token = Token::get("id",$users["id"]);
        Db::name("users_sms")->where("mobile",$username)->delete();
        $info = UsersUtils::info($user_id);

        return [
            "id"            => $info["id"],
            "token"         => $token,
            "username"      => $info["username"],
            "nickname"      => $info["nickname"],
            "group_name"    => $info["group_name"],
            "shop_count"    => $info["shop_count"],
            "coupon_count"  => $info["coupon_count"],
            "mobile"        => $info["mobile"],
            "sex"           => $info["sex"],
            "point"         => $info["point"],
            "amount"        => $info["amount"],
            "last_ip"       => $info["last_ip"],
            "last_login"    => $info["last_login"]
        ];
    }

    public static function autologin(){
        $token = Token::check();
        $result  = Token::parse($token,"id");
        if(!is_array($result)){
            throw new \Exception("您还未登录，请先登录",401);
        }

        $info = UsersUtils::info($result["value"]);
        return [
            "id"            => $info["id"],
            "token"         => $token,
            "username"      => $info["username"],
            "nickname"      => $info["nickname"],
            "group_name"    => $info["group_name"],
            "shop_count"    => $info["shop_count"],
            "coupon_count"  => $info["coupon_count"],
            "mobile"        => $info["mobile"],
            "sex"           => $info["sex"],
            "point"         => $info["point"],
            "amount"        => $info["amount"],
            "last_ip"       => $info["last_ip"],
            "last_login"    => $info["last_login"]
        ];
    }

    /**
     * 发送短信
     * @param $data
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function sendSMS($data){
        $config = SettingModel::getArrayData("sms");
        $sms = UsersSmsModel::where("mobile",$data["username"])->order("id","DESC")->find();
        if(!empty($sms) && (strtotime($sms["create_time"]) + (60 * $config["duration_time"])) > time()){
            throw new \Exception("您的验证码已发送，请注意查收",1);
        }

        sendSMS(["mobile"=>$data["username"]],$data["type"]);
        return "发送成功，验证码".$config["duration_time"]."分钟内有效";
    }

}