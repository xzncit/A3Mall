<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wap;

use mall\basic\Users;
use mall\utils\Tool;
use think\facade\Db;
use think\facade\Request;

class Special extends Base {

    public function index(){
        $page = Request::param("page","1","intval");
        $type = Request::param("type","0","intval");
        $sort = Request::param("sort","1","intval");

        switch($type){
            case '2':
                $order = 'item.goods_price';
                $text = $sort == 1 ? "ASC" : "DESC";
                break;
            case '1':
                $order = 'g.sale';
                $text = 'DESC';
                break;
            case '0':
            default :
                $order = 'p.id';
                $text = 'DESC';
                break;
        }

        $size = 10;

        $subsql = Db::name('promotion_price_item')
            ->field('pid,min(price) as goods_price')
            ->group('pid',"DESC")
            ->buildSql();

        $count = Db::name("promotion_price")->alias('p')
            ->join("goods g","p.goods_id=g.id","LEFT")
            ->join([$subsql=>"item"],"p.id=item.pid","LEFT")
            ->where('g.status',0)->count();


        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $result = Db::name("promotion_price")
            ->alias('p')
            ->field("g.id,p.id as p_id,g.title,g.photo,g.sell_price as price,g.sale,item.goods_price")
            ->join("goods g","p.goods_id=g.id","LEFT")
            ->join([$subsql=>"item"],"p.id=item.pid","LEFT")
            ->where('g.status',0)
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

        $subsql = Db::name('promotion_price_item')
            ->field('pid,min(price) as goods_price')
            ->where("pid",$id)
            ->where("group_id",Users::get("group_id"))
            ->group('pid',"DESC")
            ->buildSql();

        $goods = Db::name("promotion_price")
            ->alias('pg')
            ->field("g.*,pg.id as pg_id,pg.product_id,item.goods_price")
            ->join("goods g","pg.goods_id=g.id","LEFT")
            ->join([$subsql=>"item"],"pg.id=item.pid","LEFT")
            ->where("pg.id",$id)
            ->where('g.status',0)->find();

        if(empty($goods)){
            return $this->returnAjax("特价商品不存在",0);
        }

        $data = [];

        $data["photo"] = array_map(function ($result){
            return Tool::thumb($result["photo"],"",true);
        }, Db::name("attachments")->field("path as photo")->where([
            "pid"=>$goods["id"],
            "module"=>"goods",
            "method"=>"photo"
        ])->select()->toArray());

        $goods_item = Db::name("goods_item")
            ->where("id",$goods["product_id"])
            ->where("goods_id",$goods['id'])->select()->toArray();

        $goods_attribute = [];
        $___attr = [];
        foreach($goods_item as $val){
            $spec = explode(",",$val["spec_key"]);
            foreach($spec as $v){
                $spec_Key = explode(":",$v);
                if(!in_array($spec_Key[0].'_'.$spec_Key[1],$___attr)){
                    $___attr[] = $spec_Key[0].'_'.$spec_Key[1];
                    $goods_attribute[] = Db::name("goods_attribute")->where([
                        "goods_id"=>$goods["id"],
                        "attr_id"=>$spec_Key[0],
                        "attr_data_id"=>$spec_Key[1],
                    ])->find();
                }
            }
        }

        $goods_attr = [];
        foreach ($goods_attribute as $key=>$val){
            if(!in_array($val["attr_id"],array_keys($goods_attr))){
                $goods_attr[$val["attr_id"]] = [
                    "id"=>$val["attr_id"],
                    "name"=>$val["name"],
                    "list"=>[]
                ];
            }

            $goods_attr[$val["attr_id"]]["list"][$val["attr_data_id"]] = [
                "id"=>$val["attr_data_id"],
                "pid"=>$val["attr_id"],
                "value"=>$val["value"]
            ];
        }

        $goods_attr = array_values($goods_attr);
        foreach($goods_attr as $key=>$val){
            $goods_attr[$key]['list'] = array_values($val["list"]);
        }

        $data["attr"] = $goods_attr;

        $item = [];
        foreach($goods_item as $key=>$val){
            $sku_id = str_replace([",",":"],["_","_"], $val["spec_key"]);
            $item[$sku_id]["key"] = $val["spec_key"];
            $item[$sku_id]["sell_price"] = $goods["goods_price"];
            $item[$sku_id]["goods_weight"] = $val["goods_weight"];
            $item[$sku_id]["store_nums"] = $val["store_nums"];
            $item[$sku_id]["goods_no"] = $val["goods_number"];
            $item[$sku_id]["product_id"] = $val["id"];
        }

        $data['item'] = $item;

        $goods["content"] = Tool::replaceContentImage(Tool::removeContentAttr($goods["content"]));

        $data["goods"] = [
            "`id"=>$goods["pg_id"],
            "goods_id"=>$goods["id"],
            "title"=>$goods["title"],
            "photo"=>Tool::thumb($goods["photo"],'medium',true),
            "sell_price"=>$goods["goods_price"],
            "market_price"=>$goods["sell_price"],
            "store_nums"=>$goods["store_nums"],
            "sale"=>$goods["sale"],
            "content"=>$goods["content"]
        ];

        $data["comments"] = [];
        try{
            $comments = Users::getComments($goods["id"],4);
            $data["comments"] = $comments["data"];
        }catch (\Exception $e){}

        return $this->returnAjax("ok",1,$data);
    }

}