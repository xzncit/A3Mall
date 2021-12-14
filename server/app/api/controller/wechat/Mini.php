<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller\wechat;

use app\api\controller\Base;
use app\api\service\wechat\Mini as MiniService;

class Mini extends Base {

    /**
     * 消息模板
     * @return \think\response\Json
     */
    public function template(){
        try{
            return $this->returnAjax("ok",1,MiniService::getTemplate());
        }catch (\Exception $ex){
            return $this->returnAjax("ok",1);
        }
    }

}