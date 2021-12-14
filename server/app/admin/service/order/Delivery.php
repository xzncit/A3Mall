<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\order;

use app\admin\service\Service;
use app\admin\model\order\OrderDelivery as OrderDeliveryModel;
use app\common\models\Area as AreaModel;
use app\common\models\order\OrderGoods as OrderGoodsModel;
use app\common\models\system\Users as UsersModel;

class Delivery extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $condition = [];
        $key = $data["key"]??[];
        $arr = ["order.order_no","users.username"];
        if((isset($key["type"]) && isset($arr[$key["type"]])) && !empty($key["title"])){
            $condition[] = [$arr[$key["type"]],"like",'%'.$key["title"].'%'];
        }

        $count = OrderDeliveryModel::withJoin(["order","users","orderFreight"])->where($condition)->count();
        $result = array_map(function ($res){
            $res["username"] = getUserName($res);
            return $res;
        },OrderDeliveryModel::withJoin(["order","users","orderFreight"])->where($condition)->order("id","desc")->page($data["page"]??1,$data["limit"]??10)->select()->toArray());

        return ["count"=>$count, "data"=>$result];
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
        $data = OrderDeliveryModel::alias("c")
            ->field('c.id as id,c.user_id,c.admin_id,o.order_no,c.order_id,d.title as pname,o.create_time as order_create_time,u.username,c.name,c.province,c.city,c.area,c.address,c.mobile,c.phone,c.zip,c.freight,c.distribution_code,c.create_time,c.note')
            ->join("order o","c.order_id=o.id","LEFT")
            ->join("users u","u.id=c.user_id","LEFT")
            ->join("distribution d","c.distribution_id=d.id","LEFT")->where('c.id',$id)->find();

        if(empty($data)){
            throw new \Exception("您要查找的内容不存在！");
        }

        $data["area_name"] = AreaModel::getArea([$data['province'], $data['city'], $data['area']],",");
        $data["order_create_time"] = date("Y-m-d H:i:s",$data['order_create_time']);
        $data["username"] = getUserName($data);

        if($data["admin_id"] == "-1"){
            $data['admin_name'] = 'system';
        }else{
            $data['admin_name'] = UsersModel::where(["id"=>$data["admin_id"]])->value("username");
        }

        $orderGoods= OrderGoodsModel::where(["order_id" => $data["order_id"]])->order("id","DESC")->select()->toArray();
        foreach($orderGoods as $key=>$item){
            $orderGoods[$key]["goods_array"] = "";
            if(!empty($item["goods_array"])){
                $orderGoods[$key]["goods_array"] = json_decode($item["goods_array"],true);
            }

            $orderGoods[$key]["order_price"] = number_format($item["goods_nums"]*$item["sell_price"],2);
        }

        $data["goods"] = $orderGoods;
        return [ "data"=>$data ];
    }

}