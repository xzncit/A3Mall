<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\system;

use app\common\model\base\A3Mall;

class Users extends A3Mall {

    protected $name = "system_users";

    protected $type = [
        "id"=>"integer",
        "role_id"=>"integer",
        "status"=>"integer",
        "lock"=>"integer",
        "count"=>"integer",
        "time"=>"integer"
    ];

    public function profile(){
        return $this->hasOne(Manage::class,'id','role_id')
            ->joinType("LEFT")->bind([
                "cat_name"=>"title"
            ]);
    }

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->withJoin("profile")->where($condition)->count();
        $data = $this->withJoin("profile")->where($condition)->order('id','desc')->paginate($size);
        $list = array_map(function ($res){
            $res["create_time"] = $res->time;
            return $res;
        },$data->items());

        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

    public function setAvatarAttr($value){
        return strip_tags(trim($value));
    }

    public function setUsernameAttr($value){
        return strip_tags(trim($value));
    }

    public function setPasswordAttr($value){
        return strip_tags(trim($value));
    }

    public function setSaltAttr($value){
        return strip_tags(trim($value));
    }

    public function setEmailAttr($value){
        return strip_tags(trim($value));
    }

    public function setIpAttr($value){
        return strip_tags(trim($value));
    }

    public function getTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}