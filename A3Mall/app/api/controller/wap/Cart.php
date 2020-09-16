<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wap;

use think\facade\Db;
use think\facade\Request;
use app\common\model\base\Cart as ShopCart;
use mall\basic\Users;
use mall\basic\Shopping;

class Cart extends Base {

    public function index(){
        $page = Request::param("page","1","intval");
        $size = 10;

        try {
            $condition = ["user_id"=>Users::get("id")];
            $shopCart = new ShopCart();
            $list = $shopCart->getList($condition,$size,$page);
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }

        return $this->returnAjax("ok",1, [
            "list"=>$list['data'],
            "page"=>$page,
            "total"=>$list['total'],
            "size"=>$size
        ]);
    }

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

    public function delete(){
        try {
            Shopping::delete(Request::param("id","0","strip_tags"));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),0);
        }

        return $this->returnAjax("ok",1);
    }
}