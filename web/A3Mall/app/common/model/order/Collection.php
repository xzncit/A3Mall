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
use app\common\model\base\Payment;
use app\common\model\users\Users;

class Collection extends A3Mall{

    protected $name = "order_collection";

    protected $type = [
        "id"=>"integer",
        "order_id"=>"integer",
        "user_id"=>"integer",
        "amount"=>"float",
        "payment_id"=>"integer",
        "admin_id"=>"integer",
        "pay_status"=>"integer",
        "is_delete"=>"integer",
        "create_time"=>"integer",
    ];

    public function lorder(){
        return $this->hasOne(Order::class,"id","order_id")
            ->bind(["order_no"])->joinType("LEFT");
    }

    public function payment(){
        return $this->hasOne(Payment::class,"id","payment_id")
            ->bind(["name"])->joinType("LEFT");
    }

    public function users(){
        return $this->hasOne(Users::class,"id","user_id")
            ->bind(['username'])->joinType("LEFT");
    }

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->withJoin(["lorder","users","payment"])->where($condition)->count();
        $result = $this->withJoin(["lorder","users","payment"])->where($condition)->order('collection.id','DESC')->paginate($size);

        $data = array_map(function ($res){
            $res["username"] = getUserName($res);
            return $res;
        },$result->items());

        return [
            "count"=>$count,
            "data"=>$data
        ];
    }

    public function setNoteAttr($value){
        return strip_tags(trim($value));
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}