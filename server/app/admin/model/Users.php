<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use app\common\models\users\Users as UsersModel;
use app\common\models\users\UsersGroup;

class Users extends UsersModel {

    /**
     * 增加查询条件
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchGroupIdAttr($query, $value, $data){
        if(!empty($value) && $value != '-1'){
            $query->where('users.group_id','=',$value);
        }
    }

    /**
     * 增加查询条件
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchUsernameAttr($query, $value, $data){
        if(!empty($value)){
            $query->where('users.username','like',"%" . $value . "%");
        }
    }

    /**
     * 关联表
     * @return \think\model\relation\HasOne
     */
    public function order(){
        return $this->hasOne(Order::class,'id','user_id')->joinType("LEFT");
    }

    /**
     * 关联表
     * @return \think\model\relation\HasOne
     */
    public function group(){
        return $this->hasOne(UsersGroup::class,'id','group_id')->bind(["group_name"=>"name"])->joinType("LEFT");
    }

    public function getSpreadTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

}