<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\validate;

use app\common\models\Setting;
use app\common\validate\Validate;
use think\facade\Db;

class Register extends Validate {

    protected $rule = [
        "username"  => "require|mobile|checkMobiles",
        "password"  => "require|checkPasswords",
        "code"      => "require|checkCodes"
    ];

    protected $message  =   [
        "username.require"  => "请填写手机号码",
        "username.mobile"   => "您填写的手机号码不正确",
        "password.require"  => "请填写密码",
        "code.require"      => "请填写验证码"
    ];

    protected $scene = [
        "register"=>["username","password","code"]
    ];

    protected function checkMobiles($value, $rule, $data=[]){
        if(Db::name("users")->where("mobile",$value)->count()){
            return "您填写的手机号码已被注册！";
        }

        return true;
    }

    protected function checkPasswords($value, $rule, $data=[]){
        if(preg_match("/(\s)/i",$value)){
            return "密码不能包含空格字符！";
        }

        $len = mb_strlen($value,"UTF8");
        if($len < 6 || $len > 18){
            return "密码长度请控制在6-18字符！";
        }

        return true;
    }

    protected function checkCodes($value, $rule, $data=[]){
        if(empty($data["username"])){
            return "请填写手机号码";
        }

        if(!$sms = Db::name("users_sms")->where("mobile",$data["username"])->where("code",$value)->order("id","DESC")->find()){
            return "您填写的验证码错误";
        }

        $config = Setting::getArrayData("sms");
        if(($sms["create_time"] + (60 * $config["duration_time"])) < time()){
            return "您的验证码已过期，请重新发送。";
        }

        return true;
    }
}