<?php
namespace mall\basic;

use think\facade\Db;

class Users {

    private static $data = [];

    public static function get($field){
        return isset(self::$data[$field]) ? self::$data[$field] : null;
    }

    public static function set($data){
        self::$data = $data;
    }

    public static function info($user_id){
        if(($row=Db::name("users")->where("id",$user_id)->find()) == false){
            return false;
        }

        $row["group_name"] = Db::name("users_group")->where(["id"=>$row["group_id"]])->value("name");
        $row["shop_count"] = (int)Db::name("cart")->where("user_id",$user_id)->count();
        $row["coupon_count"] = (int)Db::name("users_bonus")
            ->alias("u")
            ->field("b.*")
            ->join("promotion_bonus b","u.bonus_id=b.id","LEFT")
            ->where('u.status=0 and b.end_time > ' . time())
            ->where("u.user_id",$user_id)->count();

        self::set($row);
        return $row;
    }

    public static function delete($id=""){
        if(($row = Db::name("users")->where(['id'=>$id])->find()) == false){
            return true;
        }

        try {
            Db::name("users")->where(['id'=>$id])->delete();
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        return true;
    }

    public static function statusInfo($status){
        $arr = ["0"=>"正常","1"=>"审核","2"=>"锁定","3"=>"删除"];
        return isset($arr[$status]) ? $arr[$status] : "未知错误";
    }
}