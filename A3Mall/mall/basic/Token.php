<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\basic;

use think\facade\Db;
use think\facade\Request;

class Token {

    public static function get($field,$value){
        return Db::name("users_token")->where($field,$value)->order("id","DESC")->value("token");
    }

    public static function set($user_id){
        $value = sha1($user_id . uniqid() . time());
        Db::name("users_token")->insert([
            "user_id"=>$user_id,
            "token"=>$value,
            "ip"=>Request::ip(),
            "expire_time"=>time()
        ]);

        return $value;
    }

    public static function check(){
        $token = Request::header("Auth-Token");
        if(($row = Db::name("users_token")->where("token",$token)->find()) == false){
            throw new \Exception("您还没有登录，请先登录","-1001");
        }

        $expires = time() - (60 * 60 * 2);
        if($expires > $row["expire_time"]){
            throw new \Exception("Token己过期，请重新登录","-1002");
        }

        $time = $expires - (60 * 5);
        if($time > $row["expire_time"]){
            Db::name("users_token")->where("token",$token)->update([
                "expire_time"=>time()
            ]);
        }

        return true;
    }

    public static function refresh(){}

    public static function delete($user_id){
        return Db::name("users_token")->where("user_id",$user_id)->delete();
    }

    public static function clear(){
        return Db::name("users_token")->where("1=1")->delete();
    }

}