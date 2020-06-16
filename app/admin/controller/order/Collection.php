<?php
namespace app\admin\controller\order;

use app\admin\controller\Auth;
use think\facade\Request;
use think\facade\Db;
use mall\utils\Date;
use mall\response\Response;
use think\facade\View;

class Collection extends Auth {
    
    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");
            
            $condition = [];
            $arr = ["o.order_no","u.username"];
            if((isset($key["type"]) && isset($arr[$key["type"]])) && !empty($key["title"])){
                $condition[] = [$arr[$key["type"]],"like",'%'.$key["title"].'%'];
            }
            
            $count = Db::name("order_collection")
                    ->alias("c")
                    ->join("order o","c.order_id=o.id","LEFT")
                    ->join("users u","u.id=c.user_id","LEFT")
                    ->join("payment p","c.payment_id=p.id","LEFT")
                    ->where($condition)->count();
            
            $data = Db::name("order_collection")
                    ->alias("c")
                    ->field('o.order_no,c.amount,u.username,p.name,c.id,c.pay_status,c.create_time')
                    ->join("order o","c.order_id=o.id","LEFT")
                    ->join("users u","c.user_id=u.id","LEFT")
                    ->join("payment p","c.payment_id=p.id","LEFT")
                    ->where($condition)->order('c.id DESC')->paginate($limit);
    
            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }
            
            $list = $data->items();
            
            foreach($list as $key=>$item){
                $list[$key]['create_time'] = Date::format($item["create_time"]);
                $list[$key]['url'] = createUrl("detail",["id"=>$item["id"]]);
            }
            
            return Response::returnArray("ok",0,$list,$count);
        }
        
        return View::fetch();
    }
    
    public function detail(){
        $id = Request::param("id");
        
        $data = Db::name("order_collection")->alias("c")
                    ->field('o.order_no,p.name as pname,o.create_time,p.type,u.username,c.amount,o.pay_time,c.admin_id,c.note')
                    ->join("order o","c.order_id=o.id","LEFT")
                    ->join("users u","u.id=c.user_id","LEFT")
                    ->join("payment p","c.payment_id=p.id","LEFT")->where('c.id',$id)->find();
        
        if(empty($data)){
            $this->error("您要查找的内容不存在！");
        }
        
        $data["create_time"] = Date::format($data['create_time']);
        $data["pay_time"] = Date::format($data['pay_time']);
        if($data["admin_id"] == "-1"){
            $data['admin_name'] = 'system';
        }else{
            $data['admin_name'] = Db::name("system_users")->where(["id"=>$data["admin_id"]])->value("username");
        }
        
        return View::fetch("",[
            "data"=>$data
        ]);
    }
    
}