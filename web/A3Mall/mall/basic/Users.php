<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\basic;

use mall\utils\CString;
use think\facade\Db;
use think\facade\Request;
use mall\utils\Check;
use think\facade\Session;
use think\facade\Cookie;
use mall\utils\Hash;

class Users {

    private static $data = [];

    public static function init(){
        if(Cookie::has("user") && ($data = Hash::decrypt(Cookie::get("user"))) != false){
            Session::set("user_id",$data["value"]);
        }

        if(!Session::has("user_id")){
            return false;
        }

        if(Db::name("users")->where("id",Session::get("user_id"))->count() <= 0){
            Session::delete("user_id");
            Cookie::delete("user");
            return false;
        }

        $userInfo = self::info(Session::get("user_id"));
        $userInfo["register_time"] = date("Y-m-d H:i:s",$userInfo["create_time"]);

        if(Session::has("users_last_login")){
            $userInfo["last_login_time"] = date("Y-m-d H:i:s",$userInfo["last_login"]);
            $userInfo["users_last_time"] = Session::has("users_last_time") ? Session::get("users_last_time") : date("Y-m-d H:i:s",$userInfo["last_login"]);
        }else{
            $last_login = time();
            Db::name("users")->where(["id"=>$userInfo["id"]])->update([
                "last_login"=>$last_login
            ]);

            $userInfo["users_last_time"] = date("Y-m-d H:i:s",$userInfo["last_login"]);
            Session::set("users_last_time",$userInfo["users_last_time"]);
            $userInfo["last_login_time"] = date("Y-m-d H:i:s",$last_login);
            Session::set("users_last_login",$last_login);
        }

        if($userInfo['exp'] > 0){
            $r = Db::name("users_group")->where("minexp","<=",$userInfo["exp"])->where("maxexp",">=",$userInfo["exp"])->find();
            if($userInfo["group_id"] != $r['id']){
                Db::name("users")->where(["id"=>$userInfo["id"]])->update(["group_id"=>$r['id']]);
            }
        }

        return G(["users"=>$userInfo]);
    }

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

    public static function getComments($id,$type=0,$size=5,$page=1){
        $count = Db::name("users_comment")->alias("uc")
            ->join("order o","uc.order_no=o.order_no","LEFT")
            ->join("users u","uc.user_id=u.id","LEFT")
            ->where("o.type",$type)
            ->where("uc.goods_id",$id)
            ->where("uc.status",1)->count();

        $total = ceil($count / $size);
        if($total == $page -1){
            throw new \Exception("没有数据了哦！",-1);
        }

        $result = Db::name("users_comment")->alias("uc")
            ->field("uc.contents,uc.reply_content,u.avatar,uc.comment_time,u.username,u.nickname,u.mobile")
            ->join("order o","uc.order_no=o.order_no","LEFT")
            ->join("users u","uc.user_id=u.id","LEFT")
            ->where("o.type",$type)
            ->where("uc.goods_id",$id)
            ->where("uc.status",1)
            ->order("uc.id","DESC")->paginate($size);

        $rs = array_map(function ($data){
            $array = [];
            $username = !empty($data["nickname"]) ? $data["nickname"] : $data["username"];
            $array['time'] = date("Y-m-d",$data['comment_time']);
            $array['avatar'] = self::avatar($data['avatar']);
            $array['content'] = strip_tags($data['contents']);
            $array['reply_content'] = strip_tags($data['reply_content']);

            if(!empty($username)){
                $array['username'] = CString::msubstr($username,3,false) . "***";
            }else{
                $array['username'] = preg_replace('/(1[3-9]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$data['mobile']);
            }

            return $array;
        },$result->items());

        return [
            "count"=>$count,
            "total"=>$total,
            "data"=>$rs
        ];
    }

    public static function avatar($image="",$root=true){
        if(Check::url($image)){
            return $image;
        }

        if(Request::domain() == "http://" || Request::domain() == "https://"){
            $domain = $root ? trim(env("web.app_web_url"),"/") : "";
        }else{
            $domain = $root ? trim(Request::domain(),"/") : "";
        }

        $setting = Setting::get("upload");
        if(isset($setting["type"]) && $setting["type"] == 1){
            if(empty($image)){
                return $domain . "/static/images/avatar.png";
            }

            if(!empty($setting["domain"])){
                return trim($setting["domain"],"/") . "/" . trim($image,"/");
            }

            $protocol = !empty($setting["protocol"]) ? $setting["protocol"] : "auto";
            $url = "";
            if($protocol == "http" || $protocol == "https"){
                $url .= $protocol . "://";
            }else{
                $url .= "//";
            }

            $url .= $setting["bucket"] . "." . $setting["endpoint"] . ".aliyuncs.com/" . trim($image,"/");
            return $url;
        }

        $default = $domain . "/static/images/avatar.png";
        $file = trim($image,"/");
        if(empty($image) || !file_exists($file)){
            return $default;
        }

        return $domain . '/' . $file;
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
        return isset($arr[$status]) ? $arr[$status] : "未知";
    }
}