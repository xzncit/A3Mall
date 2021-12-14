<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;

use app\common\exception\BaseException;
use think\facade\Request;
use app\api\service\Comments as CommentsService;

class Comments extends Base {

    /**
     * 获取商品评论
     * @return \think\response\Json
     */
    public function index(){
        try{
            return $this->returnAjax("ok",1,CommentsService::getList(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

}