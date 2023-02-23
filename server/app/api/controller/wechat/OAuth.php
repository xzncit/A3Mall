<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wechat;

use think\facade\Request;
use app\api\controller\Base;
use app\api\service\wechat\OAuth as OAuthService;
use app\common\library\oauth\QQ;
use app\common\library\oauth\WeChat;

class OAuth extends Base {

    /**
     * 登录
     * @return \think\response\Json
     */
    public function login(){
        try {
            return $this->returnAjax("ok",1,OAuthService::login(Request::param()));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

}