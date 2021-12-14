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
use app\admin\model\users\UsersTags as UsersTagsModel;

class Tags extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = UsersTagsModel::withSearch(['name'],[
            "name"=>$data["key"]["title"]??""
        ])->count();
        $result = UsersTagsModel::withSearch(['name'],[
            "name"=>$data["key"]["title"]??""
        ])->page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray();
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
        return [ "data"=>UsersTagsModel::where("id",$id)->find()??[] ];
    }

    /**
     * 保存数据
     * @param $data
     * @return bool
     */
    public static function save($data){
        if(UsersTagsModel::where("id",$data["id"])->count()){
            UsersTagsModel::where("id",$data["id"])->save($data);
        }else{
            UsersTagsModel::create($data);
        }

        return true;
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        return UsersTagsModel::where("id",$id)->delete();
    }

}