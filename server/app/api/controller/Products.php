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
use app\api\service\Products as ProductsService;

class Products extends Base {

    /**
     * 热门商品
     * @return \think\response\Json
     */
    public function hot(){
        try{
            return $this->returnAjax("ok",1,ProductsService::getList(Request::param(),[
                ["g.status","=",0],
                ["e.attribute","=","hot"]
            ]));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

    /**
     * 推荐商品
     * @return \think\response\Json
     */
    public function recommend(){
        try{
            return $this->returnAjax("ok",1,ProductsService::getList(Request::param(),[
                ["g.status","=",0],
                ["e.attribute","=","recommend"]
            ]));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }
}