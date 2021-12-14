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
use app\common\models\goods\Deliver as DeliverModel;
use app\common\models\Area as AreaModel;

class Deliver extends Service {

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
            "count"=>DeliverModel::count(),
            "data"=>DeliverModel::page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray()
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
        $row = DeliverModel::where("id",$id)->find();
        $province = AreaModel::where(['pid' => 0])->select()->toArray();

        $city = $area = [];
        if(!empty($row["province"])){
            $city = AreaModel::where(['pid' => $row["province"]])->select()->toArray();
        }

        if(!empty($row["city"])){
            $area = AreaModel::where(['pid' => $row["city"]])->select()->toArray();
        }

        return [ "province"=>$province, "city"=>$city, "area"=>$area, "data"=>$row??[] ];
    }

    /**
     * 保存数据
     * @param $data
     * @return DeliverModel|bool|\think\Model
     */
    public static function save($data){
        $data["is_default"] = isset($data["is_default"]) && is_numeric($data["is_default"]) ? $data["is_default"] : 0;
        if($data["is_default"] == 1){
            DeliverModel::where("1=1")->update(["is_default" => 0]);
        }

        if(DeliverModel::where("id",$data["id"])->count()){
            return DeliverModel::where("id",$data["id"])->save($data);
        }else{
            return DeliverModel::create($data);
        }
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        return DeliverModel::where("id",$id)->delete();
    }

}