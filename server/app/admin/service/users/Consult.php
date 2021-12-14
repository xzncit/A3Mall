<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\users;

use app\admin\service\Service;
use app\common\models\goods\Goods as GoodsModel;
use app\common\models\users\Users as UsersModel;
use app\admin\model\users\UsersConsult as UsersConsultModel;
use think\facade\Session;

class Consult extends Service {

    /**
     * 列表
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $condition = [];
        $condition[] = ["pid","=",0];
        $key = $data["key"]??[];
        if(isset($key["cat_id"]) && $key["cat_id"] != '-1'){
            $filed = $key["cat_id"] == 0 ? "users.username" : "goods.title";
            $condition[] = [$filed,"like",'%'.$key["title"].'%'];
        }

        $count = UsersConsultModel::withJoin(['goods','users'])->where($condition)->count();
        $result = UsersConsultModel::withJoin(['goods','users'])->where($condition)->page($data["page"]??1,$data["limit"]??10)->order("users_consult.id","desc")->select()->toArray();
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
        if(!$row=UsersConsultModel::where("id",$id)->find()){
            throw new \Exception("您要查找的内容不存在！");
        }

        $row["goods_name"] = GoodsModel::where("id",$row["goods_id"])->value("title");
        $users = UsersModel::where("id",$row["user_id"])->find();
        $row["username"] = getUserName($users);

        $row["children"] = UsersConsultModel::where(["pid"=>$row["id"]])->select()->toArray();

        foreach($row["children"] as $key=>$value){
            $row["children"][$key]["username"] = $value["user_id"] == 0 ? "管理回复" : "会员回复";
        }

        return [ "data"=>$row ];
    }

    /**
     * 保存数据
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function save($data){
        if(empty($data["content"])){
            throw new \Exception("请填写回复内容",0);
        }

        $data["admin_id"] = Session::get("system_user_id");
        $data["reply_time"] = time();
        $data["status"] = 1;

        if($obj=UsersConsultModel::where("id",$data["id"])->find()){
            $obj->save($data);
            $data["user_id"] = $obj["id"];
            $data["goods_id"] = $obj["id"];
        }

        $data["pid"] = $data["id"];
        unset($data["id"]);
        UsersConsultModel::create($data);
        return true;
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        if(UsersConsultModel::where("id",$id)->delete()){
            return UsersConsultModel::where("pid",$id)->delete();
        }

        return false;
    }

}