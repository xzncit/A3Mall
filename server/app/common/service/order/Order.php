<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\service\order;

use mall\basic\Store;
use mall\basic\Users;
use think\facade\Db;
use think\facade\Session;
use app\common\service\Service;
use mall\utils\CString;
use app\common\models\order\Order as OrderModel;
use app\common\models\order\OrderLog as OrderLogModel;
use app\common\models\order\OrderRefundment as OrderRefundmentModel;
use app\common\models\order\OrderGroup as OrderGroupModel;
use app\common\models\order\OrderGoods as OrderGoodsModel;
use app\common\models\order\OrderCollection as OrderCollectionModel;
use app\common\models\order\OrderDelivery as OrderDeliveryModel;
use app\common\models\users\Users as UsersModel;
use app\common\models\users\UsersLog as UsersLogModel;
use app\common\service\Spread\Spread;
use app\common\models\goods\Goods as GoodsModel;
use app\common\models\goods\GoodsItem as GoodsItemModel;
use app\common\models\users\UsersComment as UsersCommentModel;
use app\common\models\wechat\WechatUsers as WechatUsersModel;
use app\common\service\subscribe\mini\Subscribe as SubscribeMini;
use app\common\service\subscribe\wechat\Subscribe as SubscribeWechat;
use app\common\models\system\Users as SystemUsersModel;
use app\common\models\Payment as PaymentModel;
use app\common\library\payment\alipay\Alipay as AlipayPayment;
use app\common\library\payment\wechat\WeChat as WeChatPayment;
use app\common\models\goods\GoodsCardTemplate as GoodsCardTemplateModel;

class Order extends Service {

    /**
     * 创建订单
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public static function createOrder($data = []){
        try{
            Db::startTrans();

            $order_id = OrderModel::create([
                "activity_id"           => $data["activity_id"]??0,
                "shipping_type"         => $data["shipping_type"]??1,
                "store_id"              => $data["store_id"]??0,
                "type"                  => $data['type'],
                "user_id"               => Users::get("id"),
                "order_no"              => orderNo(),
                "pay_type"              => $data['payment']["id"],
                "distribution_id"       => 0,
                "accept_name"           => $data["address"]["accept_name"]??"",
                "zip"                   => $data["address"]["zip"]??"",
                "mobile"                => $data["address"]["mobile"]??"",
                "phone"                 => $data["address"]["phone"]??"",
                "province"              => $data["address"]["province"]??"",
                "city"                  => $data["address"]["city"]??"",
                "area"                  => $data["address"]["area"]??"",
                "address"               => $data["address"]["address"]??"",
                "message"               => $data["remarks"],
                "promotions"            => $data["promotions"]??0,
                "discount"              => $data["discount"]??0,
                "real_freight"          => $data["real_freight"],
                "payable_freight"       => $data["payable_freight"],
                "real_amount"           => $data["real_amount"],
                "real_point"            => $data["real_point"]??0,
                "order_amount"          => $data["order_amount"],
                "payable_amount"        => $data["payable_amount"],
                "shipping_code"         => isset($data["shipping_type"]) && $data["shipping_type"] == 2 ? Store::getUniqueCode() : "",
                "exp"                   => $data["exp"],
                "point"                 => $data["point"],
                "source"                => $data["source"],
                "create_time"           => time()
            ])->id;

            foreach($data["item"] as $val){
                $val["order_id"] = $order_id;
                $val["thumb_image"] = str_replace(getDomain(),"",$val["thumb_image"]);
                $val["goods_array"] = json_encode([
                    "title"=>$val["title"],
                    "spec"=>!empty($val["goods_array"]) ? implode(", ",array_map(function ($res){
                        return $res["name"] . '：' . $res['value'];
                    },$val["goods_array"])) : ""
                ],JSON_UNESCAPED_UNICODE);
                OrderGoodsModel::create($val);
            }

            Db::commit();
            return $order_id;
        }catch (\Exception $ex){
            Db::rollback();
            throw new \Exception("服务器繁忙，请稍后在试",0);
        }
    }

    /**
     * 是否允许退款申请
     * @param $order
     * @param $message
     * @return bool
     */
    public static function refundmentApply($order, $message) {
        if(!in_array($order["status"],[2,7])){
            throw new \Exception("您的订单不允许做退款处理",0);
        }

        $orderGoods = Db::name("order_goods")->where([
            "order_id"=>$order["id"]
        ])->select()->toArray();

        $arr = [];
        foreach ($orderGoods as $val) {
            if ($val['is_send'] == 2) {
                throw new \Exception("该订单已经有商品做了退款处理", 0);
            }

            if (Db::name("order_refundment")
                ->where("is_delete",0)->where("pay_status",0)
                ->where('FIND_IN_SET(' . $val["id"] . ',order_goods_id)')->count()) {
                throw new \Exception("您已经对此商品提交了退款申请，请耐心等待", 0);
            }
            $arr[] = $val["id"];
        }

        Db::startTrans();
        try{
            Db::name("order_refundment")->insert([
                "order_no"=>$order["order_no"],
                "order_id"=>$order["id"],
                "user_id"=>$order["user_id"],
                "pay_status"=>0,
                "content"=>$message,
                "amount"=>$order['order_amount'],
                "order_goods_id"=>implode(',',$arr),
                "create_time"=>time(),
            ]);

            self::updateOrderGroupStatus($order,3,1);
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            throw new \Exception("提交退款申请失败，请稍后在试。",0);
            //throw new \Exception($e->getMessage(),0);
        }

        return true;
    }

