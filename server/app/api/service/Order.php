<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;

use mall\basic\Users;
use mall\library\delivery\aliyun\Aliyun;
use mall\utils\Check;
use think\facade\Db;
use think\facade\Config;
use app\common\models\order\Order as OrderModel;
use app\common\models\order\OrderGoods as OrderGoodsModel;
use app\common\service\order\Order as OrderService;
use mall\utils\Tool;
use app\common\exception\BaseException;
use app\common\models\Area as AreaModel;
use app\common\models\Payment as PaymentModel;
use app\common\models\Store as StoreModel;
use app\common\models\order\OrderRefundment as OrderRefundmentModel;

class Order extends Service {

    /**
     * 订单列表条件
     * @param $type
     * @return string
     */
    public static function getCondition($type){
        $condition = 'user_id=' . Users::get("id") . ' and ';
        switch($type){
            case 1: // 待付款
                $condition .= 'status=1 and pay_status=0';
                break;
            case 2: // 待发货
                $condition .= 'status=2 and pay_status=1 and distribution_status=0';
                break;
            case 3: // 待收货
                $condition .= 'status=2 and pay_status=1 and distribution_status in(1,2)';
                break;
            case 4: // 待评价
                $condition .= 'status=5 and pay_status=1 and delivery_status=1 and evaluate_status in(0,2)';
                break;
            case 5: // 己完成
                $condition .= 'status=5 and pay_status=1 and delivery_status=1 and evaluate_status=1';
                break;
        }

        return $condition;
    }

