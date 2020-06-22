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

class Bonus extends Auth {

    public function index(){
        $page = Request::param("page","1","intval");
        $size = 10;

        $condition = 'status=0 && end_time > ' . time();

        $count = Db::name("promotion_bonus")
            ->where($condition)
            ->count();

        $total = ceil($count / $size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $bonus = Db::name("promotion_bonus")
            ->field("id,name,amount,order_amount,end_time")
            ->where($condition)
            ->order("id","DESC")
            ->limit((($page - 1) * $size),$size)
            ->select()->toArray();

        $data = [];
        foreach($bonus as $key=>$value){
            $data[$key] = [
                "id"=>$value["id"],
                "name"=>$value["name"],
                "amount"=>number_format($value["amount"]),
                "price"=>$value["order_amount"],
                "end_time"=>date('Y-m-d',$value["end_time"]),
                "is_receive"=>Db::name("users_bonus")->where("bonus_id",$value["id"])->count()
            ];
        }

        return $this->returnAjax("ok",1,[
            "list"=>$data,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

    public function receive(){
        $id = Request::param("id","0","intval");
        if(($row=Db::name("promotion_bonus")
            ->where("id",$id)
            ->where('status=0 && end_time > ' . time())->find()) == false){
            return $this->returnAjax("优惠劵己过期",0);
        }

        if(Db::name("users_bonus")->where(["user_id"=>$this->users["id"],"bonus_id"=>$id])->count()){
            return $this->returnAjax("本优惠劵您己领取过了");
        }

        Db::name("users_bonus")->insert([
            "user_id"=>$this->users["id"],
            "type"=>$row["type"],
            "bonus_id"=>$id,
            "create_time"=>time()
        ]);

        return $this->returnAjax("领取成功",1);
    }
}