    public static function sendDeliverGoods($params){
        if (empty($params["distribution_code"])) {
            throw new \Exception("请填写配送单号", 0);
        }

        if (empty($params["freight_id"])) {
            throw new \Exception("请选择物流公司", 0);
        }

        if(!$order = OrderModel::where(["id"=>$params["id"]])->find()){
            throw new \Exception("您要操作的订单不存在！",0);
        }

        if(!$orderGoodsList = OrderGoodsModel::where("id","in",$params["order_goods_id"])->select()->toArray()){
            throw new \Exception("请选择要发货的商品", 0);
        }

        if($refund = OrderRefundmentModel::where([ "order_id"=>$order["id"],"pay_status"=>0,"is_delete"=>0 ])->find()){
            throw new \Exception("此订单有未处理的退款申请",0);
        }

        $data = [
            'order_id'              => $order["id"],
            'admin_id'              => $params["admin_id"]??0,
            'user_id'               => $order["user_id"],
            'name'                  => $params["accept_name"] ?? $order["accept_name"],
            'zip'                   => $params["zip"] ?? $order["zip"],
            'phone'                 => $params["phone"] ?? $order["phone"],
            'province'              => $params["province"] ?? $order["province"],
            'city'                  => $params["city"] ?? $order["city"],
            'area'                  => $params["area"] ?? $order["area"],
            'address'               => $params["address"] ?? $order["address"],
            'mobile'                => $params["mobile"] ?? $order["mobile"],
            'freight'               => $order["real_freight"],
            'distribution_code'     => $params["distribution_code"],
            'distribution_id'       => $order["distribution_id"],
            'note'                  => $params["remarks"]??"",
            'create_time'           => time(),
            'freight_id'            => $params["freight_id"]
        ];

        $delivery_id = OrderDeliveryModel::create($data)->id;

        $admin = SystemUsersModel::where(["id"=>$params["admin_id"]??0])->find();
        if ($order['pay_type'] == 0) {
            //减少库存量
            foreach ($orderGoodsList as $val) {
                self::updateStock([
                    "goods_id" => $val["goods_id"],
                    "product_id" => $val["product_id"],
                    "goods_nums"=>$val["goods_nums"]
                ], "-");
            }
        }

        foreach ($orderGoodsList as $val) {
            if(!empty($val["fictitious_array"])){
                $fictitious_array = json_decode($val["fictitious_array"],true);
                if(!empty($fictitious_array) && $fictitious_array["goods_type"] == 1){
                    $cardTemp = GoodsCardTemplateModel::where([
                        ["card_id","=",$fictitious_array["value"]],
                        ["order_id","<>",$order["id"]],
                        ["status","=",1]
                    ])->find();

                    if(empty($cardTemp)){
                        throw new \Exception("卡密为空，请添加新的卡密",0);
                    }

                    $fictitious_array["card"] = "帐号" . $cardTemp["name"] . " 密码：" . $cardTemp["content"];
                    OrderGoodsModel::where("id",$val["id"])->save([
                        "fictitious_array"=>json_encode($fictitious_array,JSON_UNESCAPED_UNICODE)
                    ]);

                    GoodsCardTemplateModel::where("id",$cardTemp["id"])->save([
                        "order_id"=>$order["id"],
                        "user_id"=>$order["user_id"],
                        "status"=>2
                    ]);
                }
            }
        }

        //更新发货状态
        $orderGoods = OrderGoodsModel::field('count(*) as num')->where([ "is_send"=>0,"order_id"=>$order["id"] ])->find();

        $sendStatus = 2; //部分发货
        if (count($params["order_goods_id"]) >= $orderGoods['num']) {
            $sendStatus = 1; //全部发货
        }

        foreach ($params["order_goods_id"] as $val) {
            OrderGoodsModel::where(["id"=>$val])->save([ "is_send" => 1, "distribution_id" => $delivery_id ]);
        }

        //更新发货状态
        OrderModel::where(['id'=>$order["id"]])->save([ 'distribution_status' => $sendStatus, 'send_time' =>time() ]);

        OrderLogModel::create([
            'order_id' => $order["id"],
            'username' => $admin["username"],
            'action' => '发货',
            'result' => '成功',
            'note' => '订单【' . $order["order_no"] . '】由【管理员】' . $admin["username"] . '发货',
            'create_time' => time()
        ]);

        $user = WechatUsersModel::where("user_id",$order["user_id"])->find();
        if(!empty($user["mp_openid"])){
            SubscribeMini::deliveryNotice($user["mp_openid"],$delivery_id);
        }

        if(!empty($user["openid"])){
            SubscribeWechat::delivery($user["openid"],$delivery_id);
        }

        sendSMS(["mobile"=>$order["mobile"],"order_no"=>$order["order_no"]],"deliver_goods");
        return true;
    }

