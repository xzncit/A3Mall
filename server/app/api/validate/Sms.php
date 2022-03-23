<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\validate;

use app\common\validate\Validate;
use think\facade\Db;

class Sms extends Validate {

    protected $rule = [
        "username"  => "require|mobile",
        "type"      => "checkSmsType",
        "code"      => "checkCodes"
    ];

    protected $message  =   [
        "username.require"  => "请填写手机号码",
        "username.mobile"   => "您填写的手机号码不正确",
        //"code.require"      => "请填写验证码"
    ];

    protected $scene = [
        "sms"=>["username","type","code"]
    ];

    protected function checkSmsType($value, $rule, $data=[]){
        if(empty($value) || !in_array($value,["register","repassword"])){
            return "非法操作";
        }

        if($value == "repassword" && Db::name("users")->where("mobile",$data["username"])->count() <= 0){
            return "您填写的手机号码不存在！";
        }

        if($value == "register" && Db::name("users")->where("mobile",$data["username"])->count() > 0){
            return "您填写的手机号码已被使用！";
        }

        return true;
    }

    protected function checkCodes($value, $rule, $data=[]){
        if(empty($data["username"])){
            return "请填写手机号码";
        }

        return true;
    }

}