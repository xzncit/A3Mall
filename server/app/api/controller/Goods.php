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
use app\api\service\Goods as GoodsService;

class Goods extends Base {

    /**
     * 商品列表
     * @return \think\response\Json
     */
    public function index(){
        try{
            return $this->returnAjax("ok",1,GoodsService::getList(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

    /**
     * 商品详情
     * @return \think\response\Json
     */
    public function view(){
        try {
            return $this->returnAjax("ok",1,GoodsService::detail(Request::param("id","0","intval")));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 收藏商品
     * @return \think\response\Json
     */
    public function favorite(){
        try{
            $res = GoodsService::favorite(Request::param("id","","intval"));
            return $this->returnAjax($res==1?"收藏成功":"取消成功",1,$res);
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

}