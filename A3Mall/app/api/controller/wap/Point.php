<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wap;

use app\common\model\users\Bonus as UsersBonus;
use mall\basic\Users;
use think\facade\Db;
use think\facade\Request;

class Point extends Base {

    public function index(){
        $page = Request::param("page","1","intval");
        $size = 10;

        $count = Db::name("promotion_bonus")->where("type",1)
            ->where("status",0)
            ->where(time() . "> start_time and end_time > " . time())->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $coupon = Db::name("promotion_bonus")->where("type",1)
            ->where("status",0)->where(time() . "> start_time and end_time > " . time())
            ->limit((($page - 1) * $size),$size)
            ->select()->toArray();

        $data = [];
        foreach($coupon as $k=>$v){
            $data[$k] = [
                "id"=>$v["id"],
                "name"=>$v["name"],
                "point"=>$v["point"],
                "start_time"=>date("Y-m-d",$v["start_time"]),
                "end_time"=>date("Y-m-d",$v["end_time"]),
                "active"=>Db::name("users_bonus")->where([
                    "user_id"=>Users::get("id"),
                    "bonus_id"=>$v['id'],
                    "type"=>$v["type"],
                    "status"=>0
                ])->count()
            ];
        }

        return $this->returnAjax("ok",1, [
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
                ->where('type=1 and status=0 and end_time > ' . time())->find()) == false){
            return $this->returnAjax("优惠劵己过期",0,2);
        }

        if($row["giveout"] != 0 && ($row["used"] >= $row["giveout"])){
            return $this->returnAjax("优惠劵己领完",0,1);
        }

        if(Db::name("users_bonus")
            ->where(["user_id"=>Users::get("id"),"bonus_id"=>$id,"status"=>0])->count()){
            return $this->returnAjax("该优惠劵您己领取过了",0,1);
        }

        $users = Db::name("users")->where(["id"=>Users::get("id")])->find();
        if($row["point"] > $users["point"]){
            return $this->returnAjax("兑换失败，您的积分不足",0);
        }

        try{
            Db::name("users")->where(["id"=>Users::get("id")])->update([
                "point"=>Db::raw("point-".$row["point"])
            ]);

            Db::name("users_log")->insert([
                "user_id"=>Users::get("id"),
                "action"=>1,
                "operation"=>1,
                "point"=>$row["point"],
                "description"=>"兑换" . $row["name"],
                "create_time"=>time()
            ]);

            $bonus = new UsersBonus();
            $bonus->save([
                "user_id"=>Users::get("id"),
                "type"=>$row["type"],
                "bonus_id"=>$id,
                "create_time"=>time()
            ]);

            Db::name("promotion_bonus")->where("id",$id)->inc("used")->update();
        }catch (\Exception $ex){
            return $this->returnAjax("兑换失败，请稍后在试",0);
        }

        return $this->returnAjax("领取成功",1,Db::name("users")->where('id', Users::get("id"))->value("point"));
    }

}