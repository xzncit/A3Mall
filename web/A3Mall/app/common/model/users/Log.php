<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\users;

use app\common\model\base\A3Mall;

class Log extends A3Mall {

    protected $name = "users_log";

    protected $type = [
        "id"=>"integer",
        "user_id"=>"integer",
        "admin_id"=>"integer",
        "action"=>"integer",
        "operation"=>"integer",
        "amount"=>"float",
        "point"=>"integer",
        "exp"=>"integer",
        "create_time"=>"integer"
    ];

    public function users(){
        return $this->hasOne(Users::class,"id","user_id")
            ->bind(["username"])
            ->joinType("LEFT");
    }

    public function getList($condition=[],$size=10){
        $count = $this->withJoin("users")->where($condition)->count();
        $result = $this->withJoin("users")->where($condition)->order('log.id','desc')->paginate($size);

        $data = array_map(function ($res){
            $res["username"] = getUserName($res);
            return $res;
        },$result->items());

        return [
            "count"=>$count,
            "data"=>$data
        ];
    }

    public function setOrderNoAttr($value){
        return strip_tags(trim($value));
    }

    public function setDescriptionAttr($value){
        return strip_tags(trim($value));
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

}