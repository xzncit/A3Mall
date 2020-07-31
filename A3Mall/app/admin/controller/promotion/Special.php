<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\promotion;

use app\admin\controller\Auth;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;


class Special extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            if(!empty($key["title"])){
                $condition["name"] = ["like",'%'.$key["title"].'%'];
            }

            $count = Db::name("promotion_price")
                ->where($condition)->count();

            $data = Db::name("promotion_price")
                ->where($condition)->order('id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            foreach($list as $key=>$item){
                $goods = Db::name("goods")->where(["id"=>$item["goods_id"]])->find();
                $list[$key]["title"] = $goods["title"];
                $list[$key]["sell_price"] = $goods["sell_price"];
                $list[$key]["thumb_image"] = $goods["photo"];

                $spec = "";
                if($item["product_id"]){
                    $product = Db::name("goods_item")->where(["id"=>$item["product_id"]])->find();
                    $arr = explode(",",$product["spec_key"]);
                    $temp = [];
                    foreach($arr as $value){
                        $param = explode(":",$value);
                        if(count($param) < 2){
                            continue;
                        }
                        $name = Db::name("products_attribute")->where(["id"=>$param[0]])->value("name");
                        $value = Db::name("products_attribute_data")->where(["id"=>$param[1],"pid"=>$param[0]])->value("value");
                        $temp[] = $name . ':' . $value;
                    }
                    $spec = implode(",", $temp);
                }
                $list[$key]["spec"] = $spec;
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $row = empty($id) ? [] : Db::name("promotion_price")->where("id",$id)->find();

            if(!empty($row)){
                $price = Db::name("promotion_price_item")->where(["pid"=>$id])->select()->toArray();
                $price_temp = [];
                foreach($price as $val){
                    $price_temp[$val["group_id"]] = $val["price"];
                }

                $row["item"] = $price_temp;
                $row["goods"] = Db::name("goods")->where(["id"=>$row["goods_id"]])->find();

                if(!empty($row["product_id"])){
                    $product_id = explode(",",$row["product_id"]);
                    $products = Db::name("goods_item")->where(["goods_id"=>$row["goods_id"]])->select()->toArray();

                    $temp = [];
                    foreach($products as $key=>$item){
                        $temp[$key] = $item;
                        $temp[$key]["checked"] = in_array($item["id"],$product_id) ? true : false;
                        $arr = explode(",",$item["spec_key"]);
                        foreach($arr as $value){
                            $param = explode(":",$value);
                            $name = Db::name("products_attribute")->where(["id"=>$param[0]])->value("name");
                            $value = Db::name("products_attribute_data")->where(["id"=>$param[1],"pid"=>$param[0]])->value("value");
                            $temp[$key]['spec_item'][] = $name . ':' . $value;
                        }
                        if(!empty($temp[$key]['spec_item'])){
                            $temp[$key]['spec_item'] = implode(",", $temp[$key]['spec_item']);
                        }
                    }

                    $row["products"] = $temp;
                }

            }

            return View::fetch("",[
                "group"=>Db::name("users_group")->select()->toArray(),
                "data"=>$row
            ]);
        }

        $data = Request::post();
        $data["product_id"] = !empty($data["product_id"]) ? $data["product_id"] : 0;
        $data["create_time"] = time();

        $row = Db::name("promotion_price")->where([
            "goods_id"=>$data["goods_id"],"product_id"=>$data["product_id"]
        ])->find();

        if(!empty($row)){
            if($row["id"] != $data["id"]){
                return Response::returnArray("该商品己添加过特价促销",0);
            }
        }

        if(empty($data["id"])){
            if(!Db::name("promotion_price")->strict(false)->insert([
                    "goods_id"=>$data["goods_id"],
                    "product_id"=>$data["product_id"],
                    "create_time"=>time()
                ])){
                return Response::returnArray("操作失败，请重试。",0);
            }
            $data["id"] = Db::name("promotion_price")->getLastInsID();
        }else{
            Db::name("promotion_price")->strict(false)->where(["id"=>$data["id"]])->update([
                "goods_id"=>$data["goods_id"],
                "product_id"=>$data["product_id"]
            ]);
        }

        Db::name("promotion_price_item")->where(["pid"=>$data["id"]])->delete();
        foreach($data["price"] as $key=>$val){
            $val = trim($val);
            if(!empty($val)){
                Db::name("promotion_price_item")->insert([
                    "pid"=>$data["id"],
                    "group_id"=>$key,
                    "price"=>$val
                ]);
            }
        }

        return Response::returnArray("操作成功！");
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("promotion_price")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("promotion_price")->delete($id);
            Db::name("promotion_price_item")->where(["pid"=>$id])->delete();
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

    public function field(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        $name = strip_tags(trim(Request::get("name")));
        $value = (int)Request::get("value");

        try {
            Db::name("bonus")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}