    /**
     * 获取订单列表数据
     * @param $data
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $condition = self::getCondition($data["type"]??1);
        $count = OrderModel::where($condition)->count();
        $result = OrderModel::where($condition)->order("id","DESC")->page($page,$size)->select()->toArray();

        $array = [ "list"=>[], "page"=>$page, "total"=>0, "size"=>$size ];
        $total = ceil($count / $size);
        $array["total"] = $total;
        if($total == $page -1){
            throw new BaseException("没有数据了哦！",-1,$array);
        }

        $list = [];
        foreach($result as $key=>$value){
            $list[$key] = [
                "order_id"      => $value["id"],
                "shipping"      => $value["shipping_type"],
                "order_no"      => $value["order_no"],
                "type"          => OrderService::getOrderTypeText($value["type"],1),
                "pay_status"    => OrderService::getPaymentStatusText($value["pay_status"]),
                "order_status"  => OrderService::getStatusText(OrderService::getStatus($value)),
                "order_amount"  => $value["order_amount"],
                "create_time"   => $value["create_time"],
                "active"        => OrderService::getStatus($value)
            ];

            $goods = OrderGoodsModel::where("order_id",$value["id"])->select()->toArray();
            foreach($goods as $k=>$v){
                $goods_array        = json_decode($v["goods_array"],true);
                $list[$key]['item'][$k] = [
                    "title"         => $goods_array["title"],
                    "spec"          => $goods_array["spec"],
                    "thumb_image"   => Tool::thumb($v["thumb_image"],"medium",true),
                    "nums"          => $v["goods_nums"],
                    "price"         => $v["sell_price"]
                ];
            }
        }

        $array["list"] = $list;
        return $array;
    }

    public static function detail($id){
        if(!$order = OrderModel::where([ "user_id"=>Users::get("id"),"id"=>$id ])->find()){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        $order["activity_id"]       = $order["activity_id"];
        $order["activity_type"]     = $order["type"];
        $order["active"]            = OrderService::getOrderActive($order);
        $order["create_time"]       = $order["create_time"];
        $order["type"]              = OrderService::getOrderTypeText($order["type"]);
        $order["pay_status"]        = OrderService::getPaymentStatusText($order["pay_status"]);
        $order["pay_type"]          = PaymentModel::where(["id"=>$order["pay_type"]])->value("name");
        $order["region"]            = AreaModel::getArea([$order['province'],$order['city'],$order['area']],' ');

        $order_goods = OrderGoodsModel::where([ "order_id"=>$id ])->select()->toArray();
        $fictitiousArray = [];
        $orderItem = [];
        foreach($order_goods as $key=>$value){
            $goods_array = json_decode($value["goods_array"],true);
            $fictitious_array = json_decode($value["fictitious_array"],true);
            if(!empty($fictitious_array) && in_array($order["status"],["2","5"]) && in_array($order["distribution_status"],[1,2])){
                if($fictitious_array["goods_type"] == 1){
                    $fictitious_array["value"] = $fictitious_array["card"];
                }else if($fictitious_array["goods_type"] == 3){
                    $fictitious_array["value"] = getDomain(). "/" . trim($fictitious_array["value"],"/");
                }

                $fictitiousArray[] = [
                    "type"      => $fictitious_array["goods_type"],
                    "title"     => $goods_array["title"],
                    "content"   => $fictitious_array["value"]
                ];
            }

            $orderItem[$key] = [
                "title"         => $goods_array["title"],
                "spec"          => !empty($goods_array["spec"]) ? $goods_array["spec"] : "",
                "goods_id"      => $value["goods_id"],
                "goods_no"      => $value["goods_no"],
                "thumb_image"   => Tool::thumb($value["thumb_image"],"medium",true),
                "sell_price"    => $value["sell_price"],
                "nums"          => $value["goods_nums"]
            ];
        }

        $order["item"] = $orderItem;

        $data = [
            "activity_id"           => $order["activity_id"],
            "activity_type"         => $order["activity_type"],
            "accept_name"           => $order["accept_name"],
            "mobile"                => $order["mobile"],
            "region"                => $order["region"],
            "address"               => $order["address"],
            "order_no"              => $order["order_no"],
            "create_time"           => $order["create_time"],
            "type"                  => $order["type"],
            "pay_status"            => $order["pay_status"],
            "order_status"          => OrderService::getStatus($order),
            "pay_type"              => $order["pay_type"],
            "payable_freight"       => Tool::moneyPrefix($order["payable_freight"]),
            "order_amount"          => Tool::moneyPrefix($order["order_amount"]),
            "promotions"            => Tool::moneyPrefix($order["promotions"]),
            "real_amount"           => Tool::moneyPrefix($order["real_amount"]),
            "payable_amount"        => Tool::moneyPrefix($order["payable_amount"]),
            "item"                  => $order["item"],
            "active"                => $order["active"],
            "users_price"           => Users::get("amount"),
            "shipping_type"         => $order["shipping_type"],
            "goods_info"            => $fictitiousArray
        ];

        if($order["shipping_type"] == 2){
            $data['qrcode']         = (string)url("qrcode",["data"=>$order["shipping_code"]],false,true);
            $array                  = [];
            $array[]                = substr($order["shipping_code"],0,4);
            $array[]                = substr($order["shipping_code"],4,4);
            $array[]                = substr($order["shipping_code"],8);
            $data['code']           = implode(' ',$array);
            $store                  = StoreModel::where("id",$order["store_id"])->find();
            $data["area_name"]      = AreaModel::getArea([$store["province"],$store["city"],$store["area"]]," ");
            $data["shop_name"]      = $store["shop_name"];
            $data["phone"]          = $store["phone"];
            $data["shop_address"]   = $store["address"];
            $data["day_time"]       = $store["day_time"];
        }

        return $data;
    }

    /**
     * 退款
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function refund($id){
        if(!$order = Db::name("order")->where([ "user_id"=>Users::get("id"),"id"=>$id ])->find()){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        if(!in_array($order["status"],[2,6,7])){
            throw new \Exception("该订单不允许此操作",0);
        }

        $order_goods = OrderGoodsModel::where([ "order_id"=>$id ])->select()->toArray();
        $refundment = OrderRefundmentModel::where("order_id",$id)->find();
        if(!empty($refundment["order_goods_id"])){
            $array = explode(",",$refundment["order_goods_id"]);
        }else{
            $array = [];
        }

        foreach($order_goods as $key=>$value){
            $goods_array = json_decode($value["goods_array"],true);
            $order["item"][$key] = [
                "title"         => $goods_array["title"],
                "spec"          => !empty($goods_array["spec"]) ? $goods_array["spec"] : "",
                "goods_id"      => $value["goods_id"],
                "goods_no"      => $value["goods_no"],
                "thumb_image"   => Tool::thumb($value["thumb_image"],"medium",true),
                "sell_price"    => $value["sell_price"],
                "nums"          => $value["goods_nums"]
            ];

            $order["item"][$key]["is_refund"] = (!empty($array) && in_array($value["goods_id"],$array)) ? 1 : 0;
        }

        return [
            "payable_freight"   => Tool::moneyPrefix($order["payable_freight"]),
            "order_amount"      => Tool::moneyPrefix($order["order_amount"]),
            "promotions"        => Tool::moneyPrefix($order["promotions"]),
            "real_amount"       => Tool::moneyPrefix($order["real_amount"]),
            "payable_amount"    => Tool::moneyPrefix($order["payable_amount"]),
            "is_refund"         => !empty($refundment),
            "refund_msg"        => !empty($refundment["dispose_idea"]) ? $refundment["dispose_idea"] : "",
            "order_status"      => !empty($refundment) ? $refundment["pay_status"] : '0',
            "item"              => $order["item"]
        ];
    }

    /**
     * 提交退款申请
     * @param $params
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function applyRefund($params){
        $id      = intval($params["id"]??0);
        $message = strip_tags($params["message"]??"");

        if(empty($message)){
            throw new \Exception("请填写退款说明",0);
        }else if(Check::strlen($message) > 200){
            throw new \Exception("退款说明，请控制在200字符内",0);
        }

        if(!$order = OrderModel::where([ "user_id"=>Users::get("id"),"id"=>$id ])->find()){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        OrderService::refundmentApply($order,$message);
        return true;
    }

    /**
     * 确认收货详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function delivery($id){
        if(!$order = Db::name("order")->where([ "user_id"=>Users::get("id"),"id"=>$id ])->find()){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        if(!in_array($order["status"],[2,6,7])){
            throw new \Exception("该订单不允许此操作",0);
        }

        $order_goods = OrderGoodsModel::where([ "order_id"=>$id ])->select()->toArray();
        foreach($order_goods as $key=>$value){
            $goods_array = json_decode($value["goods_array"],true);
            $order["item"][$key] = [
                "title"         => $goods_array["title"],
                "spec"          => !empty($goods_array["spec"]) ? $goods_array["spec"] : "",
                "goods_id"      => $value["goods_id"],
                "goods_no"      => $value["goods_no"],
                "thumb_image"   => Tool::thumb($value["thumb_image"],"medium",true),
                "sell_price"    => $value["sell_price"],
                "nums"          => $value["goods_nums"]
            ];
        }

        return [
            "payable_freight"   => Tool::moneyPrefix($order["payable_freight"]),
            "order_amount"      => Tool::moneyPrefix($order["order_amount"]),
            "promotions"        => Tool::moneyPrefix($order["promotions"]),
            "real_amount"       => Tool::moneyPrefix($order["real_amount"]),
            "payable_amount"    => Tool::moneyPrefix($order["payable_amount"]),
            "item"              => $order["item"]
        ];
    }

    /**
     * 确认收货
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function confirmDelivery($id){
        if(!$order = Db::name("order")->where([ "user_id"=>Users::get("id"),"id"=>$id ])->find()){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        if(!in_array($order["status"],[2,6,7])){
            throw new \Exception("该订单不允许此操作",0);
        }

        try{
            Db::startTrans();
            Db::name("order")->where(['id'=>$id])->update([ 'status' => 5, 'delivery_status'=>1, 'completion_time' => time() ]);
            OrderService::complete($order["order_no"]);
            Db::commit();
            return true;
        }catch (\Exception $ex){
            Db::rollback();
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 评价详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function evaluate($id){
        if(!$order = Db::name("order")->where([ "user_id"=>Users::get("id"), "id"=>$id ])->find()){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        if($order["status"] != 5){
            throw new \Exception("该订单不允许此操作",0);
        }else if($order["evaluate_status"] == 1){
            throw new \Exception("该订单己评价",2);
        }

        $order_goods = OrderGoodsModel::where([ "order_id"=>$id ])->select()->toArray();
        foreach($order_goods as $key=>$value){
            $goods_array = json_decode($value["goods_array"],true);
            $order["item"][$key] = [
                "title"         => $goods_array["title"],
                "spec"          => !empty($goods_array["spec"]) ? $goods_array["spec"] : "",
                "goods_id"      => $value["goods_id"],
                "goods_no"      => $value["goods_no"],
                "thumb_image"   => Tool::thumb($value["thumb_image"],"medium",true),
                "sell_price"    => $value["sell_price"],
                "nums"          => $value["goods_nums"]
            ];
        }

        return [
            "payable_freight"   => Tool::moneyPrefix($order["payable_freight"]),
            "order_amount"      => Tool::moneyPrefix($order["order_amount"]),
            "promotions"        => Tool::moneyPrefix($order["promotions"]),
            "real_amount"       => Tool::moneyPrefix($order["real_amount"]),
            "payable_amount"    => Tool::moneyPrefix($order["payable_amount"]),
            "item"              => $order["item"]
        ];
    }

    /**
     * 提交评价
     * @param array $params
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function applyEvaluate($params=[]){
        $id         = intval($params["id"]??0);
        $message    = trim(strip_tags($params["message"]??""));
        $rate       = intval($params["rate"]??5);

        if(!$order = Db::name("order")->where([ "user_id"=>Users::get("id"),"id"=>$id ])->find()){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        if($order["evaluate_status"] == 1){
            throw new \Exception("您的订单己评价！",0);
        }

        try{
            Db::startTrans();
            $comment = Db::name("users_comment")->where([ "user_id"=>Users::get("id"), "order_no"=>$order["order_no"], "status"=>0 ])->select()->toArray();
            foreach($comment as $value){
                Db::name("users_comment")->where('id',$value["id"])->update([
                    "status"=>1,
                    "contents"=>$message,
                    "point"=>$rate,
                    "comment_time"=>time()
                ]);
            }

            Db::name("order")->where([ "user_id"=>Users::get("id"),"id"=>$id ])->update([ "evaluate_status"=>1 ]);
            Db::commit();
            return true;
        }catch (\Exception $ex){
            Db::rollback();
            throw new \Exception("服务器繁忙，请稍后在试",$ex->getCode());
        }
    }

    /**
     * 取消订单
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function cancel($id){
        $condition = ["user_id"=>Users::get("id"),"id"=>$id];
        if(!$order=Db::name("order")->where($condition)->find()){
            throw new \Exception("您要操作的订单不存在！",0);
        }

        if($order["status"] == 1){
            Db::name("order")->where($condition)->update([ "status"=>3 ]);
            Db::name("order_log")->insert([
                'order_id' => $id,
                'username' => Users::get("username"),
                'action' => "取消订单",
                'result' => '成功',
                'note' => "订单【{$order["order_no"]}】客户取消订单",
                'create_time' => time()
            ]);

            OrderService::updateOrderGroupStatus($order,3);
            return true;
        }

        if(in_array($order["status"],[3,4])){
            throw new \Exception("非法操作",0);
        }

        throw new \Exception("您的订单己付款，不允许此操作",0);
    }

    /**
     * 售后列表
     * @param $data
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function service($data){
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $condition = ["r.user_id"=>Users::get("id")];
        $count = OrderRefundmentModel::alias("r")->join("order o","r.order_id=o.id","LEFT")->where(["r.user_id"=>Users::get("id")])->count();
        $result = OrderRefundmentModel::alias("r")->field("o.*,r.amount,r.pay_status as r_pay_status,r.create_time as r_create_time")->join("order o","r.order_id=o.id","LEFT")->where($condition)->page($page,$size)->select()->toArray();

        $array = [ "list"=>[], "page"=>$page, "total"=>0, "size"=>$size ];
        $total = ceil($count / $size);
        $array["total"] = $total;
        if($total == $page -1){
            throw new BaseException("没有数据了哦！",-1,$array);
        }

        $list = [];
        foreach($result as $key=>$value){
            $list[$key] = [
                "order_id"          => $value["id"],
                "order_no"          => $value["order_no"],
                "type"              => OrderService::getOrderTypeText($value["type"],1),
                "pay_status"        => OrderService::getRefundmentText($value["r_pay_status"]),
                "order_status"      => OrderService::getStatusText(OrderService::getStatus($value)),
                "order_amount"      => $value["order_amount"],
                "create_time"       => date("Y-m-d H:i:s",$value["r_create_time"]),
                "active"            => OrderService::getOrderActive($value)
            ];

            $goods = Db::name("order_goods")->where("order_id",$value["id"])->select()->toArray();
            foreach($goods as $k=>$v){
                $goods_array        = json_decode($v["goods_array"],true);
                $list[$key]['item'][$k] = [
                    "title"         => $goods_array["title"],
                    "spec"          => $goods_array["spec"],
                    "thumb_image"   => Tool::thumb($v["thumb_image"],"medium",true),
                    "nums"          => $v["goods_nums"],
                    "price"         => $v["sell_price"]
                ];
            }
        }

        $array["list"] = $list;
        return $array;
    }

    /**
     * 获取物流信息
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getExpressData($id){
        if(!$order = Db::name("order")->where(["user_id"=>Users::get("id"),"id"=>$id])->where("distribution_status","in","1,2")->find()){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        $orderDelivery = Db::name("order_delivery")->where("order_id",$id)->find();
        if(empty($orderDelivery)){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        $type = strtolower(Db::name("freight")->where("id",$orderDelivery["freight_id"])->value("type"));
        if($type == 'sfexpress'){
            $orderDelivery["distribution_code"] = $orderDelivery["distribution_code"] . ":" . substr($orderDelivery["mobile"],-4);
        }

        $order["region"] = AreaModel::getArea([$order['province'],$order['city'],$order['area']],' ');

        $express = ["expName"=>"", "number"=>"", "takeTime"=>"", "updateTime"=>""];
        try{
            $express = Aliyun::query($orderDelivery["distribution_code"],$type);
        }catch(\Exception $ex){
            $express["list"][] = [
                "status" => "商家正在通知快递公司",
                "time"   => date("Y-m-d H:i:s",$order["send_time"])
            ];
        }

        return [
            "accept_name"   => $order["accept_name"],
            "mobile"        => $order["mobile"],
            "region"        => $order["region"],
            "address"       => $order["address"],
            "order_no"      => $order["order_no"],
            "express"       => $express
        ];
    }

    /**
     * 获取订单信息
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getOrderInfo($id){
        if(!$row=Db::name("order")->where(["id"=>$id,"user_id"=>Users::get("id")])->find()){
            throw new \Exception("订单不存在",0);
        }

        return [
            "order_id"          => $row["id"],
            "order_no"          => $row["order_no"],
            "create_time"       => $row["create_time"],
            "order_amount"      => number_format($row["order_amount"],2),
            "order_status"      => OrderService::getPaymentStatusText($row["pay_status"]),
            "payment_type"      => PaymentModel::where("id",$row["pay_type"])->value("name"),
            "users_price"       => Db::name("users")->where("id",Users::get("id"))->value("amount")
        ];
    }

}