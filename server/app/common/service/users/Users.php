<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\service\users;

use app\common\service\Service;
use app\common\models\users\Users as UsersModel;
use app\common\models\users\UsersGroup as UsersGroupModel;
use app\common\models\users\UsersBonus as UsersBonusModel;
use app\common\models\Cart as CartModel;

class Users extends Service {

    private static $data = [];

    public static function isEmpty($field){
        return empty(self::$data[$field]);
    }

    public static function get($field=null){
        if(is_null($field)){
            return null;
        }

        return isset(self::$data[$field]) ? self::$data[$field] : null;
    }

    public static function set($data){
        self::$data = $data;
    }

    public static function info($user_id){
        if(!$row=UsersModel::where("id",$user_id)->find()){
            throw new \Exception("查找会员信息失败",0);
        }

        $row["group_name"] = UsersGroupModel::where(["id"=>$row["group_id"]])->value("name");
        $row["shop_count"] = (int)CartModel::where("user_id",$user_id)->count();
        $row["coupon_count"] = (int)UsersBonusModel::alias("u")
            ->field("b.*")
            ->join("promotion_bonus b","u.bonus_id=b.id","LEFT")
            ->where('u.status=0 and b.end_time > ' . time())
            ->where("u.user_id",$user_id)->count();

        self::set($row);
        return $row;
    }


}