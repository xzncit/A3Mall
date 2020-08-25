<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\basic;

use mall\library\wechat\chat\WeChat;
use mall\utils\CString;
use think\facade\Db;
use think\facade\Request;

class Users {

    private static $data = [];

    public static function get($field=null){
        return !is_null($field) && isset(self::$data[$field]) ? self::$data[$field] : self::$data;
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
            ->where("o.type",$type)->where("uc.goods_id",$id)
            ->where("uc.status",1)->count();

        $total = ceil($count / $size);
        if($total == $page -1){
            throw new \Exception("没有数据了哦！",-1);
        }

        $result = Db::name("users_comment")->alias("uc")
            ->field("uc.contents,u.avatar,uc.comment_time,u.username,u.nickname,u.mobile")
            ->join("order o","uc.order_no=o.order_no","LEFT")
            ->join("users u","uc.user_id=u.id","LEFT")
            ->where("o.type",$type)->where("uc.goods_id",$id)
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
        $domain = $root ? trim(Request::domain(),"/") : "";
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