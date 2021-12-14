<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\order;

use app\admin\service\Service;
use app\admin\model\users\UsersRechange as UsersRechangeModel;

class Recharge extends Service {

    public static function getList($data){
        $condition = [];
        $key = $data["key"]??[];
        $arr = ["users_rechange.order_no","users.username"];
        if((isset($key["type"]) && isset($arr[$key["type"]])) && !empty($key["title"])){
            $condition[] = [$arr[$key["type"]],"like",'%'.$key["title"].'%'];
        }

        $count = UsersRechangeModel::withJoin(["users"])->where($condition)->count();
        $result = array_map(function ($res){
            $res["username"] = getUserName($res);
            return $res;
        },UsersRechangeModel::withJoin(["users"])->where($condition)->order("id","desc")->page($data["page"]??1,$data["limit"]??10)->select()->toArray());

        return ["count"=>$count, "data"=>$result];
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        return UsersRechangeModel::where("id",$id)->delete();
    }

}