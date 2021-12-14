<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\api\service;
use app\common\exception\BaseException;
use app\common\service\order\Order as OrderService;
use app\common\models\users\UsersComment as UsersCommentModel;
use mall\utils\CString;
use think\facade\Config;
use app\common\library\utils\Image;

class Comments extends Service {

    /**
     * 获取商品评论
     * @param $data
     * @return array
     * @throws BaseException
     */
    public static function getList($data){
        $id = $data["id"]??"0";
        $type = OrderService::getOrderType($data["type"]=="goods"?"buy":$data["type"]);
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $condition = [
            ["o.type","=",$type],
            ["uc.goods_id","=",$id],
            ["uc.status","=",1]
        ];

        $count = UsersCommentModel::alias("uc")->join("order o","uc.order_no=o.order_no","LEFT")->join("users u","uc.user_id=u.id","LEFT")->where($condition)->count();
        $result = UsersCommentModel::alias("uc")
            ->field("uc.contents,uc.reply_content,u.avatar,uc.comment_time,u.username,u.nickname,u.mobile")
            ->join("order o","uc.order_no=o.order_no","LEFT")->join("users u","uc.user_id=u.id","LEFT")
            ->where($condition)->order("uc.id","DESC")->page($page,$size)->select()->toArray();

        $data = array_map(function ($data){
            $array = [];
            $username = !empty($data["nickname"]) ? $data["nickname"] : $data["username"];
            $array['time'] = date("Y-m-d",$data['comment_time']);
            $array['avatar'] = Image::avatar($data['avatar']);
            $array['content'] = strip_tags($data['contents']);
            $array['reply_content'] = strip_tags($data['reply_content']);

            if(!empty($username)){
                $array['username'] = CString::msubstr($username,3,false) . "***";
            }else{
                $array['username'] = preg_replace('/(1[3-9]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$data['mobile']);
            }

            return $array;
        },$result);

        $array = [ "list"=>$data??[], "page"=>$page, "total"=>0, "size"=>$size ];
        $total = ceil($count / $size);
        $array["total"] = $total;
        if($total == $page -1){
            throw new BaseException("没有数据了哦！",-1,$array);
        }

        return $array;
    }

}