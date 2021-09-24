<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\statistics;

use app\admin\controller\Auth;
use mall\response\Response;
use think\facade\Db;
use think\facade\View;
use think\facade\Request;
use think\facade\Config;
use mall\utils\Tool;

class Search extends Auth {

    public function index(){
        // APP搜索
        $app = Db::name("statistics_search_goods")
            ->field("COUNT(if(type=0,true,null)) as android,COUNT(if(type=1,true,null)) as ios")
            ->where(["referer" => 2])->find();

        // 网页搜索
        $web = Db::name("statistics_search_goods")
            ->field("COUNT(if(referer=0,true,null)) as pc,COUNT(if(referer=1,true,null)) as wap,COUNT(if(referer=3,true,null)) as wechat")
            ->where("referer", "IN", "0,1,3")->find();

        // 小程序搜索
        $field = "COUNT(if(type=0,true,null)) as wechat,COUNT(if(type=1,true,null)) as alipay";
        $field .= ",COUNT(if(type=2,true,null)) as baidu,COUNT(if(type=3,true,null)) as zjtd";
        $field .= ",COUNT(if(type=4,true,null)) as qq";
        $mp = Db::name("statistics_search_goods")
            ->field($field)
            ->where(["referer" => 4])->find();

        $data = [];
        for ($i = 1; $i <= 6; $i++) {
            $month = strtotime("-{$i} month");
            $k = date("m",$month);
            $start_time = strtotime(date("Y-m-01",$month));
            $end_time = strtotime(date('Y-m-d', strtotime(date("Y-m-01",$month)." +1 month -1 day")));
            $condition = "create_time >= '{$start_time}' AND create_time <= '{$end_time}'";
            $data["cat"][] = $k;
            $row = Db::name("statistics_search_goods")
                ->field("COUNT(if(referer=0,true,null)) as pc,COUNT(if(referer=1,true,null)) as wap,COUNT(if(referer=2,true,null)) as app,COUNT(if(referer=3,true,null)) as wechat,COUNT(if(referer=4,true,null)) as mp")
                ->where($condition)->find();

            $data["list"][] = $row;
        }

        $list["cat"] = array_reverse($data["cat"]);
        $list["list"] = array_reverse($data["list"]);
        $array = [];
        foreach($list["list"] as $key=>$value){
            $array["pc"][] = $value["pc"];
            $array["wap"][] = $value["wap"];
            $array["app"][] = $value["app"];
            $array["wechat"][] = $value["wechat"];
            $array["mp"][] = $value["mp"];

        }

        $arr = [];
        foreach($array as $k=>$v){
            $arr[$k] = implode(",",$v);

        }
        $list["list"] = $arr;

        return View::fetch("",[
            "app"=>$app,"web"=>$web,"mp"=>$mp,
            "data"=>$list
        ]);
    }

    public function ranking(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::param("key/a","","trim,strip_tags");

            $start_time = !empty($key["start_time"]) ? strtotime($key["start_time"]) : "";
            $end_time = !empty($key["end_time"]) ? strtotime($key["end_time"]) : "";

            if(!empty($start_time) && ($start_time > $end_time)){
                return Response::returnArray("开始时间不能大于结束时间！",1);
            }

            $condition = "";
            if(!empty($start_time)){
                $condition .= " (s.create_time >= '{$start_time}' AND s.create_time <= '{$end_time}')";
            }

            $count = Db::name("statistics_search_goods")->alias("s")
                ->join("goods g","g.id=s.goods_id","LEFT")
                ->where($condition)->count();

            $data = Db::name("statistics_search_goods")
                ->field("s.name,g.title,COUNT(s.goods_id) as num")->alias("s")
                ->join("goods g","g.id=s.goods_id","LEFT")
                ->where($condition)->group("s.goods_id")
                ->order("num DESC")->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function search_goods_clear(){
        if(!Request::isAjax()){
            return Response::returnArray("该页面不允许直接访问哦！",0);
        }

        $prefix = Tool::prefix();
        $sql = "TRUNCATE ".$prefix."statistics_search_goods";
        Db::name("statistics_search_goods")->query($sql);
        return Response::returnArray("ok");
    }

    public function detailed(){
        if(Request::isAjax()){
            $limit = Request::get("limit");

            $count = Db::name("statistics_search")->count();
            $data = Db::name("statistics_search")
                ->order("num DESC")->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function detailed_clear(){
        if(!Request::isAjax()){
            return Response::returnArray("该页面不允许直接访问哦！",0);
        }

        $prefix = Tool::prefix();
        $sql = "TRUNCATE ".$prefix."statistics_search";
        Db::name("statistics_search")->query($sql);
        return Response::returnArray("ok");
    }
}