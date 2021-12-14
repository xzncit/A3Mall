<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;

use app\common\exception\BaseException;
use mall\basic\Users;
use mall\utils\Tool;
use think\facade\Config;
use app\common\models\Cart as CartModel;
use app\common\models\goods\Goods as GoodsModel;
use app\common\models\goods\GoodsItem as GoodsItemModel;
use app\common\models\goods\GoodsAttribute as GoodsAttributeModel;

class Cart extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $condition = ["user_id"=>Users::get("id")];
        $count = CartModel::where($condition)->count();
        $result = CartModel::where($condition)->order("id","desc")->page($page,$size)->select()->toArray();

        $array = [ "list"=>[], "page"=>$page, "total"=>0, "size"=>$size ];
        $total = ceil($count / $size);
        $array["total"] = $total;
        if($total == $page -1){
            throw new BaseException("没有数据了哦！",-1,$array);
        }

        $array["list"] = self::getGoodsList($result);
        return $array;
    }

    /**
     * 获取商品列表
     * @param $result
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getGoodsList($result){
        $data = [];
        $goodsModel = new GoodsModel();
        $goodsItemModel = new GoodsItemModel();
        $goodsAttributeModel = new GoodsAttributeModel();
        foreach($result as $key=>$value){
            $goods = $goodsModel
                ->where("id",$value["goods_id"])
                ->where("status",0)->find();

            if(empty($goods)){
                CartModel::where("id",$value["id"])->delete();
                continue;
            }

            if($goodsItemModel->where(["goods_id"=>$value["goods_id"]])->count() && $value["product_id"] <= 0){
                CartModel::where("id",$value["id"])->delete();
                continue;
            }

            $data[$key] = [
                "id"=>$value["id"],
                "title"=>$goods["title"],
                "price"=>$goods["sell_price"],
                "photo"=>Tool::thumb($goods["photo"],"medium",true),
                "nums"=>$goods["store_nums"],
                "goods_nums"=>$value["goods_nums"],
                "goods_id"=>$value["goods_id"],
                "product_id"=>$value["product_id"],
            ];

            if($value["product_id"] > 0){
                $products = $goodsItemModel->where([
                    "goods_id"=>$value["goods_id"],
                    "spec_key"=>$value["spec_key"],
                ])->find();

                if(empty($products)){
                    unset($data[$key]);
                    CartModel::where("id",$value["id"])->delete();
                    continue;
                }

                $arr = explode(",",$value["spec_key"]);
                $attr = [];
                foreach ($arr as $val){
                    $spec = explode(":",$val);
                    $attribute = $goodsAttributeModel->where([
                        "goods_id"=>$value["goods_id"],
                        "attr_id"=>$spec[0],
                        "attr_data_id"=>$spec[1]
                    ])->find();
                    $attr[] = $attribute["name"] . ":" . $attribute["value"];
                }

                $data[$key]["attr"] = implode(",",$attr);
                $data[$key]["price"] = $products["sell_price"];
                $data[$key]["nums"] = $products["store_nums"];
                $data[$key]["product_id"] = $products["id"];
            }
        }

        return $data;
    }

    /**
     * 删除购物车商品
     * @param $id
     * @return int
     * @throws BaseException
     */
    public static function delete($id){
        if(empty($id)){
            throw new BaseException("非法参数",0);
        }

        if(!is_array($id)){
            $id = array_map("intval",explode(",",$id));
        }

        CartModel::where("id","in",$id)->where("user_id",Users::get("id"))->delete();
        return CartModel::where("user_id",Users::get("id"))->count();
    }

}