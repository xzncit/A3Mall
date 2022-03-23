<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\order;

use mall\library\delivery\aliyun\Aliyun;
use think\facade\Db;
use think\facade\Env;
use think\facade\Session;
use app\admin\model\Order as OrderModel;
use app\common\models\order\OrderCollection as OrderCollectionModel;
use app\common\models\order\OrderDelivery as OrderDeliveryModel;
use app\common\models\order\OrderRefundment as OrderRefundmentModel;
use app\common\service\order\Order as OrderService;
use app\common\models\system\Users as SystemUsersModel;
use app\common\models\order\OrderLog as OrderLogModel;
use app\common\models\Store as StoreModel;
use app\common\models\Area as AreaModel;
use app\common\models\goods\Distribution as DistributionModel;
use app\common\models\Payment as PaymentModel;
use app\common\models\order\OrderGoods as OrderGoodsModel;
use app\common\models\goods\Goods as GoodsModel;
use app\common\models\goods\GoodsItem as GoodsItemModel;
use app\common\models\users\Users as UsersModel;
use app\common\models\users\UsersGroup as UsersGroupModel;
use app\common\models\goods\Freight as FreightModel;
use app\common\models\wechat\WechatUsers as WechatUsersModel;
use app\common\service\subscribe\mini\Subscribe as SubscribeMini;
use app\common\service\subscribe\wechat\Subscribe as SubscribeWechat;

class Order extends OrderService {

