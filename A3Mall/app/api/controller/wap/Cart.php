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

class Cart extends Auth {

    public function index(){
        $page = Request::param("page","1","intval");
        $size = 10;
        $condition = ["user_id"=>Users::get("id")];

        try {
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
        $num = Request::param("num","","intval");

        if(empty($num)){
            $num = 1;
        }

        $goods = Db::name("goods")->where("id",$id)->find();
        if(empty($goods)){
            return $this->returnAjax("该商品己下架！",0);
        }

        if($goods["status"] != 0){
            return $this->returnAjax("该商品己下架。",0);
        }

        $products = null;
        if(Db::name("goods_item")->where("goods_id",$id)->count()){
            $products = Db::name("goods_item")->where([
                "id"=>$sku_id,
                "goods_id"=>$id
            ])->find();

            if(empty($products)){
                return $this->returnAjax("该商品己下架。",0);
            }
        }

        if(!empty($products)){
            if($products["store_nums"] < $num){
                return $this->returnAjax("您选择的商品库存不足",0);
            }
        }else{
            if($goods["store_nums"] < $num){
                return $this->returnAjax("您选择的商品库存不足",0);
            }
        }

        $cart = [
            "session_id"=>session_id(),
            "user_id"=>Users::get("id"),
            "goods_id"=>$id,
            "product_id"=>!empty($products["id"]) ? $products["id"] : 0,
            "spec_key"=>!empty($products["spec_key"]) ? $products["spec_key"] : "",
            "sell_price"=>!empty($products["sell_price"]) ? $products["sell_price"] : $goods["sell_price"],
            "cost_price"=>!empty($products["cost_price"]) ? $products["cost_price"] : $goods["cost_price"],
            "market_price"=>!empty($products["market_price"]) ? $products["market_price"] : $goods["market_price"],
            "goods_weight"=>!empty($products["goods_weight"]) ? $products["goods_weight"] : $goods["goods_weight"],
            "goods_nums"=>$num,
            "create_time"=>time()
        ];

        $map = [
            "goods_id"=>$id,
            "user_id"=>Users::get("id")
        ];
        if(!empty($products["spec_key"])){
            $map["spec_key"] = $products["spec_key"];
        }

        if(Db::name("cart")->where($map)->count()){
            Db::name("cart")->where($map)->inc("goods_nums",$num)->update();
            Db::name("cart")->where($map)->update(["update_time"=>time()]);
        }else{
            Db::name("cart")->insert($cart);
            Db::name("cart")->getLastInsID();
        }

        return $this->returnAjax("商品添加至购物车成功",1,[
            "count" => Db::name("cart")->where('user_id',Users::get("id"))->sum("goods_nums")
        ]);
    }

    public function change(){
        $id = Request::param("id","","intval");
        $sku_id = Request::param("sku_id","","intval");
        $num = Request::param("num","","intval");

        if(empty($num)){
            $num = 1;
        }

        $goods = Db::name("goods")->where("id",$id)->find();
        if(empty($goods)){
            return $this->returnAjax("该商品己下架！",0);
        }

        if($goods["status"] != 0){
            return $this->returnAjax("该商品己下架。",0);
        }

        $products = null;
        if(Db::name("goods_item")->where("goods_id",$id)->count()){
            $products = Db::name("goods_item")->where([
                "id"=>$sku_id,
                "goods_id"=>$id
            ])->find();

            if(empty($products)){
                return $this->returnAjax("该商品己下架。",0);
            }
        }

        if(!empty($products)){
            if($products["store_nums"] < $num){
                return $this->returnAjax("您选择的商品库存不足",0);
            }
        }else{
            if($goods["store_nums"] < $num){
                return $this->returnAjax("您选择的商品库存不足",0);
            }
        }

        $cart = [
            "session_id"=>session_id(),
            "user_id"=>Users::get("id"),
            "goods_id"=>$id,
            "product_id"=>!empty($products["id"]) ? $products["id"] : 0,
            "spec_key"=>!empty($products["spec_key"]) ? $products["spec_key"] : "",
            "sell_price"=>!empty($products["sell_price"]) ? $products["sell_price"] : $goods["sell_price"],
            "cost_price"=>!empty($products["cost_price"]) ? $products["cost_price"] : $goods["cost_price"],
            "market_price"=>!empty($products["market_price"]) ? $products["market_price"] : $goods["market_price"],
            "goods_weight"=>!empty($products["goods_weight"]) ? $products["goods_weight"] : $goods["goods_weight"],
            "goods_nums"=>$num,
            "create_time"=>time()
        ];

        $map = [
            "goods_id"=>$id,
            "user_id"=>Users::get("id")
        ];
        if(!empty($products["spec_key"])){
            $map["spec_key"] = $products["spec_key"];
        }

        if(Db::name("cart")->where($map)->count()){
            Db::name("cart")->where($map)->update([
                "goods_nums"=>$num,
                "update_time"=>time()
            ]);
        }else{
            Db::name("cart")->insert($cart);
            Db::name("cart")->getLastInsID();
        }

        return $this->returnAjax("ok",1,[
            "count" => Db::name("cart")->where('user_id',Users::get("id"))->sum("goods_nums")
        ]);
    }

    public function delete(){
        $id = Request::param("id","","trim,strip_tags");
        if(empty($id)){
            return $this->returnAjax("非法操作！",0);
        }

        $id = array_map("intval",explode(",",$id));

        if(Db::name("cart")->where("id","in",$id)->delete()){
            return $this->returnAjax("ok",1);
        }

        return $this->returnAjax("delete failed",0);
    }
}