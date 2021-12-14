<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\users;

use app\admin\model\users\UsersSuggest as UsersSuggestModel;
use app\admin\service\Service;
use app\common\models\users\Users as UsersModel;
use think\facade\Session;

class Suggest extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $condition = [];
        $key = $data["key"]??[];
        if(isset($key["cat_id"]) && $key["cat_id"] != '-1'){
            $filed = $key["cat_id"] == 0 ? "users.username" : "goods.title";
            $condition[] = [$filed,"like",'%'.$key["title"].'%'];
        }

        $count = UsersSuggestModel::withJoin(['users'])->where($condition)->count();
        $result = UsersSuggestModel::withJoin(['users'])->where($condition)->page($data["page"]??1,$data["limit"]??10)->order("users_suggest.id","desc")->select()->toArray();
        return [ "count"=>$count, "data"=>array_map(function ($res){
            $res["username"] = getUserName($res);
            return $res;
        },$result) ];
    }

    /**
     * 详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id){
        if(!$row=UsersSuggestModel::where("id",$id)->find()){
            throw new \Exception("您要查找的内容不存在！");
        }

        $users = UsersModel::where("id",$row["user_id"])->find();
        $row["username"] = getUserName($users);
        return [ "data"=>$row ];
    }

    /**
     * 保存数据
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function save($data){
        if(empty($data["reply_content"])){
            throw new \Exception("请填写回复内容",0);
        }

        $data["admin_id"] = Session::get("system_user_id");
        $data["reply_time"] = time();
        $data["status"] = 1;

        UsersSuggestModel::where("id",$data["id"])->save($data);
        return true;
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        return UsersSuggestModel::where("id",$id)->delete();
    }

}