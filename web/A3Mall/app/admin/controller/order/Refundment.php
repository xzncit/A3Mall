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
use app\common\model\order\Refundment as OrderRefundment;
use think\facade\Request;
use think\facade\Db;
use mall\basic\Order;
use mall\utils\Date;
use mall\response\Response;
use think\facade\View;

class Refundment extends Auth {
    
    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            $arr = ["lorder.order_no","users.username"];
            if((isset($key["type"]) && isset($arr[$key["type"]])) && !empty($key["title"])){
                $condition[] = [$arr[$key["type"]],"like",'%'.$key["title"].'%'];
            }

            $orderRefundment = new OrderRefundment();
            $list = $orderRefundment->getList($condition,$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }
            
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }
        
        return View::fetch();
    }
    
    public function detail(){
        $id = Request::param("id");
        
        $data = Db::name("order_refundment")->alias("c")
                    ->field('o.order_no,o.create_time as order_create_time,u.username,c.*')
                    ->join("order o","c.order_id=o.id","LEFT")
                    ->join("users u","u.id=c.user_id","LEFT")->where('c.id',$id)->find();
        
        if(empty($data)){
            $this->error("您要查找的内容不存在！");
        }

        $data["username"] = getUserName($data);
        $data["create_time"] = Date::format($data['create_time']);
        $data["order_create_time"] = Date::format($data['order_create_time']);
        $data["refundment_text"] = Order::getRefundmentText($data["pay_status"]);
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