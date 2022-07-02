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

class Login extends Validate {

    protected $rule = [
        "username"  => "require",
        "password"  => "require"
    ];

    protected $message  =   [
        "username.require"  => "请填写帐号",
        //"username.mobile"   => "您填写的手机号码不正确",
        "password.require"  => "请填写密码"
    ];

    protected $scene = [
        "login"=>["username","password"]
    ];

}