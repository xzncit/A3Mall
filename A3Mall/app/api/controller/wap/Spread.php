<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller\wap;

use mall\utils\Tool;
use think\facade\Request;
use think\facade\Db;
use mall\basic\Users;

class Spread extends Auth {

    public function index(){
        return $this->returnAjax("ok",1,[
            "amount"=>Users::get("spread_amount"),
            "amount_count"=>Db::name("users_withdraw_log")->where("user_id",Users::get("id"))->where("withdraw_type",0)->where("status",1)->sum("price"),
            "yesterday_amount"=>Db::name("users_log")->where("user_id",Users::get("id"))->where("action",4)->where("operation",0)->whereTime("create_time","-24 hours")->sum("amount")
        ]);
    }

    public function promotion_list(){
        $page = Request::param("page","1","intval");
        $size = 10;
        $type = Request::param("type",'1',"intval");
        if(!in_array($type,[1,2,3])){
            $type = 1;
        }

        switch($type){
            case 1:
                $count = Db::name("users")
                    ->alias("u")
                    ->field("u.*,SUM(o.order_amount) as sum_order_amount,COUNT(o.id) as order_count")
                    ->join("order o","u.id=o.user_id","LEFT")
                    ->where(["u.spread_id"=>Users::get("id")])
                    ->group("u.id")->count();
                break;
            case 2:
                $count = Db::name('users')
                    ->alias("u")
                    ->field("u.*,SUM(o.order_amount) as sum_order_amount,COUNT(o.id) as order_count")
                    ->join("order o","u.id=o.user_id","LEFT")
                    ->where('u.spread_id','IN',function($query){
                        $query->name('users')->where('spread_id',Users::get("id"))->field('id');
                    })->group("u.id")->count();
                break;
        }


        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        switch($type){
            case 1:
                $data = Db::name("users")
                    ->alias("u")
                    ->field("u.*,SUM(o.order_amount) as sum_order_amount,COUNT(o.id) as order_count")
                    ->join("order o","u.id=o.user_id","LEFT")
                    ->where(["u.spread_id"=>Users::get("id")])
                    ->group("u.id")
                    ->order("u.id desc")->limit((($page - 1) * $size),$size)->select()->toArray();
                break;
            case 2:
                $data = Db::name('users')
                    ->alias("u")
                    ->field("u.*,SUM(o.order_amount) as sum_order_amount,COUNT(o.id) as order_count")
                    ->join("order o","u.id=o.user_id","LEFT")
                    ->where('u.spread_id','IN',function($query){
                        $query->name('users')->where('spread_id',Users::get("users.id"))->field('id');
                    })->group("u.id")->order("u.id desc")->limit((($page - 1) * $size),$size)->select()->toArray();
                break;
        }

        $list = [];
        foreach($data as $k=>$v){
            $list[$k]["nickname"] = $v["username"];
            $list[$k]["avatar"] = empty($v["avatar"]) ? "" : Tool::thumb($v["avatar"],"",true);
            $list[$k]["order_count"] = $v["order_count"];
            $list[$k]["sum_order_amount"] = number_format($v["sum_order_amount"],2);
            $list[$k]["time"] = date("Y-m-d H:i:s",$v["spread_time"]);
        }

        return $this->returnAjax("ok",1,[
            "list"=>$list,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

    public function promotion_order(){
        $page = Request::param("page","1","intval");
        $size = 10;

        $count = Db::name("users_log")
            ->alias("l")
            ->field("l.*,o.create_time as order_time,u.username")
            ->join("order o","l.order_no=o.order_no","LEFT")
            ->join("users u","l.user_id=u.id","LEFT")
            ->where("l.user_id",Users::get("id"))
            ->where("l.action","4")->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $data = Db::name("users_log")
            ->alias("l")
            ->field("l.*,o.create_time as order_time,u.username,o.order_no")
            ->join("order o","l.order_no=o.order_no","LEFT")
            ->join("users u","l.user_id=u.id","LEFT")
            ->where("l.user_id",Users::get("id"))
            ->where("l.action","4")->order('l.id DESC')
            ->limit((($page - 1) * $size),$size)->select()->toArray();

        $list = [];
        foreach($data as $key=>$value){
            $list[$key]['nickname'] = $value['username'];
            $list[$key]['order_no'] = $value['order_no'];
            $list[$key]['amount'] = $value['amount'];
            $list[$key]["avatar"] = empty($value["avatar"]) ? "" : Tool::thumb($value["avatar"],"",true);
            $list[$key]['time'] = date("Y-m-d H:i:s",$value["order_time"]);
        }

        return $this->returnAjax("ok",1,[
            "list"=>$list,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

    public function commission(){
        $page = Request::param("page","1","intval");
        $size = 10;

        $count = Db::name("users_log")
            ->where("user_id",Users::get("id"))
            ->where("action","4")
            ->where("operation","0")
            ->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $data = Db::name("users_log")
            ->where("user_id",Users::get("id"))
            ->where("action","4")
            ->where("operation","0")
            ->order('id DESC')->select()->toArray();

        $list = [];
        foreach($data as $key=>$value){
            $list[$key]["description"] = $value["description"];
            $list[$key]["amount"] = $value["amount"];
            $list[$key]['time'] = date("Y-m-d H:i:s",$value["create_time"]);
        }

        return $this->returnAjax("ok",1,[
            "list"=>$list,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

    public function cashrecord(){
        $page = Request::param("page","1","intval");
        $size = 10;

        $count = Db::name("users_log")
            ->where("user_id",Users::get("id"))
            ->where("action","4")
            ->where("operation","1")
            ->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $data = Db::name("users_log")
            ->where("user_id",Users::get("id"))
            ->where("action","4")
            ->where("operation","1")
            ->order('id DESC')->select()->toArray();

        $list = [];
        foreach($data as $key=>$value){
            $list[$key]["description"] = $value["description"];
            $list[$key]["amount"] = $value["amount"];
            $list[$key]['time'] = date("Y-m-d H:i:s",$value["create_time"]);
        }

        return $this->returnAjax("ok",1,[
            "list"=>$list,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

    public function settlement(){
        $setting = Db::name("setting")->where(["name"=>"spread"])->value("value");
        $setting = json_decode($setting,true);
        $setting["bank"] = explode("|",$setting["bank"]);
        return $this->returnAjax("ok",1,[
            "bank"=>$setting["bank"],
            "money"=>Users::get("spread_amount")
        ]);
    }

    public function settlement_save(){
        $data = Request::post();
        $setting = Db::name("setting")->where(["name"=>"spread"])->value("value");
        $setting = json_decode($setting,true);
        if(Db::name("users_withdraw_log")->where(["user_id"=>Users::get("id"),"status"=>0,"withdraw_type"=>0])->count()){
            return $this->returnAjax("您还有提现申请未处理。",0);
        }

        if(empty($data["name"])){
            return $this->returnAjax("请填写持卡人。",0);
        }

        if(empty($data["code"])){
            return $this->returnAjax("请填写卡号。",0);
        }

        if(empty($data["price"])){
            return $this->returnAjax("请填写金额。",0);
        }

        if($data["price"] < $setting["amount"]){
            return $this->returnAjax("提现金额不能小于" . $setting["amount"],0);
        }

        Db::name("users_withdraw_log")->insert([
            "user_id"=>Users::get("id"),
            "withdraw_type"=>0,
            "bank_name"=>$data["bank_type"],
            "bank_real_name"=>$data["name"],
            "type"=>1,
            "code"=>$data["code"],
            "price"=>$data["price"],
            "status"=>0,
            "create_time"=>time()
        ]);

        return $this->returnAjax("申请提现成功，请等待管理员审核");
    }

}