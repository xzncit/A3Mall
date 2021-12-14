<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\products;

use app\admin\service\Service;
use app\common\models\goods\ProductsBrand as BrandModel;

class Brand extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        return [
            "count"=>BrandModel::count(),
            "data"=>BrandModel::page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray()
        ];
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
        return [
            "data"=>BrandModel::where("id",$id)->find()??[]
        ];
    }

    /**
     * 保存数据
     * @param $data
     * @return BrandModel|bool|\think\Model
     */
    public static function save($data){
        if(BrandModel::where("id",$data["id"]??0)->count()){
            return BrandModel::where("id",$data["id"])->save($data);
        }else{
            return BrandModel::create($data);
        }
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        return BrandModel::where("id",$id)->delete();
    }

    /**
     * 更新字段值
     * @return BrandModel
     */
    public static function setFields(){
        $data = self::getFields();
        return BrandModel::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
    }

}