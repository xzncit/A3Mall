<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\models\users;


use app\common\models\Model;

class Users extends Model {

    protected $name = "users";

    public static function statusInfo($status){
        $arr = ["0"=>"正常","1"=>"审核","2"=>"锁定","3"=>"删除"];
        return isset($arr[$status]) ? $arr[$status] : "未知";
    }

}