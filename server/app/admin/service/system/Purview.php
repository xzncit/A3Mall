<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\system;

use app\admin\service\Service;
use app\common\models\system\Manage as ManageModel;
use app\common\models\system\Purview as PurviewModel;

class Purview extends Service {

    /**
     * 列表
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = ManageModel::count();
        $list = ManageModel::page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray();

        return [ "count"=>$count,"data"=>$list ];
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
        $row = ManageModel::where("id",$id)->find();
        if(isset($row["lock"]) && $row["lock"] == 1) {
            throw new \Exception("该分组已被锁定，不允许修改",0);
        }

        $systemMenu = PurviewModel::where(['status'=>0,"pid"=>0])->select()->toArray();
        foreach($systemMenu as $key=>$item){
            $children = PurviewModel::where(['status'=>0,"pid"=>$item["id"]])->select()->toArray();
            foreach($children as $k=>$v){
                $children[$k]["children"] = PurviewModel::where(['status'=>0,"pid"=>$v["id"]])->select()->toArray();
            }

            $systemMenu[$key]["children"] = $children;
        }

        return [ "group"=>$systemMenu, "data"=>$row ];
    }

    /**
     * 保存数据
     * @param $data
     * @return bool
     */
    public static function save($data){
        $data["purview"] = isset($data["purview"]) && is_array($data["purview"]) ? json_encode($data["purview"],JSON_UNESCAPED_UNICODE) : $data["purview"];
        if(ManageModel::where("id",$data["id"])->count()){
            ManageModel::where("id",$data["id"])->save($data);
        }else{
            ManageModel::create($data);
        }

        return true;
    }

    /**
     * 删除
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function delete($id){
        $row = ManageModel::where('id',$id)->find();

        if(empty($row)){
            throw new \Exception("您要查找的数据不存在",0);
        }

        if($row["lock"] == 1){
            throw new \Exception("该权限为系统权限，不允许删除。",0);
        }

        return ManageModel::where('id',$id)->delete();
    }

    /**
     * 更新字段值
     * @return ManageModel
     */
    public static function setFields(){
        $data = self::getFields();

        $row = ManageModel::where('id',$data["id"])->find();
        if(empty($row)){
            throw new \Exception("您要查找的数据不存在！",0);
        }

        if($row["lock"] == 1){
            throw new \Exception("该权限为系统权限，不允许更改。",0);
        }

        return ManageModel::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
    }

}