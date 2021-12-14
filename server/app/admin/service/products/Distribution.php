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
use app\common\models\goods\Distribution as DistributionModel;
use app\common\models\Area as AreaModel;

class Distribution extends Service {

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
            "count"=>DistributionModel::count(),
            "data"=>DistributionModel::page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray()
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
        $row = DistributionModel::where("id",$id)->find();

        if(!empty($row['area_group'])){
            $row["area_group"] = json_decode($row['area_group'],true);
        }

        if(!empty($row['first_price_group'])){
            $row["first_price_group"] = json_decode($row['first_price_group'],true);
        }

        if(!empty($row['second_price_group'])){
            $row["second_price_group"] = json_decode($row['second_price_group'],true);
        }

        $temp = [];
        if(!empty($row["area_group"])){
            foreach ($row["area_group"] as $key => $item) {
                $area_id = explode(",", $item);
                $arr = array();
                foreach ($area_id as $val) {
                    $arr[] = AreaModel::where(['id' => $val])->value("name");
                }

                $temp[$key]["id"] = $item;
                $temp[$key]["title"] = implode(",", $arr);
                $temp[$key]["first"] = $row["first_price_group"][$key];
                $temp[$key]["second"] = $row["second_price_group"][$key];
            }
        }

        $row["attr"] = $temp;

        return [
            "data"=>$row,
            "weight"=>[ "500"=>"500克", "1000"=>"1公斤", "1500"=>"1.5公斤", "2000"=>"2公斤", "5000"=>"5公斤", "10000"=>"10公斤", "20000"=>"20公斤", "50000"=>"50公斤" ]
        ];
    }

    /**
     * 保存数据
     * @param $data
     * @return DistributionModel|bool|\think\Model
     */
    public static function save($data){
        if(!empty($data['area_group'])){
            $data["area_group"] = json_encode($data['area_group'],JSON_UNESCAPED_UNICODE);
        }

        if(!empty($data['first_price_group'])){
            $data["first_price_group"] = json_encode($data['first_price_group'],JSON_UNESCAPED_UNICODE);
        }

        if(!empty($data['second_price_group'])){
            $data["second_price_group"] = json_encode($data['second_price_group'],JSON_UNESCAPED_UNICODE);
        }

        if(DistributionModel::where("id",$data["id"])->count()){
            return DistributionModel::where("id",$data["id"])->save($data);
        }else{
            return DistributionModel::create($data);
        }
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        return DistributionModel::where("id",$id)->delete();
    }

    /**
     * 更新字段值
     * @return DistributionModel
     */
    public static function setFields(){
        $data = self::getFields();
        return DistributionModel::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
    }
}