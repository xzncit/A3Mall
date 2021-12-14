<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\promotion;

use app\common\service\promotion\Order as OrderService;
use app\admin\model\promotion\PromotionOrder as PromotionOrderModel;

class Order extends OrderService {

    /**
     * 列表
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = PromotionOrderModel::withSearch("name",['name'=>$data['key']["title"]??''])->count();
        $result = PromotionOrderModel::withSearch("name",['name'=>$data['key']["title"]??''])->page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray();

        foreach($result as $key=>$item){
            $result[$key]['type'] = self::getActivityType($item["type"]);
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
        return [
            "type"=>self::getActivityType(),
            "data"=>PromotionOrderModel::where("id",$id)->find()??[]
        ];
    }

    public static function save($data){
        $data["start_time"] = strtotime($data["start_time"]);
        $data["end_time"] = strtotime($data["end_time"]);

        if($data["start_time"] > $data["end_time"]){
            throw new \Exception("开始时间不能小于结束时间",0);
        }

        if(PromotionOrderModel::where('id',$data["id"])->count()){
            PromotionOrderModel::where('id',$data["id"])->save($data);
        }else{
            PromotionOrderModel::create($data);
        }
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        return PromotionOrderModel::where("id",$id)->delete();
    }

}