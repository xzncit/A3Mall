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
use think\facade\Db;
use think\facade\Request;
use mall\basic\Users;
use mall\basic\Shopping;
use app\api\service\Cart as CartService;

class Cart extends Base {

    /**
     * 购物车
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        try{
            return $this->returnAjax("ok",1,CartService::getList(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

    /**
     * 添加商品
     * @return \think\response\Json
     */
    public function add(){
        $id = Request::param("id","","intval");
        $sku_id = Request::param("sku_id","","intval");
        $num = Request::param("num","0","intval");

        try {
            Shopping::add($id,$sku_id,$num);
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),0);
        }

        return $this->returnAjax("商品添加至购物车成功",1,[
            "count" => Db::name("cart")->where('user_id',Users::get("id"))->sum("goods_nums")
        ]);
    }

    /**
     * 修改商品数量
     * @return \think\response\Json
     */
    public function change(){
        $id = Request::param("id","","intval");
        $sku_id = Request::param("sku_id","","intval");
        $num = Request::param("num","","intval");

        try {
            Shopping::add($id,$sku_id,$num);
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),0);
        }

        return $this->returnAjax("ok",1,[
            "count" => Db::name("cart")->where('user_id',Users::get("id"))->sum("goods_nums")
        ]);
    }

    /**
     * 删除商品
     * @return \think\response\Json
     */
    public function delete(){
        try{
            return $this->returnAjax("ok",1,CartService::delete(Request::param("id","0","strip_tags")));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),0);
        }
    }
}