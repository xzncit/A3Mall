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
use app\admin\model\order\OrderRefundment as OrderRefundmentModel;
use app\common\models\order\Order as OrderModel;
use app\common\models\order\OrderGoods as OrderGoodsModel;
use app\common\models\system\Users as UsersModel;
use app\common\service\order\Order as OrderService;

class Refundment extends OrderService {

    /**
     * 查询条件
     * @param $key
     * @return array
     */
    protected static function getCondition($key){
        $condition = [];
        $arr = ["order_refundment.order_no","users.username"];
        if((isset($key["type"]) && isset($arr[$key["type"]])) && !empty($key["title"])){
            $condition[] = [$arr[$key["type"]],"like",'%'.$key["title"].'%'];
        }

        if(isset($key["order_status"])){
            if(in_array($key["order_status"],[1,2,3,4])){
                $condition = [];
                switch ($key["order_status"]){
                    case 1:
                        $condition[] = ["order_refundment.pay_status","=",0];
                        break;
                    case 2:
                        $condition[] = ["order_refundment.pay_status","=",2];
                        break;
                    case 3:
                        $condition[] = ["order_refundment.pay_status","=",1];
                        break;
                }
            }
        }

        return $condition;
    }

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $condition = self::getCondition($data["key"]??[]);

        $count = OrderRefundmentModel::withJoin(["order","users"])->where($condition)->count();
        $result = array_map(function ($res){
            $res["username"] = getUserName($res);
            $res["refundment_text"] = self::getRefundmentText($res["pay_status"]);
            $res["order_url"] = createUrl("order.index/detail",["id"=>$res["order_id"]]);
            $res["url"] = createUrl("detail",["id"=>$res["id"]]);
            return $res;
        },OrderRefundmentModel::withJoin(["order","users"])->where($condition)->order("id","desc")->page($data["page"]??1,$data["limit"]??10)->select()->toArray());

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
        $data = OrderRefundmentModel::alias("c")
            ->field('o.order_no,o.create_time as order_create_time,u.username,c.*')
            ->join("order o","c.order_id=o.id","LEFT")
            ->join("users u","u.id=c.user_id","LEFT")->where('c.id',$id)->find();

        if(empty($data)){
            throw new \Exception("您要查找的内容不存在！");
        }

        $data["username"] = getUserName($data);
        $data["order_create_time"] = date("Y-m-d H:i:s",$data['order_create_time']);
        $data["refundment_text"] = self::getRefundmentText($data["pay_status"]);

        if($data["admin_id"] == "-1"){
            $data['admin_name'] = 'system';
        }else{
            $data['admin_name'] = UsersModel::where(["id"=>$data["admin_id"]])->value("username");
        }

        $goods = OrderGoodsModel::where(["order_id" => $data["order_id"]])->order("id","DESC")->select()->toArray();
        foreach($goods as $key=>$item){
            $goods[$key]["goods_array"] = "";
            if(!empty($item["goods_array"])){
                $goods[$key]["goods_array"] = json_decode($item["goods_array"],true);
            }

            $goods[$key]["order_price"] = number_format($item["goods_nums"]*$item["sell_price"],2);
        }

        $data["goods"] = $goods;
        return [ "data"=>$data ];
    }

}