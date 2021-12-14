<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\promotion;

use app\admin\service\Service;
use app\admin\model\promotion\PromotionBonus as PromotionBonusModel;
use app\common\models\users\UsersBonus as UsersBonusModel;

/**
 * 优惠劵服务类
 * Class Bonus
 * @package app\admin\service\promotion
 */
class Bonus extends Service {

    /**
     * 列表
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = PromotionBonusModel::withSearch("name",['name'=>$data['key']["title"]??''])->count();
        $result = PromotionBonusModel::withSearch("name",['name'=>$data['key']["title"]??''])->page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray();

        foreach($result as $key=>$item){
            $result[$key]['total'] = $item["used"] . ' / ' . $item["giveout"];
            $result[$key]['time'] = $item["start_time"] . ' ~ ' . $item["end_time"];
        }

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
        $row = PromotionBonusModel::where("id",$id)->find();
        return [ "data"=>$row??[] ];
    }

    /**
     * 保存数据
     * @param $data
     * @return PromotionBonusModel|bool|\think\Model
     * @throws \Exception
     */
    public static function save($data){
        $data["start_time"] = strtotime($data["start_time"]);
        $data["end_time"] = strtotime($data["end_time"]);
        if($data["start_time"] > $data["end_time"]){
            throw new \Exception("开始时间不能小于结束时间",0);
        }

        if(PromotionBonusModel::where("id",$data["id"])->count()){
            return PromotionBonusModel::where("id",$data["id"])->save($data);
        }

        return PromotionBonusModel::create($data);
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        if(PromotionBonusModel::where("id",$id)->delete()){
            return UsersBonusModel::where("bonus_id",$id)->delete();
        }

        return false;
    }

    /**
     * 更新字段值
     * @return PromotionBonusModel
     */
    public static function setFields(){
        $data = self::getFields();
        return PromotionBonusModel::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
    }

}