<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wap;

use app\common\model\custom\Pages;
use mall\utils\Tool;
use think\facade\Db;
use think\facade\Request;

class Index extends Auth {

    public function index(){
        $banner = Db::name("data")->where("sign","banner")->find();
        $slider = array_map(function($res){
            return Tool::thumb($res["photo"],"",true);
        },Db::name("data_item")->where("pid",$banner["id"])->order("sort","ASC")->select()->toArray());

        $category = Db::name("data")->where("sign","category")->find();
        $nav = array_map(function($res){
            return [
                "url"=>$res["url"],
                "name"=>$res["name"],
                "image"=>Tool::thumb($res["photo"],"",true)
            ];
        },Db::name("data_item")->where("pid",$category["id"])->order("sort","ASC")->select()->toArray());

        $adOne = Db::name("data")->where("sign","home_ad_one")->find();
        $adItemOne = array_map(function($res){
            return [
                "url"=>$res["url"],
                "name"=>$res["name"],
                "image"=>Tool::thumb($res["photo"],"",true)
            ];
        },Db::name("data_item")->where("pid",$adOne["id"])->order("sort","ASC")->select()->toArray());

        $adTwo = Db::name("data")->where("sign","home_ad_two")->find();
        $adItemTwo = array_map(function($res){
            return [
                "url"=>$res["url"],
                "name"=>$res["name"],
                "image"=>Tool::thumb($res["photo"],"",true)
            ];
        },Db::name("data_item")->where("pid",$adTwo["id"])->order("sort","ASC")->select()->toArray());

        $hot = array_map(function ($res){
                return [
                    "url"=>'/goods/view/'.$res["id"],
                    "name"=>$res["title"],
                    "image"=>Tool::thumb($res["photo"],"",true),
                    "price"=>$res["sell_price"]
                ];
            },
            Db::name("goods_extends")
            ->alias("e")->field("g.*")->join("goods g","e.goods_id=g.id","LEFT")
            ->where('g.status',0)->where("e.attribute","hot")
            ->order("e.id","DESC")->limit(3)->select()->toArray()
        );

        $recommend = array_map(function ($res){
            return [
                "url"=>'/goods/view/'.$res["id"],
                "name"=>$res["title"],
                "image"=>Tool::thumb($res["photo"],"",true),
                "price"=>$res["sell_price"]
            ];
        },
            Db::name("goods_extends")
                ->alias("e")->field("g.*")->join("goods g","e.goods_id=g.id","LEFT")
                ->where('g.status',0)->where("e.attribute","recommend")
                ->order("e.id","DESC")->limit(5)->select()->toArray()
        );

        return $this->returnAjax("ok",1,[
            "banner"=>$slider,
            "nav"=>$nav,
            "img_1"=>isset($adItemOne[0]) ? $adItemOne[0] : [],
            "img_2"=>$adItemTwo,
            "hot"=>$hot,
            "recommend"=>$recommend
        ]);
    }

    public function get_list(){
        $page = Request::param("page","1","intval");

        $size = 10;
        $count = Db::name("goods")
            ->where('status',0)->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $result = Db::name("goods")
            ->field("id,title,photo,sell_price as price,sale")
            ->where('status',0)
            ->order('id','desc')->limit((($page - 1) * $size),$size)->select()->toArray();

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

}