    /**
     * 退款处理
     * @param $refunds_id
     * @param int $admin_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function refund($refunds_id,$admin_id=0){
        $refunds = OrderRefundmentModel::where(["id"=>$refunds_id])->find();

        $orderGoodsList = OrderGoodsModel::where("id","in",$refunds['order_goods_id'])->where("is_send","<>","2")->select()->toArray();
        if (!$orderGoodsList) {
            throw new \Exception("订单中没有符合退货条件的商品！",0);
        }

        //退款的商品关联信息
        $autoMount = 0;
        $orderRow = [ 'exp' => 0, 'point' => 0, 'order_no' => $refunds['order_no'] ];

        foreach ($orderGoodsList as $val) {
            $autoMount += $val['goods_nums'] * $val['real_price'];

            //库存增加
            self::updateStock(["goods_id" => $val["goods_id"], "product_id" => $val["product_id"], "goods_nums"=>$val["goods_nums"]], '+');

            //更新退款状态
            OrderGoodsModel::where('id',$val['id'])->save(['is_send' => 2]);

            //退款积分,经验
            $goodsRow = GoodsModel::where('id',$val['goods_id'])->find();
            $orderRow['exp'] += $goodsRow['exp'] * $val['goods_nums'];
            $orderRow['point'] += $goodsRow['point'] * $val['goods_nums'];
        }

        //如果管理员自定义了退款金额。否则就使用默认的付款商品金额
        $amount = $refunds['amount'] > 0 ? $refunds['amount'] : $autoMount;

        //更新order表状态,查询是否订单中还有未退款的商品，判断是订单退款状态：全部退款或部分退款
        $isSendData = OrderGoodsModel::where('order_id',$refunds['order_id'])->where('is_send','<>','2')->find();
        $orderStatus = 6; //全部退款
        if ($isSendData) {
            $orderStatus = 7; //部分退款
        }

        OrderModel::where(["id"=>$refunds['order_id']])->save(['status' => $orderStatus]);
        $order = OrderModel::where('id',$refunds['order_id'])->find();
        if ($orderStatus == 6) {
            //在订单商品没有发货情况下，返还运费，报价，税金
            $isDeliveryData = OrderGoodsModel::where('order_id',$refunds['order_id'])->where('distribution_id','>','0')->find();
            if (!$isDeliveryData) {
                $amount += $order['real_freight'] + $order['insured'] + $order['taxes'];
            }
        }

        $out_refund_no = orderNo();
        //更新退款表
        OrderRefundmentModel::where(["id"=>$refunds_id])->save([
            'out_refund_no'=>$out_refund_no,
            'amount' => $amount,
            'pay_status' => 2,
            'dispose_time' =>time()
        ]);

        $admin = SystemUsersModel::where(["id"=>$refunds["admin_id"]])->find();

        if($refunds["type"] == 0){
            UsersModel::where(["id"=>$order["user_id"]])->inc("amount",$amount)->update();
            UsersLogModel::create([
                "order_no"=>$order["order_no"],
                "user_id"=>$order["user_id"],
                "admin_id"=>Session::get("system_user_id"),
                "action"=>3,
                "operation"=>1,
                "amount"=>$amount,
                "description"=>'退款订单号：' . $refunds['order_no'] . '中的商品,退款金额 -￥' . $amount,
                "create_time"=>time()
            ]);
        }

        if($orderRow['exp'] > 0){
            //更新用户的信息
            $users = UsersModel::where(["id"=>$refunds["user_id"]])->find();

            $exp = $users['exp'] - $orderRow['exp'];
            if($exp > 0) {
                UsersModel::where(["id" => $refunds["user_id"]])->save([ "exp"=>$exp ]);
            }

            $log = '退款订单号：' . $refunds['order_no'] . '中的商品,减掉经验 -' . $orderRow['exp'];
            UsersLogModel::create([
                "order_no"=>$order["order_no"],
                "user_id"=>$refunds["user_id"],
                "admin_id"=>$admin_id ? $admin_id : "-1",
                "action"=>2,
                "operation"=>1,
                "point"=>$orderRow['exp'],
                "description"=>$log,
                "create_time"=>time()
            ]);
        }

        if($orderRow['point'] > 0){
            $log = '退款订单号：' . $refunds['order_no'] . '中的商品,减掉积分 -' . $orderRow['point'];
            UsersModel::where(["id"=>$order["user_id"]])->dec("point",$orderRow['point'])->update();
            UsersLogModel::create([
                "order_no"=>$order["order_no"],
                "user_id"=>$refunds["user_id"],
                "admin_id"=>$admin_id ? $admin_id : "-1",
                "action"=>1,
                "operation"=>1,
                "point"=>$orderRow['point'],
                "description"=>$log,
                "create_time"=>time()
            ]);
        }

        $user = WechatUsersModel::where("user_id",$order["user_id"])->find();
        if(!empty($user["mp_openid"])){
            SubscribeMini::refundNotice($user["mp_openid"],$refunds['order_no']);
        }

        OrderLogModel::create([
            'order_id' => $refunds["order_id"],
            'username' => $admin["username"],
            'action' => '退款',
            'result' => '成功',
            'note' => '订单【' . $refunds["order_no"] . '】退款，退款金额：' . $amount,
            'create_time' => time()
        ]);

        self::updateOrderGroupStatus($order,3,1);
        return true;
    }

    /**
     *  获取己退款金额
     * @param array $data
     * @return float|int
     */
    public static function getRefundAmount($data){
        $list = OrderRefundmentModel::where([ "order_id"=>$data["id"],"pay_status"=>2 ])->select()->toArray();

        $refundFee = 0.00;
        foreach ($list as $val) {
            $refundFee += $val['amount'];
        }

        return number_format($refundFee,2);
    }

