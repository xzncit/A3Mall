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
use app\common\models\users\UsersGroup as UsersGroupModel;

class Group extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = UsersGroupModel::count();
        $result = UsersGroupModel::page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray();
        return [ "count"=>$count, "data"=>$result ];
    }

    /**
     * 详情
     * @param $id
     * @return array[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id){
        return ["data"=>UsersGroupModel::where("id",$id)->find()??[]];
    }

    /**
     * 保存数据
     * @param $data
     * @return bool
     */
    public static function save($data){
        if(UsersGroupModel::where("id",$data["id"])->count()){
            UsersGroupModel::where("id",$data["id"])->save($data);
        }else{
            UsersGroupModel::create($data);
        }

        return true;
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        return UsersGroupModel::where("id",$id)->delete();
    }

}