<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\validate;

class Validate extends \think\Validate {

    /**
     * 验证验证码
     * @param $value
     * @param null $rule
     * @param array $data
     * @return bool
     */
    protected function checkCode($value, $rule=null, $data=[]){
        return captcha_check($value);
    }

}