    /**
     * 支付成功后修改订单状态
     */
    public static function payment($order_no,$admin_id=0,$note="",$trade_no=""){
        if(!$order = OrderModel::where(["order_no"=>$order_no])->find()){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        if($order["pay_status"] == 1){
            throw new \Exception("您查找的订单己支付！",0);
        }

        if(!OrderModel::where(["order_no"=>$order_no])->save([
            "status" => ($order['status'] == 5) ? 5 : 2,
            "pay_time" => time(),
            "pay_status" => 1,
            "note" => $note,
            "trade_no"=>$trade_no,
            "admin_id"=>$admin_id
        ])){
            throw new \Exception("操作订单失败，请重试！",0);
        }

        //插入收款单
        OrderCollectionModel::create([
            'order_id' => $order['id'],
            'user_id' => $order['user_id'],
            'amount' => $order['order_amount'],
            'create_time' => time(),
            'payment_id' => $order['pay_type'],
            'pay_status' => 1,
            'is_delete' => 0,
            'note' => $note,
            'admin_id' => $admin_id ? $admin_id : 0
        ]);

        $orderGoodsList = OrderGoodsModel::where(['order_id' => $order['id']])->select()->toArray();
        //减少库存量
        if ($order['pay_type'] != 0) {
            foreach ($orderGoodsList as $val) {
                self::updateStock([
                    "goods_id" => $val["goods_id"],
                    "product_id" => $val["product_id"],
                    "goods_nums" => $val["goods_nums"]
                ], "-");

                GoodsModel::where('id',$val["goods_id"])->save([ "sale"=>Db::raw("sale+1") ]);
            }
        }

        // 核销订单，购买后发货
        if($order["shipping_type"] == 2){
            foreach ($orderGoodsList as $val) {
                OrderGoodsModel::where(["id"=>$val["id"]])->save([
                    "is_send" => 1,
                    "distribution_id" => 0
                ]);
            }

            //更新发货状态
            OrderModel::where(['id'=>$order['id']])->save([
                'distribution_status' => 1,
                'send_time' =>time()
            ]);

            OrderLogModel::create([
                'order_id' => $order['id'],
                'username' => "system",
                'action' => '发货',
                'result' => '成功',
                'note' => '订单【' . $order["order_no"] . '】由 system 发货',
                'create_time' => time()
            ]);
        }

        $user = WechatUsersModel::where("user_id",$order["user_id"])->find();
        if(!empty($user["mp_openid"])){
            SubscribeMini::orderPaySuccess($user["mp_openid"],$order["id"]);
        }

        if(!empty($user["openid"])){
            SubscribeWechat::pay($user["openid"],$order["id"]);
        }

        return true;
    }


    /**
     * 订单完成
     */
    public static function complete($order_no,$admin_id=0){
        if(!$order = OrderModel::where(["order_no"=>$order_no])->find()){
            throw new \Exception("您要查找的订单不存在，请刷新重试！",0);
        }

        if(!$users = UsersModel::where(["id"=>$order["user_id"]])->find()){
            if($order['exp'] > 0){
                $log = '成功购买了订单号：' . $order['order_no'] . '中的商品,奖励经验' . $order['exp'];
                UsersModel::where(["id"=>$order["user_id"]])->inc("exp",$order['exp'])->update();
                UsersLogModel::create([
                    "order_no"=>$order_no,
                    "user_id"=>$order["user_id"],
                    "admin_id"=> $admin_id ? $admin_id : 0,
                    "action"=>2,
                    "operation"=>0,
                    "exp"=>$order['exp'],
                    "description"=>$log,
                    "create_time"=>time()
                ]);
            }

            if($order['point'] > 0){
                $log = '成功购买了订单号：' . $order['order_no'] . '中的商品,奖励积分' . $order['point'];
                UsersModel::where(["id"=>$order["user_id"]])->inc("point",$order['point'])->update();
                UsersLogModel::create([
                    "order_no"=>$order_no,
                    "user_id"=>$order["user_id"],
                    "admin_id"=> $admin_id ? $admin_id : 0,
                    "action"=>1,
                    "operation"=>0,
                    "point"=>$order['point'],
                    "description"=>$log,
                    "create_time"=>time()
                ]);
            }

            Spread::backBrokerage($order);
        }

        //获取此订单中的商品种类
        $orderList = OrderGoodsModel::where(['order_id'=>$order["id"]])->group('goods_id')->select()->toArray();

        $orderData = [
            "point"=>0,
            "describes"=>0,
            "service"=>0,
            "logistics"=>0,
        ];

        if($order["status"] == 5){
            $orderData["point"] = 5;
            $orderData["describes"] = 5;
            $orderData["service"] = 5;
            $orderData["logistics"] = 5;
            $orderData["comment_time"] = time();
        }

        //对每类商品进行评论开启
        foreach ($orderList as $val) {
            if (GoodsModel::where(['id'=>$val['goods_id']])->find()) {
                UsersCommentModel::create(array_merge($orderData,[
                    'goods_id' => $val['goods_id'],
                    'order_no' => $order['order_no'],
                    'user_id' => $order['user_id'],
                    'create_time' => time()
                ]));
            }
        }

        self::updateOrderGroupStatus($order,2);

        $user = WechatUsersModel::where("user_id",$order["user_id"])->find();
        if(!empty($user["mp_openid"])){
            SubscribeMini::orderComplete($user["mp_openid"],$order["id"]);
        }

        return true;
    }

    /**
     * 更新拼团订单状态
     * @param $order
     * @param $status
     * @param int $refundStatus
     * @return false
     */
    public static function updateOrderGroupStatus($order,$status,$refundStatus=0){
        if($order["type"] != 5){
            return false;
        }

        $condition = ["order_id"=>$order["id"],"user_id"=>$order["user_id"],"status"=>1,"is_refund"=>0];
        if($order["type"] == 5 && OrderGroupModel::where($condition)->count()){
            return OrderGroupModel::where($condition)->update([
                "is_refund"=>$refundStatus,"status"=>$status,"complete_time"=>time()
            ]);
        }

        return false;
    }

    /**
     * 更新库存
     * @return boolean
     */
    public static function updateStock($data, $type = "-") {
        if ($data["product_id"] > 0) {
            $product = GoodsItemModel::where([ "goods_id"=>$data["goods_id"],"id"=>$data["product_id"] ])->find();
        }

        $product_store = 0;
        $goods = GoodsModel::where(["id"=>$data["goods_id"]])->find();
        switch ($type) {
            case "-":
                if (!empty($product)) {
                    $product_store = $product["store_nums"] - $data["goods_nums"];
                }
                $goods_store = $goods["store_nums"] - $data["goods_nums"];
                break;
            case "+":
                if (!empty($product)) {
                    $product_store = $product["store_nums"] + $data["goods_nums"];
                }
                $goods_store = $goods["store_nums"] + $data["goods_nums"];
                break;
        }

        if ($data["product_id"] > 0) {
            GoodsItemModel::where([ "goods_id"=>$data["goods_id"],"id"=>$data["product_id"] ])->save(["store_nums" => $product_store]);
        }

        GoodsModel::where([ "id"=>$data["goods_id"]])->save(["store_nums" => $goods_store]);
        return true;
    }

    /**
     * 获取退款类型
     * @param $code
     * @return string
     */
    public static function getRefundmentText($code) {
        $result = ['0' => '申请退款', '1' => '退款失败', '2' => '退款成功'];
        return isset($result[$code]) ? $result[$code] : '';
    }

    public static function getOrderActive($order){
        if($order["pay_status"] == 0){
            return 0;
        }

        if($order["status"] == 2 && $order["distribution_status"] == 0){
            return 1;
        }else if($order["status"] == 2 && $order["distribution_status"]){
            return 2;
        }

        if($order["status"] == 5 && in_array($order["evaluate_status"],[0,2])){
            return 3;
        }

        if($order["status"] == 5 && $order["evaluate_status"] == 1){
            return 4;
        }

        return -1;
    }

    /**
     * 获取订单状态码
     * @param $order
     * @return int
     */
    public static function getStatus($order){
        if(empty($order)){
            return 0;
        }

        if($order["status"] == 1 && $order["pay_status"] == 0){ // 待付款
            return 1;
        }else if($order["status"] == 2){
            // 检查是否有退款
            $refund = OrderRefundmentModel::where([ "order_id"=>$order['id'],"is_delete"=>0 ])->find();
            if(!empty($refund)){
                if($refund["pay_status"] == 0){
                    return 11;
                }

                if($refund["pay_status"] == 1){
                    if($order["distribution_status"] == 1){
                        return 3;
                    }else if($order["distribution_status"] == 2){
                        return 4;
                    }

                    return 12;
                }
            }

            if($order["distribution_status"] == 0){ // 待发货
                return 2;
            }else if($order["distribution_status"] == 1){ // 待收货
                return 3;
            }else if($order["distribution_status"] == 2){ // 部份发货
                return 4;
            }
        }

        if($order["status"] == 5){
            if(in_array($order["evaluate_status"],[0,2])){ // 待评价
                return 5;
            }else if($order["evaluate_status"] == 1){ // 己完成
                return 6;
            }
        }else if ($order['status'] == 3 || $order['status'] == 4) { // 取消或者作废订单
            return 7;
        }else if ($order['status'] == 6) { // 退款
            return 8;
        }else if ($order['status'] == 7) { // 部分退款
            if ($order['distribution_status'] == 1) { // 发货
                return 10;
            } else { // 未发货
                return 9;
            }
        }

        return 0;
    }

    /**
     * 获取订单状态
     * @param $code
     * @return string
     */
    public static function getStatusText($code) {
        $result = [
            0=>'未知',1=>'等待付款',2=>'等待发货',3=>'待收货',4 => '部份发货',
            5=>'待评价',6=>'已完成',7=>'己取消',8=>'退款成功',9=>'未发货',
            10=>'部分退款',11=>'申请退款',12=>'拒绝退款'
        ];

        return isset($result[$code]) ? $result[$code] : '';
    }

    /**
     * 获取支付状态
     * @param $status
     * @return string
     */
    public static function getPaymentStatusText($status){
        return $status == 0 ? "未支付" : "己支付";
    }

    /**
     * 获取订单类型
     * @param $type
     * @param int $length
     * @return string
     */
    public static function getOrderTypeText($type,$length=-1) {
        switch ($type) {
            case "1":
                $string = '积分订单';
                break;
            case "2":
                $string = '团购订单';
                break;
            case "3":
                $string = "秒杀订单";
                break;
            case "5":
                $string = "拼团订单";
                break;
            case '4':
            default:
                $string = '普通订单';
        }

        return $length == -1 ? $string : CString::msubstr($string,$length,false);
    }

    /**
     * 获取订单发货状态
     * @param $code
     * @return string
     */
    public static function getSendStatus($code) {
        $data = [0 => '等待发货', 1 => '已发货', 2=>"部份发货", 3 => '已退货'];
        return isset($data[$code]) ? $data[$code] : '未配货';
    }

    /**
     * 获取订单类型num
     * @param string $type
     * @return int
     */
    public static function getOrderType($type=""){
        $arr = ["point"=>1,"regiment"=>2,"second"=>3,"special"=>4,"group"=>5,"buy"=>0,"cart"=>0];
        return isset($arr[$type]) ? $arr[$type] : 0;
    }
}