<?php
namespace app\admin\controller\order;

use app\admin\controller\Auth;
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
            $arr = ["o.order_no","u.username"];
            if((isset($key["type"]) && isset($arr[$key["type"]])) && !empty($key["title"])){
                $condition[] = [$arr[$key["type"]],"like",'%'.$key["title"].'%'];
            }
            
            $count = Db::name("order_refundment")->alias("c")
                    ->join("order o","c.order_id=o.id","LEFT")
                    ->join("users u","u.id=c.user_id","LEFT")
                    ->where($condition)->count();
            
            $data = Db::name("order_refundment")->alias("c")
                    ->field('c.*,u.username')
                    ->join("order o","c.order_id=o.id","LEFT")
                    ->join("users u","u.id=c.user_id","LEFT")
                    ->where($condition)->order('c.id DESC')->paginate($limit);
            
            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }
            
            $list = $data->items();
            foreach($list as $key=>$item){
                $list[$key]["refundment_text"] = Order::getRefundmentText($item["pay_status"]);
                $list[$key]['create_time'] = Date::format($item["create_time"]);
                $list[$key]['url'] = createUrl("detail",["id"=>$item["id"]]);
                $list[$key]['order_url'] = createUrl("order.index/detail",["id"=>$item["id"]]);
            }
            
            return Response::returnArray("ok",0,$list,$count);
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