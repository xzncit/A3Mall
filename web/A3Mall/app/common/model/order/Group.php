<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\order;

use app\common\model\base\A3Mall;
use app\common\model\users\Users;

class Group extends A3Mall {

    protected $name = "order_group";

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "user_id"=>"integer",
        "order_id"=>"integer",
        "goods_nums"=>"integer",
        "order_amount"=>"float",
        "group_id"=>"integer",
        "goods_id"=>"integer",
        "people"=>"integer",
        "sell_price"=>"float",
        "start_time"=>"integer",
        "end_time"=>"integer",
        "is_refund"=>"integer",
        "status"=>"integer",
        "create_time"=>"integer",
        "complete_time"=>"integer"
    ];

    public function getList($condition=[],$size=10,$page=10){
        $count = $this->withJoin(["users"])->where($condition)->count();
        $data = $this->withJoin(["users"])->where($condition)->order('id','desc')->paginate($size);

        $list = array_map(function ($res){
            $res['people_count'] = $this->where("pid",$res["id"])->count()+1;
            $res["username"] = getUserName($res);
            return $res;
        },$data->items());

        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

    public function users(){
        return $this->hasOne(Users::class,"id","user_id")
            ->bind(["username"])->joinType("LEFT");
    }

    public function setGoodsTitleAttr($value){
        return strip_tags($value);
    }

    public function getStartTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getEndTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}