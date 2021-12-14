<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\statistics;

use app\admin\service\Service;
use app\common\models\users\Users as UsersModel;
use app\admin\model\Order as OrderModel;
use app\admin\model\order\OrderGoods as OrderGoodsModel;
use mall\utils\Tool;

class Sale extends Service {

    /**
     * 获取销售排行数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSalesRanking(){
        // 取得会员总数
        $users_count = UsersModel::count();

        // 购买过订单的会员数
        $order_count = OrderModel::field("COUNT(DISTINCT user_id) as count")->where("user_id > 0 AND status=5")->find();

        // 会员订单总数和订单总购物额
        $order = OrderModel::field("COUNT(*) AS order_num,SUM(order_amount) as order_amount_total")->where("user_id > 0 AND status=5")->find();

        // 注册后还未购买过商品的会员
        $users_goods_count = UsersModel::field("COUNT(DISTINCT o.user_id) as count")->alias("u")->join("order o","o.user_id=u.id","LEFT")->where("o.user_id > 0 AND o.status=5")->find();
        $not_goods_users_count = $users_count - $users_goods_count["count"];

        // 注册会员购买率
        $cart = sprintf("%0.2f", ($users_count > 0 ? $not_goods_users_count / $users_count : 0) * 100);

        // 订单排行
        $fields = "SUM(o.order_amount) as total,COUNT(o.id) as count,u.username";
        $order_hot = OrderModel::field($fields)
            ->alias("o")
            ->join("users u","o.user_id=u.id","LEFT")
            ->where("o.user_id > 0 AND o.status=5")
            ->group("o.user_id")->order("total DESC")
            ->limit(10)->select()->toArray();

        $i=1;
        foreach($order_hot as $k=>$v){
            $order_hot[$k]['p'] = $i++;
        }

        return [
            "a"=>$cart,
            "b"=>$users_count,
            "c"=>$not_goods_users_count,
            "d"=>$users_goods_count,
            "e"=>empty($order["order_amount_total"]) ? "0.00" : $order["order_amount_total"],
            "f"=>empty($order["order_amount_total"]) ? "0.00" : sprintf("%.2f",($order["order_amount_total"] > 0 && $users_goods_count["count"] > 0 ? $order["order_amount_total"]/$users_goods_count["count"] : 0)),
            "g"=>$order_hot
        ];
    }

    /**
     * 获取购买排行数据
     * @param $data
     * @return array
     * @throws \Exception
     */
    public static function getRankingData($data){
        $start_time = !empty($data["key"]["start_time"]) ? strtotime($data["key"]["start_time"]) : "";
        $end_time = !empty($data["key"]["end_time"]) ? strtotime($data["key"]["end_time"]) : "";

        if(!empty($start_time) && ($start_time > $end_time)){
            throw new \Exception("开始时间不能大于结束时间！",0);
        }

        $condition = "order.status=5";
        if(!empty($start_time)){
            $condition .= " AND (order.create_time >= '{$start_time}' AND order.create_time <= '{$end_time}')";
        }

        $fields = "SUM(order.order_amount) as total,COUNT(order.id) as count";
        $count = OrderModel::withJoin("users")->field($fields)->where($condition)->group("order.user_id")->order("total DESC")->count();
        $result = OrderModel::withJoin("users")->field($fields)->where($condition)->group("order.user_id")->order("total","DESC")->page($data["page"]??1,$data["limit"]??10)->select()->toArray();

        $list = array_map(function ($res){
            $res["username"] = getUserName($res);
            return $res;
        },$result);

        return [ "count"=>$count, "data"=>$list ];
    }

    /**
     * 获取销售明细数据
     * @param $data
     * @return array
     * @throws \Exception
     */
    public static function getSaleList($data){
        $start_time = !empty($data["key"]["start_time"]) ? strtotime($data["key"]["start_time"]) : "";
        $end_time = !empty($data["key"]["end_time"]) ? strtotime($data["key"]["end_time"]) : "";

        if(!empty($start_time) && ($start_time > $end_time)){
            throw new \Exception("开始时间不能大于结束时间！",1);
        }

        $condition = "order.status=5";
        if(!empty($start_time)){
            $condition .= " AND (order.create_time >= '{$start_time}' AND order.create_time <= '{$end_time}')";
        }

        $count = OrderGoodsModel::withJoin("order")->where($condition)->count();
        $data = OrderGoodsModel::withJoin("order")
            ->where($condition)->group("order.user_id")
            ->order("order_goods.goods_nums","DESC")->page($data["page"]??1,$data["limit"]??10)
            ->select()->toArray();

        $tableData = [];
        foreach($data as $key=>$value){
            $goods_array = json_decode($value["goods_array"],true);
            $tableData[$key] = [
                "goods_img"=>Tool::thumb($value["thumb_image"]),
                "goods_name"=>$goods_array["title"] . (!empty($goods_array["spec"]) ? '['.$goods_array["spec"].']' : ''),
                "order_no"=>$value["order_no"],
                "num"=>$value["goods_nums"],
                "price"=>$value["real_price"],
                "time"=>$value["create_time"]
            ];
        }

        return [ "count"=>$count, "data"=>$tableData ];
    }

    /**
     * 获取商品排行数据
     * @param $data
     * @return array
     * @throws \Exception
     */
    public static function getSaleOrder($data){
        $start_time = !empty($data["key"]["start_time"]) ? strtotime($data["key"]["start_time"]) : "";
        $end_time = !empty($data["key"]["end_time"]) ? strtotime($data["key"]["end_time"]) : "";

        if(!empty($start_time) && ($start_time > $end_time)){
            throw new \Exception("开始时间不能大于结束时间！",1);
        }

        $condition = "order.status=5";
        if(!empty($start_time)){
            $condition .= " AND (order.create_time >= '{$start_time}' AND order.create_time <= '{$end_time}')";
        }

        $count = OrderGoodsModel::withJoin("order")->where($condition)->group("order_goods.goods_id")->count();
        $data = OrderGoodsModel::withJoin("order")
            ->field("SUM(order_goods.goods_nums) as nums,SUM(order_goods.goods_nums * order_goods.real_price) as total")
            ->where($condition)->group("order.user_id")
            ->group("order_goods.goods_id")
            ->order("order_goods.goods_nums","DESC")->page($data["page"]??1,$data["limit"]??10)
            ->select()->toArray();

        $tableData = [];
        foreach($data as $key=>$value){
            $goods_array = json_decode($value["goods_array"],true);
            $tableData[$key] = [
                "goods_img"=>Tool::thumb($value["thumb_image"]),
                "goods_name"=>$goods_array["title"] . (!empty($goods_array["spec"]) ? '['.$goods_array["spec"].']' : ''),
                "goods_no"=>$value["goods_no"],
                "num"=>$value["nums"],
                "price"=>$value["total"],
                "average"=>number_format($value['nums'] ? $value['total'] / $value['nums'] : 0,2)
            ];
        }

        return [ "count"=>$count, "data"=>$tableData ];
    }

}