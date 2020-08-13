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
use mall\utils\Tool;
use mall\utils\BC;

class Goods extends Auth {

    public function index(){
        $page = Request::param("page","1","intval");
        $id = Request::param("id","","intval");
        $type = Request::param("type","0","intval");
        $sort = Request::param("sort","1","intval");

        switch($type){
            case '2':
                $order = 'sell_price';
                $text = $sort == 1 ? "ASC" : "DESC";
                break;
            case '1':
                $order = 'sale';
                $text = 'DESC';
                break;
            case '0':
            default :
                $order = 'id';
                $text = 'DESC';
                break;
        }

        $size = 10;
        $count = Db::name("goods")
            ->where('status',0)->where("cat_id",$id)
            ->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[
                "list"=>[],
                "page"=>$page,
                "total"=>$total,
                "size"=>$size
            ]);
        }

        $result = Db::name("goods")
            ->field("id,title,photo,sell_price as price,sale")
            ->where('status',0)->where("cat_id",$id)
            ->order($order,$text)->limit((($page - 1) * $size),$size)->select()->toArray();

        $data = array_map(function ($rs){
            $rs["photo"] = Tool::thumb($rs["photo"],"medium",true);
            return $rs;
        },$result);

        return $this->returnAjax("ok",1, [
            "list"=>$data,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

    public function view(){
        $id = Request::param("id","0","intval");
        if(($goods = Db::name("goods")->where("id",$id)->where("status",0)->find()) == false){
            return $this->returnAjax("商品不存在",0);
        }

        $data = [];
        $data["collect"] = false;
        if(!empty($this->users)){
            $data["collect"] = Db::name("users_favorite")->where([
                "user_id"=>$this->users["id"],
                "goods_id"=>$id
            ])->count() ? true : false;
        }

        $data["photo"] = array_map(function ($result){
            return Tool::thumb($result["photo"],"",true);
        }, Db::name("attachments")->field("path as photo")->where([
            "pid"=>$id,
            "module"=>"goods",
            "method"=>"photo"
        ])->select()->toArray());

        // 商品规格
        $attr = Db::name("goods_attribute")->where("goods_id",$id)->select()->toArray();

        $goods_attr = [];
        foreach ($attr as $key=>$val){
            if(!in_array($val["attr_id"],array_keys($goods_attr))){
                $goods_attr[$val["attr_id"]] = [
                    "id"=>$val["attr_id"],
                    "name"=>$val["name"],
                    "list"=>[]
                ];
            }

            $flag = empty($goods_attr[$val["attr_id"]]['list']) ? true : false;
            $goods_attr[$val["attr_id"]]["list"][$val["attr_data_id"]] = [
                "id"=>$val["attr_data_id"],
                "pid"=>$val["attr_id"],
                "value"=>$val["value"],
                "selected"=>false,
                "disable"=>false
            ];
        }

        $goods_attr = array_values($goods_attr);
        foreach($goods_attr as $key=>$val){
            $goods_attr[$key]['list'] = array_values($val["list"]);

        }
        $data["attr"] = $goods_attr;

        $item = Db::name("goods_item")->where("goods_id",$id)->select()->toArray();
        $goods_item = [];
        foreach($item as $key=>$val){
            $sku_id = str_replace([",",":"],["_","_"], $val["spec_key"]);
            $goods_item[$sku_id]["key"] = $val["spec_key"];
            $goods_item[$sku_id]["sell_price"] = $val["sell_price"];
            $goods_item[$sku_id]["goods_weight"] = $val["goods_weight"];
            $goods_item[$sku_id]["store_nums"] = $val["store_nums"];
            $goods_item[$sku_id]["goods_no"] = $val["goods_number"];
            $goods_item[$sku_id]["product_id"] = $val["id"];
        }
        $data['item'] = $goods_item;


        $goods["content"] = Tool::replaceContentImage(Tool::removeContentAttr($goods["content"]));

        $data["goods"] = [
            "id"=>$id,
            "title"=>$goods["title"],
            "photo"=>Tool::thumb($goods["photo"],'medium ',true),
            "sell_price"=>$goods["sell_price"],
            "market_price"=>$goods["market_price"],
            "store_nums"=>$goods["store_nums"],
            "sale"=>$goods["sale"],
            "content"=>$goods["content"]
        ];

        return $this->returnAjax("ok",1,$data);
    }

    public function favorite(){
        $id = Request::param("id","","intval");
        if(empty($this->users)){
            return $this->returnAjax("您还没有登录，请先登录",0);
        }

        if(($row = Db::name("goods")->where("id",$id)->find()) == false){
            return $this->returnAjax("非法操作",0);
        }

        $condition = [
            "user_id"=>$this->users["id"],
            "goods_id"=>$id
        ];

        if(Db::name("users_favorite")->where($condition)->count()){
            Db::name("users_favorite")->where($condition)->delete();
            return $this->returnAjax("取消成功",1,'2');
        }

        Db::name("users_favorite")->insert([
            "user_id"=>$this->users["id"],
            "goods_id"=>$id,
            "create_time"=>time()
        ]);

        return $this->returnAjax("收藏成功",1,1);
    }

}