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

class Users extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "group_id"=>"integer",
        "birthday"=>"integer",
        "sex"=>"integer",
        "exp"=>"integer",
        "point"=>"integer",
        "status"=>"integer",
        "is_spread"=>"integer",
        "spread_id"=>"integer",
        "spread_time"=>"integer",
        "pay_count"=>"integer",
        "spread_count"=>"integer",
        "amount"=>"float",
        "spread_amount"=>"float",
        "create_time"=>"integer",
        "last_login"=>"integer"
    ];

    public function group(){
        return $this->hasOne(Group::class,'id','group_id')
            ->bind(["group_name"=>"name"])->joinType("LEFT");
    }

    public function getList($condition=[],$size=10){
        $count = $this->withJoin("group")->where($condition)->count();
        $data = $this->withJoin("group")->where($condition)->order('users.id desc')->paginate($size);

        $list = array_map(function ($res){
            $res['time'] = $res->create_time;
            return $res;
        },$data->items());

        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function setUsernameAttr($value){
        return strip_tags(trim($value));
    }

    public function setPasswordAttr($value){
        return strip_tags(trim($value));
    }

    public function setEmailAttr($value){
        return strip_tags(trim($value));
    }

    public function setAvatarAttr($value){
        return strip_tags(trim($value));
    }

    public function setNicknameAttr($value){
        return strip_tags(trim($value));
    }

    public function setRealnameAttr($value){
        return strip_tags(trim($value));
    }

    public function setMobileAttr($value){
        return strip_tags(trim($value));
    }

    public function setCreateIpAttr($value){
        return strip_tags(trim($value));
    }

    public function setLastIpAttr($value){
        return strip_tags(trim($value));
    }


}