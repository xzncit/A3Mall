<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\system;

use app\common\models\system\Users as UsersModel;
use app\common\models\system\Manage;

class Users extends UsersModel {

    public function manage(){
        return $this->hasOne(Manage::class,'id','role_id')->joinType("LEFT")->bind([ "cat_name" => "title" ]);
    }

    public function getTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

}