    /**
     * 搜索条件
     * @param $key
     * @return array
     */
    public static function getCondition($key){
        $condition = [];
        if(isset($key["pay_type"]) && $key["pay_type"] != '-1'){
            $condition[] = ["order.pay_status","=",$key["pay_type"]];
        }

        if(isset($key["status"]) && $key["status"] != '-1'){
            $condition[] = ["order.status","=",$key["status"]];
        }

        if(isset($key["distribution_status"]) && $key["distribution_status"] != '-1'){
            $condition[] = ["order.distribution_status","=",$key["distribution_status"]];
        }

        if(!empty($key["title"])){
            $condition[] = ["order.order_no","like",'%'.$key["title"].'%'];
        }

        if(isset($key["order_status"])){
            if(in_array($key["order_status"],[1,2,3,4,5])){
                $condition = [];
                switch ($key["order_status"]){
                    case 1:
                        $condition[] = ["order.pay_status","=",0];
                        break;
                    case 2:
                        $condition[] = ["order.pay_status","=",1];
                        $condition[] = ["order.distribution_status","=",0];
                        break;
                    case 3:
                        $condition[] = ["order.distribution_status","=",1];
                        $condition[] = ["order.delivery_status","=",0];
                        break;
                    case 4:
                        $condition[] = ["order.status","=",5];
                        $condition[] = ["order.evaluate_status","=",0];
                        break;
                    case 5:
                        $condition[] = ["order.status","=",5];
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
        $count = OrderModel::withJoin(["users","payment"])->where($condition)->count();
        $result = array_map(function ($res){
            $res["username"] = getUserName($res);
            $res["distribution_status_name"] = self::getStatusText(self::getStatus($res));
            $res["pay_status_name"] = self::getPaymentStatusText($res["pay_status"]);
            $res["order_type_name"] = self::getOrderTypeText($res['type']);

            return $res;
        },OrderModel::withJoin(["users","payment"])->where($condition)->order("id","desc")->page($data["page"]??1,$data["limit"]??10)->select()->toArray());

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
        if(!$row = OrderModel::where("id",$id)->find()){
            throw new \Exception("您要查找的订单不存在！");
        }

        $row["store"] = StoreModel::where("id",$row["store_id"])->find();
        if(!empty($row["store"])){
            $row["store"]["area_name"] = AreaModel::getArea([$row["store"]["province"],$row["store"]["city"],$row["store"]["area"]],",");
        }

        $row["type_name"] = self::getOrderTypeText($row['type']);
        $row["distribution_status_name"] = self::getSendStatus($row["distribution_status"]);
        $row["order_status"] = self::getStatus($row);
        $row["order_status_text"] = self::getStatusText($row["order_status"]);
        $row['order_payment_status_text'] = self::getPaymentStatusText($row["pay_status"]);

        $row["distribution_name"] = DistributionModel::where(["id"=>$row["distribution_id"]])->value("title");
        $row["payment_name"] = PaymentModel::where(["id" => $row["pay_type"]])->value("name");
        $goods = OrderGoodsModel::where(["order_id" =>$id])->order("id","DESC")->select()->toArray();

        foreach($goods as $key=>$item){
            $goods[$key]["goods_array"] = "";
            if(!empty($item["goods_array"])){
                $goods[$key]["goods_array"] = json_decode($item["goods_array"],true);
            }

            $goods[$key]["order_price"] = number_format($item["goods_nums"]*$item["sell_price"],2);
        }

        $goodsWeight = 0.00;
        foreach ($goods as $key => $val) {
            $goodsWeight += $val["goods_nums"] * $val["goods_weight"];
            if ($val["product_id"] <= 0) {
                $goodsData = GoodsModel::where(["id" => $val["goods_id"]])->find();
                $goods[$key]["store_nums"] = $goodsData["store_nums"];
            } else {
                $product = GoodsItemModel::where(["id" => $val["product_id"]])->find();
                $goods[$key]["store_nums"] = $product["store_nums"];
            }
        }

        $row["goods_weight"] = $goodsWeight;
        $row["goods"] = $goods;

        $row["users"] = UsersModel::where(["id" => $row["user_id"]])->find();
        $row["users"]["username"] = getUserName($row["users"]);

        $group_name = UsersGroupModel::where(["id" => $row["users"]["group_id"]])->value("name");
        $row["users"]["level"] = empty($group_name) ? "默认会员" : $group_name;
        if (empty($group_name)) {
            $row["users"]["level"] = "默认会员";
        } else {
            $row["users"]["level"] = $group_name;
        }

        $row["users"]["sex"] = $row["users"]["sex"] == 1 ? "男" : ($row["users"]["sex"] == 0 ? "保密" : "女");

        if($row["users"]["status"] == 0){
            $row["users"]["status_text"] = "正常";
        }else if($row["users"]["status"] == 1){
            $row["users"]["status_text"] = "审核";
        }else if($row["users"]["status"] == 2){
            $row["users"]["status_text"] = "锁定";
        }else if($row["users"]["status"] == 3){
            $row["users"]["status_text"] = "删除";
        }

        $row["area_name"]       = AreaModel::getArea([$row["province"],$row["city"],$row["area"]],",");
        $row["order_log"]       = OrderLogModel::where(["order_id"=>$row["id"]])->select()->toArray();
        $row["pay_time"]        = date("Y-m-d H:i:s",$row["pay_time"]);
        $row["send_time"]       = date("Y-m-d H:i:s",$row["send_time"]);
        $row["accept_time"]     = date("Y-m-d H:i:s",$row["accept_time"]);
        $row["completion_time"] = date("Y-m-d H:i:s",$row["completion_time"]);

        return [ "data"=>$row ];
    }

    /**
     * 获取订单支付详情页数据
     * @param $id
     * @return array
     * @throws \Exception
     */
    public static function getPaymentDetail($id){
        if(!$row = OrderModel::where(["id"=>$id])->find()){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        $row["username"] = SystemUsersModel::where([ "id"=>Session::get("system_user_id") ])->value("username");
        $row["payment_name"] = PaymentModel::where(["id" => $row["pay_type"]])->value("name");
        return [ "data"=>$row ];
    }

    /**
     * 订单发货
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function deliverGoods($data){
        try{
            $data["admin_id"] = Session::get("system_user_id");
            OrderModel::startTrans();
            self::sendDeliverGoods($data);
            OrderModel::commit();
            return $data["id"];
        }catch (\Exception $ex){
            OrderModel::rollback();
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 获取发货详情数据
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getDistributionDetail($id){
        if(!$row = Db::name("order")->where(["id"=>$id])->find()){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        $goods = Db::name("order_goods")->where(["order_id" =>$row["id"]])->order("id","DESC")->select()->toArray();

        $goodsWeight = 0.00;
        foreach ($goods as $key => $val) {
            $goodsWeight += $val["goods_nums"] * $val["goods_weight"];
            if ($val["product_id"] <= 0) {
                $goodsData = Db::name("goods")->where(["id" => $val["goods_id"]])->find();
                $goods[$key]["store_nums"] = $goodsData["store_nums"];
            } else {
                $product = Db::name("goods_item")->where(["id" => $val["product_id"]])->find();
                $goods[$key]["store_nums"] = $product["store_nums"];
            }

            $goods[$key]["goods_array"] = "";
            if(!empty($val["goods_array"])){
                $goods[$key]["goods_array"] = json_decode($val["goods_array"],true);
            }

            $goods[$key]["order_price"] = number_format($val["goods_nums"]*$val["sell_price"],2);
        }

        $row["goods_weight"] = $goodsWeight;
        $row["goods"]        = $goods;

        $row["distribution_name"] = DistributionModel::where(["id"=>$row["distribution_id"]])->value("title");
        return [
            "data"=>$row,
            "freight"=>FreightModel::where(['status'=>0])->select()->toArray(),
            "province"=>AreaModel::where(['pid'=>0])->select()->toArray(),
            "city"=>AreaModel::where(['pid'=>$row["province"]])->select()->toArray(),
            "area"=>AreaModel::where(['pid'=>$row["city"]])->select()->toArray()
        ];
    }

    /**
     * 支付
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function payOrder($data){
        try{
            OrderModel::startTrans();
            $note = $data["note"]??"";

            $order = OrderModel::where(["id"=>$data["id"]??0])->find();
            if(empty($order)){
                throw new \Exception("操作失败，订单不存在！",0);
            }

            $admin_id = Session::get("system_user_id");
            self::payment($order["order_no"],$admin_id,$note);

            $admin_name = SystemUsersModel::where(["id"=>$admin_id])->value("username");
            OrderLogModel::create([
                'order_id' => $order["id"],
                'username' => $admin_name,
                'action' => '付款',
                'result' => '成功',
                'note' => '订单【' . $order["order_no"] . '】付款' . $order["order_amount"] . '元',
                'create_time' => time()
            ]);

            OrderModel::commit();
            return $order["id"];
        }catch (\Exception $ex){
            OrderModel::rollback();
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 订单退款
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function orderRefund($data){
        try{
            OrderModel::startTrans();
            $type = $data["type"]??0;
            $amount = $data["amount"]??0;
            $id = $data["id"]??0;
            $status = $data["status"]??0;
            $desc = $data["desc"]??0;
            $order_goods_id = $data["order_goods_id"]??[];

            if(empty($order_goods_id)){
                throw new \Exception("请选择需要退款的商品！",0);
            }

            if(!$order = OrderModel::where(["id"=>$id])->find()){
                throw new \Exception("您要操作的订单不存在！",0);
            }

            $admin_id = Session::get("system_user_id");

            // 管理员拒绝退款
            if($status == 1){
                if(OrderRefundmentModel::where("order_id",$order["id"])->count()){
                    OrderRefundmentModel::where("order_id",$order["id"])->save([
                        'admin_id' => $admin_id,'dispose_idea' => $desc,"pay_status"=>1,"dispose_time"=>time()
                    ]);
                }else{
                    throw new \Exception("该用户未申请退款！",0);
                }

                $user = WechatUsersModel::where("user_id",$order["user_id"])->find();
                if(!empty($user["mp_openid"])){
                    SubscribeMini::refundNotice($user["mp_openid"],$order['order_no']);
                }

                if(!empty($user["openid"])){
                    SubscribeWechat::refund($user["mp_openid"],$order['order_no']);
                }

                return $order["id"];
            }

            $orderGoodsList = OrderGoodsModel::where("id","in",$order_goods_id)->where("is_send","<>",2)->select()->toArray();
            if (empty($orderGoodsList)) {
                throw new \Exception('订单中没有符合退货条件的商品', 0);
            }

            //计算退款商品的原始支付金额
            $actAmount = 0;
            foreach ($orderGoodsList as $val) {
                $orderGoodsRow = $val;
                $actAmount += $orderGoodsRow['real_price'] * $orderGoodsRow['goods_nums'];
            }

            if ($amount > $actAmount) {
                throw new \Exception('填写的退款金额不能大于实际用户支付的金额', 0);
            }

            if(($refunds_id = OrderRefundmentModel::where("order_id",$order["id"])->value("id")) == false){
                $refunds_id = OrderRefundmentModel::create([
                    'order_no' => $order["order_no"],
                    'order_id' => $order["id"],
                    "user_id"=>$order['user_id'],
                    'admin_id' => $admin_id,
                    'type' => $type,
                    'pay_status' => 0,
                    'dispose_time' => time(),
                    'content' => '系统退款',
                    'dispose_idea' => $desc,
                    'create_time' => time(),
                    'amount' => $amount,
                    'order_goods_id' => implode(",", $order_goods_id)
                ])->id;
            }else{
                OrderRefundmentModel::where("order_id",$order["id"])->save([
                    'admin_id' => $admin_id,'dispose_idea' => $desc,'type' => $type,
                ]);
            }

            self::refund($refunds_id,$admin_id);
            OrderModel::commit();
            return $order["id"];
        }catch (\Exception $ex){
            OrderModel::rollback();
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 退款详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function orderRefundDetail($id){
        if(!$row = OrderModel::where(array("id"=>$id))->find()){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        $row["amount_refund"] = self::getRefundAmount($row);
        $row["payment"] = PaymentModel::where("id",$row["pay_type"])->find();

        $orderRefundment = OrderRefundmentModel::where("order_id",$row["id"])->find();
        $order_goods_id = isset($orderRefundment["order_goods_id"]) ? explode(",",$orderRefundment["order_goods_id"]) : [];

        $goods = OrderGoodsModel::where(["order_id" =>$row["id"]])->order("id","DESC")->select()->toArray();

        foreach ($goods as $key => $val) {
            $goods[$key]["send_status"] = Order::getSendStatus($val["is_send"]);
            $goods[$key]["goods_array"] = "";
            if(!empty($val["goods_array"])){
                $goods[$key]["goods_array"] = json_decode($val["goods_array"],true);
            }

            $goods[$key]["checked"] = in_array($val["id"],$order_goods_id) ? true : false;
            $goods[$key]["order_price"] = number_format($val["goods_nums"]*$val["sell_price"],2);
        }

        $row["goods"] = $goods;
        $row["distribution_name"] = DistributionModel::where(["id"=>$row["distribution_id"]])->value("title");
        return [
            "data"=>$row,
            "freight"=>FreightModel::where(['status' => 0])->select()->toArray(),
            "province"=>AreaModel::where(['pid' => 0])->select()->toArray(),
            "city"=>AreaModel::where(['pid' => $row["province"]])->select()->toArray(),
            "area"=>AreaModel::where(['pid' => $row["city"]])->select()->toArray()
        ];
    }

    public static function completeOrder($data){
        try{
            OrderModel::startTrans();
            $id     = $data["id"]??0;
            $status = $data["status"]??0;

            if ($id <= 0 || !in_array($status, [4, 5])) {
                throw new \Exception("参数错误",0);
            }

            $orderData = [ 'status' => $status, 'completion_time' => time() ];

            if($status == 5){
                $orderData["delivery_status"]     = 1;
                $orderData["distribution_status"] = 1;
                $orderData["evaluate_status"]     = 1;
            }

            $row = OrderModel::where(["id"=>$id])->find();
            OrderModel::where(['id'=>$id])->save($orderData);

            if ($status == 5 && in_array($row["distribution_status"],[1,2])) {
                $action = '完成';
                $note = '订单【' . $row['order_no'] . '】完成成功';

                //完成订单并且进行支付
                self::complete($row['order_no'],Session::get("system_user_id"));
            }else{
                $action = '作废';
                $note = '订单【' . $row['order_no'] . '】作废成功';
            }

            $username = SystemUsersModel::where(["id"=>Session::get("system_user_id")])->value("username");
            OrderLogModel::create([
                'order_id' => $id,
                'username' => $username,
                'action' => $action,
                'result' => '成功',
                'note' => $note,
                'create_time' => time()
            ]);

            OrderModel::commit();
            return true;
        }catch (\Exception $ex){
            OrderModel::rollback();
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * 修改订单金额
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function updateAmount($data){
        try{
            $order = OrderModel::where("id",$data["id"]??0)->find();
            $action = $data["action"]??0;
            $num = $data["num"]??0;

            if(empty($order)){
                throw new \Exception("您要操作的订单不存在",0);
            }else if($order["pay_status"] == 1){
                throw new \Exception("您要操作的订单已支付",0);
            }

            if($action == 1 && $num > $order["order_amount"]){
                throw new \Exception("您要操作的金额超过订单总金额",0);
            }

            $data = [];
            switch ($action){
                case 0:
                    $data["increase_amount"] = $num;
                    $data["order_amount"] = $order["order_amount"] + $num;
                    break;
                case 1:
                    $data["reduce_amount"] = $num;
                    $data["order_amount"] = $order["order_amount"] - $num;
                    break;
            }

            OrderModel::startTrans();
            Db::name("order")->where("id",$order["id"])->update($data);
            $username = SystemUsersModel::where(["id"=>Session::get("system_user_id")])->value("username");
            OrderLogModel::create([
                'order_id' => $order["id"],
                'username' => $username,
                'action' => $action == 0 ? "增加金额" : "减少金额",
                'result' => '成功',
                'note' => "修改订单金额",
                'create_time' => time()
            ]);

            OrderModel::commit();
            return true;
        }catch (\Exception $ex){
            OrderModel::rollback();
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * 获取订单数据
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function updateAmountDetail($id){
        return [ "order"=>OrderModel::where("id",$id)->find() ];
    }

    /**
     * 获取物流数据
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getExpressData($id){
        $order = OrderModel::where("id",$id)->where("distribution_status","in","1,2")->find();

        $order['delivery'] = OrderDeliveryModel::where("order_id",$id)->find();

        $type = strtolower(FreightModel::where("id",$order['delivery']["freight_id"])->value("type"));
        if($type == 'sfexpress'){
            $order['delivery']["distribution_code"] = $order['delivery']["distribution_code"] . ":" . substr($order['delivery']["mobile"],-4);
        }

        $order["region"] = AreaModel::getArea([$order['province'],$order['city'],$order['area']],' ');

        $express = ["expName"=>"", "number"=>"", "takeTime"=>"", "updateTime"=>""];
        try{
            $express = Aliyun::query($order['delivery']["distribution_code"],$type);
        }catch(\Exception $ex){
            $express["list"][] = [
                "status"=>"商家正在通知快递公司",
                "time"=>date("Y-m-d H:i:s",$order["send_time"])
            ];
        }

        return [
            "accept_name"=>$order["accept_name"],
            "mobile"=>$order["mobile"],
            "region"=>$order["region"],
            "address"=>$order["address"],
            "order_no"=>$order["order_no"],
            "express"=>$express
        ];
    }

    /**
     * 删除
     * @param $ids
     * @return bool
     * @throws \Exception
     */
    public static function delete($ids){
        try{
            OrderModel::startTrans();
            $array = array_map("intval",explode(",",$ids));
            foreach($array as $id) {
                $row = OrderModel::where('id', $id)->find();
                if (empty($row)) {
                    continue;
                }

                OrderModel::where("id",$id)->delete();
                OrderCollectionModel::where(["order_id" => $id])->delete();
                OrderDeliveryModel::where(["order_id" => $id])->delete();
                OrderGoodsModel::where(["order_id" => $id])->delete();
                OrderRefundmentModel::where(["order_id" => $id])->delete();
                OrderLogModel::where(["order_id" => $id])->delete();
            }

            OrderModel::commit();
            return true;
        }catch (\Exception $ex){
            OrderModel::rollback();
            throw new \Exception($ex->getMessage());
        }
    }

}