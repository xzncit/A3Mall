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
use mall\utils\Tool;
use think\facade\Config;
use app\common\models\goods\Goods as GoodsModel;
use think\facade\Db;

class Home extends Service {

    /**
     * 获取装修数据
     * @param array $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getData(){
        $banner = Db::name("data")->where("sign","banner")->find();
        $slider = array_map(function($res){
            return [
                "photo" => Tool::thumb($res["photo"],"",true),
                "url"   => $res["url"]
            ];
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
                "id"=> $res["id"],
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
                "id"=> $res["id"],
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

        $notice = Db::name("archives")->field('id,title')->where("status",0)->where('pid',71)->find();

        return [
            "banner"=>$slider,
            "nav"=>$nav,
            "img_1"=>isset($adItemOne[0]) ? $adItemOne[0] : [],
            "img_2"=>$adItemTwo,
            "hot"=>$hot,
            "recommend"=>$recommend,
            "notice"=>isset($notice) ? $notice : []
        ];
    }

    /**
     * 获取商品数据列表
     * @param array $data
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data=[]){
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $condition = ["status"=>0];
        $array = ["list"=>[], "page"=>$page, "total"=>0, "size"=>$size];

        $count = GoodsModel::where($condition)->count();
        $total = ceil($count/$size);
        $array["total"] = $total;
        $array["size"] = $size;
        if($total == $page -1){
            throw new BaseException("empty",-1,$array);
        }

        $result = GoodsModel::field("id,title,photo,sell_price as price,sale")->where($condition)->order('id','desc')->page($page,$size)->select()->toArray();
        $array["list"] = array_map(function ($rs){
            $rs["photo"] = Tool::thumb($rs["photo"],"medium",true);
            return $rs;
        },$result);

        return $array;
    }

}