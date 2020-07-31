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
use app\common\model\promotion\Bonus as PromotionBonus;
use app\common\model\users\Bonus as UsersBonus;
use mall\basic\Users;

class Bonus extends Auth {

    public function index(){
        $page = Request::param("page","1","intval");
        $size = 10;

        try{
            $condition = 'status=0 && end_time > ' . time();
            $bonus = new PromotionBonus();
            $list = $bonus->getList($condition,$size,$page);
        }catch(\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }

        $data = [];
        foreach($list['data'] as $key=>$value){
            $data[$key] = [
                "id"=>$value["id"],
                "name"=>$value["name"],
                "amount"=>number_format($value["amount"]),
                "price"=>$value["order_amount"],
                "end_time"=>date('Y-m-d',$value->getData("end_time")),
                "is_receive"=>Db::name("users_bonus")
                    ->where("bonus_id",$value["id"])
                    ->where("user_id",Users::get("id"))
                    ->count()
            ];
        }

        return $this->returnAjax("ok",1,[
            "list"=>$data,
            "page"=>$page,
            "total"=>$list["total"],
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

        if(Db::name("users_bonus")
            ->where(["user_id"=>Users::get("id"),"bonus_id"=>$id])->count()){
            return $this->returnAjax("该优惠劵您己领取过了");
        }

        $bonus = new UsersBonus();
        $bonus->save([
            "user_id"=>Users::get("id"),
            "type"=>$row["type"],
            "bonus_id"=>$id,
            "create_time"=>time()
        ]);

        return $this->returnAjax("领取成功",1);
    }
}