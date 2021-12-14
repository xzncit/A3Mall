<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\platform;

use app\admin\service\Service;
use app\common\models\Navigation as NavigationModel;

class Navigation extends Service {

    public static function getList($data){
        return [
            "count"=>NavigationModel::count(),
            "data"=>NavigationModel::page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray()
        ];
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
        return [
            "data"=>NavigationModel::where("id",$id)->find()
        ];
    }

    /**
     * 保存数据
     * @param array $data
     * @return NavigationModel|bool|\think\Model
     */
    public static function save($data=[]){
        if(NavigationModel::where("id",$data["id"])->count()){
            return NavigationModel::where("id",$data["id"])->save($data);
        }else{
            return NavigationModel::create($data);
        }
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        return NavigationModel::where("id",$id)->delete();
    }

    /**
     * 更新字段值
     * @return NavigationModel
     */
    public static function setFields(){
        $data = self::getFields();
        return NavigationModel::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
    }

}