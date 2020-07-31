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

class WithdrawLog extends A3Mall{

    protected $name = "users_withdraw_log";

    protected $type = [
        "id"=>"integer",
        "user_id"=>"integer",
        "withdraw_type"=>"integer",
        "type"=>"integer",
        "price"=>"float",
        "status"=>"integer",
        "create_time"=>"integer",
        "update_time"=>"integer"
    ];

    public function users(){
        return $this->hasOne(Users::class,"id","user_id")
            ->bind(["username"])
            ->joinType("LEFT");
    }

    public function get_list($condition=[],$size=10,$page=1){
        $count = $this->withJoin("users")->where($condition)->count();
        $data = $this->withJoin("users")->where($condition)->order('withdraw_log.id','desc')->paginate($size);

        return [
            "count"=>$count,
            "data"=>$data->items()
        ];
    }

    public function setBankNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setBankRealName($value){
        return strip_tags(trim($value));
    }

    public function setCodeAttr($value){
        return strip_tags(trim($value));
    }

    public function setAddressAttr($value){
        return strip_tags(trim($value));
    }

    public function setAccountAttr($value){
        return strip_tags(trim($value));
    }

    public function setMarkAttr($value){
        return strip_tags(trim($value));
    }

    public function setMsgAttr($value){
        return strip_tags(trim($value));
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getUpdateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}