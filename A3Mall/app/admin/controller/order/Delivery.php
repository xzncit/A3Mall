<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\order;

use app\admin\controller\Auth;
use app\common\model\order\Delivery as  OrderDelivery;
use mall\basic\Area;
use think\facade\Request;
use think\facade\Db;
use mall\utils\Date;
use mall\response\Response;
use think\facade\View;

class Delivery extends Auth {
    
    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            $arr = ["lorder.order_no","users.username"];
            if((isset($key["type"]) && isset($arr[$key["type"]])) && !empty($key["title"])){
                $condition[] = [$arr[$key["type"]],"like",'%'.$key["title"].'%'];
            }

            $orderDelivery = new OrderDelivery();
            $list = $orderDelivery->getList($condition,$limit);

            if(empty($list["data"])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list["data"],$list['count']);
        }
        
        return View::fetch();
    }
    
    public function detail(){
        $id = Request::param("id");
        
        $data = Db::name("order_delivery")->alias("c")
                    ->field('c.id as id,c.admin_id,o.order_no,c.order_id,d.title as pname,o.create_time as order_create_time,u.username,c.name,c.province,c.city,c.area,c.address,c.mobile,c.phone,c.zip,c.freight,c.distribution_code,c.create_time,c.note')
                    ->join("order o","c.order_id=o.id","LEFT")
                    ->join("users u","u.id=c.user_id","LEFT")
                    ->join("distribution d","c.distribution_id=d.id","LEFT")->where('c.id',$id)->find();
        
        if(empty($data)){
            $this->error("您要查找的内容不存在！");
        }

        $data["area_name"] = Area::get_area([$data['province'], $data['city'], $data['area']],",");
        $data["order_create_time"] = Date::format($data['order_create_time']);
        $data["create_time"] = Date::format($data['create_time']);
        $data["goods"] = Db::name("order_goods")->where(["order_id" => $data["order_id"]])->order("id DESC")->select()->toArray();
        
        if($data["admin_id"] == "-1"){
            $data['admin_name'] = 'system';
        }else{
            $data['admin_name'] = Db::name("system_users")->where(["id"=>$data["admin_id"]])->value("username");
        }
        
        foreach($data["goods"] as $key=>$item){
            $data["goods"][$key]["goods_array"] = "";
            if(!empty($item["goods_array"])){
                $data["goods"][$key]["goods_array"] = json_decode($item["goods_array"],true);
            }
            
            $data["goods"][$key]["order_price"] = number_format($item["goods_nums"]*$item["sell_price"],2);
        }
        
        return View::fetch("",[
            "data"=>$data
        ]);
    }
    
    
}