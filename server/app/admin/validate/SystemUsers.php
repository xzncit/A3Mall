<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\validate;

use app\common\validate\Validate;

class SystemUsers extends Validate {

    protected $rule = [
        "username"=>"require",
        "password"=>"require",
        "code"=>"require|checkCode",
    ];

    protected $message  =   [
        "username.require"=>"请填写用户名",
        "password.require"=>"请填写密码",
        "code.require"=>"请填写验证码",
        "code.checkCode"=>"您填写的验证码不正确",
    ];

    protected $scene = [
        "login"=>["username","password","code"]
    ];


}