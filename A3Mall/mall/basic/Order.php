<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\basic;

use mall\utils\CString;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;

class Order {

    public static function create($data = []) {
        Db::startTrans();
        try{
            Db::name("order")->insert([
                "activity_id"=>isset($data["activity_id"]) ? $data["activity_id"] : 0,
                "type"=>$data['type'],
                "user_id"=>$data["user_id"],
                "order_no"=>self::orderNo(),
                "pay_type"=>$data['payment']["id"],
                "distribution_id"=>0,
                "accept_name"=>$data["address"]["accept_name"],
                "zip"=>$data["address"]["zip"],
                "mobile"=>$data["address"]["mobile"],
                "phone"=>$data["address"]["phone"],
                "province"=>$data["address"]["province"],
                "city"=>$data["address"]["city"],
                "area"=>$data["address"]["area"],
                "address"=>$data["address"]["address"],
                "message"=>$data["remarks"],
                "promotions"=>isset($data["promotions"]) ? $data["promotions"] : 0,
                "discount"=>isset($data["discount"]) ? $data["discount"] : 0,
                "real_freight"=>$data["real_freight"],
                "payable_freight"=>$data["payable_freight"],
                "real_amount"=>$data["real_amount"],
                "real_point"=>isset($data["real_point"]) ? $data["real_point"] : 0,
                "order_amount"=>$data["order_amount"],
                "payable_amount"=>$data["payable_amount"],
                "exp"=>$data["exp"],
                "point"=>$data["point"],
                "source"=>$data["source"],
                "create_time"=>time()
            ]);

            $order_id = Db::name("order")->getLastInsID();
            foreach($data["item"] as $val){
                $val["order_id"] = $order_id;
                $val["thumb_image"] = str_replace(Request::domain(),"",$val["thumb_image"]);
                $val["goods_array"] = json_encode([
                    "title"=>$val["title"],
                    "spec"=>!empty($val["goods_array"]) ? implode(", ",array_map(function ($res){
                        return $res["name"] . '：' . $res['value'];
                    },$val["goods_array"])) : ""
                ],JSON_UNESCAPED_UNICODE);
                Db::name("order_goods")->strict(false)->insert($val);
            }

            Db::commit();
        }catch(\Exception $ex){
            Db::rollback();
            throw new \Exception("由于网络繁忙，请稍后在试",0);
        }

        return $order_id;
    }

    /**
     * 支付成功后修改订单状态
     */
    public static function payment($order_no,$admin_id=0,$note="",$trade_no=""){
        if(($order = Db::name("order")->where(["order_no"=>$order_no])->find()) == false){
            throw new \Exception("您要查找的订单不存在！",0);
        }

        if($order["pay_status"] == 1){
            throw new \Exception("您查找的订单己支付！",0);
        }

        if(Db::name("order")->where(["order_no"=>$order_no])->update([
                "status" => ($order['status'] == 5) ? 5 : 2,
                "pay_time" => time(),
                "pay_status" => 1,
                "note" => $note,
                "trade_no"=>$trade_no,
                "admin_id"=>$admin_id
            ]) == false){
            throw new \Exception("操作订单失败，请重试！",0);
        }

        //插入收款单
        Db::name('order_collection')->insert([
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

        //非货到付款的支付方式
        if ($order['pay_type'] != 0) {
            //减少库存量
            $orderGoodsList = Db::name("order_goods")->where(['order_id' => $order['id']])->select()->toArray();
            foreach ($orderGoodsList as $val) {
                self::updateStock([
                    "goods_id" => $val["goods_id"],
                    "product_id" => $val["product_id"],
                    "goods_nums"=>$val["goods_nums"]
                ], "-");
            }
        }

        return true;
    }

    /**
     * 订单完成
     */
    public static function complete($order_no,$admin_id=0){
        if(($order = Db::name("order")->where(["order_no"=>$order_no])->find()) == false){
            throw new \Exception("您要查找的订单不存在，请刷新重试！",0);
        }

        if(($users = Db::name("users")->where(array("id"=>$order["user_id"]))->find()) != false){

            if($order['exp'] > 0){
                $log = '成功购买了订单号：' . $order['order_no'] . '中的商品,奖励经验' . $order['exp'];
                Db::name("users")->where(["id"=>$order["user_id"]])->inc("exp",$order['exp'])->update();
                Db::name("users_log")->insert([
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
                Db::name("users")->where(["id"=>$order["user_id"]])->inc("point",$order['point'])->update();
                Db::name("users_log")->insert([
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

            //Spread::backBrokerage($index);
        }

        //获取此订单中的商品种类
        $orderList = Db::name("order_goods")->where(['order_id'=>$order["id"]])->group('goods_id')->select()->toArray();

        //对每类商品进行评论开启
        foreach ($orderList as $val) {
            if (Db::name("goods")->where(['id'=>$val['goods_id']])->find()) {
                Db::name("users_comment")->insert([
                    'goods_id' => $val['goods_id'],
                    'order_no' => $order['order_no'],
                    'user_id' => $order['user_id'],
                    'create_time' => time()
                ]);
            }
        }

        return true;
    }

    public static function sendDistributionGoods($order_id, $order_goods_id, $admin_id){
        if ($order_id <= 0) {
            throw new \Exception("参数错误！", 0);
        }

        if (empty($order_goods_id)) {
            throw new \Exception("请选择要发货的商品", 0);
        }

        $distribution_code = Request::post('distribution_code',"","trim,strip_tags");

        $freight_id = Request::post('freight_id',0,"intval");
        if (empty($distribution_code)) {
            throw new \Exception("请填写配送单号", 0);
        }

        if (empty($freight_id)) {
            throw new \Exception("请选择物流公司", 0);
        }

        $refund = Db::name('order_refundment')->where([
            "order_id"=>$order_id,"pay_status"=>0,"is_delete"=>0
        ])->find();
        if(!empty($refund)){
            throw new \Exception("此订单有未处理的退款申请",0);
        }

        $order = Db::name("order")->where(["id"=>$order_id])->find();

        if(empty($order)){
            throw new \Exception("该订单不存在！",0);
        }

        $data = [
            'order_id' => $order_id,
            'user_id' => $order["user_id"],
            'name' => Request::post('accept_name','','trim,strip_tags'),
            'zip' => Request::post('zip','','intval'),
            'phone' => Request::post('phone','','intval'),
            'province' => Request::post('province','','intval'),
            'city' => Request::post('city','','intval'),
            'area' => Request::post('area','','intval'),
            'address' => Request::post('address','','trim,strip_tags'),
            'mobile' => Request::post('mobile','','trim,strip_tags'),
            'freight' => $order["real_freight"],
            'distribution_code' => $distribution_code,
            'distribution_id' => $order["distribution_id"],
            'note' => Request::post('remarks','','trim,strip_tags'),
            'create_time' => time(),
            'freight_id' => $freight_id
        ];

        $data['admin_id'] = $admin_id;

        $delivery_id = Db::name('order_delivery')->insert($data);

        $admin = Db::name("system_users")->where(["id"=>$admin_id])->find();

        if ($order['pay_type'] == 0) {
            //减少库存量
            $orderGoodsList = Db::name("order_goods")->where("id","in",$order_goods_id)->select()->toArray();
            foreach ($orderGoodsList as $val) {
                self::updateStock([
                    "goods_id" => $val["goods_id"],
                    "product_id" => $val["product_id"],
                    "goods_nums"=>$val["goods_nums"]
                ], "-");
            }
        }

        //更新发货状态
        $orderGoods = Db::name("order_goods")->field('count(*) as num')->where([
            "is_send"=>0,"order_id"=>$order_id
        ])->find();

        $sendStatus = 2; //部分发货
        if (count($order_goods_id) >= $orderGoods['num']) {
            $sendStatus = 1; //全部发货
        }

        foreach ($order_goods_id as $val) {
            Db::name("order_goods")->where(["id"=>$val])->update([
                "is_send" => 1,
                "distribution_id" => $delivery_id
            ]);
        }

        //更新发货状态
        Db::name('order')->where(['id'=>$order_id])->update([
            'distribution_status' => $sendStatus,
            'send_time' =>time()
        ]);

        Db::name("order_log")->insert([
            'order_id' => $order_id,
            'username' => $admin["username"],
            'action' => '发货',
            'result' => '成功',
            'note' => '订单【' . $order["order_no"] . '】由【管理员】' . $admin["username"] . '发货',
            'create_time' => time()
        ]);

        return true;
    }

    public static function refund($refunds_id,$admin_id=0){
        $refunds = Db::name("order_refundment")->where(["id"=>$refunds_id])->find();

        $orderGoodsList = Db::name("order_goods")->where("id","in",$refunds['order_goods_id'])->where("is_send","<>","2")->select()->toArray();
        if (!$orderGoodsList) {
            throw new \Exception("订单中没有符合退货条件的商品！",0);
        }

        //退款的商品关联信息
        $autoMount = 0;
        $orderRow = [
            'exp' => 0,
            'point' => 0,
            'order_no' => $refunds['order_no']
        ];

        foreach ($orderGoodsList as $val) {
            $autoMount += $val['goods_nums'] * $val['real_price'];

            //库存增加
            self::updateStock([
                "goods_id" => $val["goods_id"],
                "product_id" => $val["product_id"],
                "goods_nums"=>$val["goods_nums"]
            ], '+');

            //更新退款状态
            Db::name("order_goods")->where('id',$val['id'])->update(['is_send' => 2]);

            //退款积分,经验
            $goodsRow = Db::name("goods")->where('id',$val['goods_id'])->find();
            $orderRow['exp'] += $goodsRow['exp'] * $val['goods_nums'];
            $orderRow['point'] += $goodsRow['point'] * $val['goods_nums'];
        }

        //如果管理员自定义了退款金额。否则就使用默认的付款商品金额
        $amount = $refunds['amount'] > 0 ? $refunds['amount'] : $autoMount;

        //更新order表状态,查询是否订单中还有未退款的商品，判断是订单退款状态：全部退款或部分退款
        $isSendData = Db::name("order_goods")->where('order_id',$refunds['order_id'])->where('is_send','<>','2')->find();
        $orderStatus = 6; //全部退款
        if ($isSendData) {
            $orderStatus = 7; //部分退款
        }

        Db::name("order")->where(["id"=>$refunds['order_id']])->update(['status' => $orderStatus]);

        /**
         * 进行用户的余额增加操作,积分，经验的减少操作,
         * 1.检查订单由谁支付
         * 2.当全部退款时候,减少订单中记录的积分和经验;且如果没有发货的商品直接退回订单中的运费，报价，税金等
         * 3.当部分退款时候,查询商品表中积分和经验
         */
        $order = Db::name("order")->where('id',$refunds['order_id'])->find();
        if ($orderStatus == 6) {
            //在订单商品没有发货情况下，返还运费，报价，税金
            $isDeliveryData = Db::name("order_goods")->where('order_id',$refunds['order_id'])->where('distribution_id','>','0')->find();
            if (!$isDeliveryData) {
                $amount += $order['real_freight'] + $order['insured'] + $order['taxes'];
            }
        }

        //更新退款表
        Db::name("order_refundment")->where(["id"=>$refunds_id])->update([
            'amount' => $amount,
            'pay_status' => 2,
            'dispose_time' =>time()
        ]);

        $admin = Db::name("system_users")->where(["id"=>$refunds["admin_id"]])->find();

        if($refunds["type"] == 0){
            Db::name("users")->where(["id"=>$order["user_id"]])->inc("amount",$amount)->update();
            Db::name("users_log")->insert([
                "user_id"=>$order["user_id"],
                "admin_id"=>Session::get("system_user_id"),
                "action"=>3,
                "operation"=>0,
                "amount"=>$amount,
                "create_time"=>time()
            ]);
        }

        if($orderRow['exp'] > 0){
            //更新用户的信息
            $users = Db::name("users")->where(["id"=>$refunds["user_id"]])->find();

            $exp = $users['exp'] - $orderRow['exp'];
            if($exp > 0) {
                Db::name("users")->where(["id" => $refunds["user_id"]])->update([
                    'exp' => $exp <= 0 ? 0 : $exp
                ]);
            }

            $log = '退款订单号：' . $refunds['order_no'] . '中的商品,减掉经验 -' . $orderRow['exp'];
            Db::name("users_log")->insert([
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
            Db::name("users")->where(["id"=>$order["user_id"]])->dec("point",$orderRow['point'])->update();
            Db::name("users_log")->insert([
                "user_id"=>$refunds["user_id"],
                "admin_id"=>$admin_id ? $admin_id : "-1",
                "action"=>1,
                "operation"=>0,
                "point"=>$orderRow['point'],
                "description"=>$log,
                "create_time"=>time()
            ]);

        }

        Db::name("order_log")->insert([
            'order_id' => $refunds["order_id"],
            'username' => $admin["username"],
            'action' => '退款',
            'result' => '成功',
            'note' => '订单【' . $refunds["order_no"] . '】退款，退款金额：' . $amount,
            'create_time' => time()
        ]);

        return true;
    }

    /**
     *  获取己退款金额
     * @param array $data
     * @return float|int
     */
    public static function getRefundAmount($data){
        $list = Db::name("order_refundment")->where([
            "order_id"=>$data["id"],"pay_status"=>2
        ])->select()->toArray();
        $refundFee = 0.00;
        foreach ($list as $val) {
            $refundFee += $val['amount'];
        }

        return number_format($refundFee,2);
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

            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            throw new \Exception("提交退款申请失败，请稍后在试。",0);
            //throw new \Exception($e->getMessage(),0);
        }

        return true;
    }

    public static function getOrderType($type=""){
        $arr = ["point"=>1,"group"=>2,"second"=>3,"special"=>4,"buy"=>0,"cart"=>0];
        return isset($arr[$type]) ? $arr[$type] : 0;
    }

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
            case '4':
            default:
                $string = '普通订单';
        }

        return $length == -1 ? $string : CString::msubstr($string,$length,false);
    }

    public static function getRefundmentText($code) {
        $result = ['0' => '申请退款', '1' => '退款失败', '2' => '退款成功'];
        return isset($result[$code]) ? $result[$code] : '';
    }

    public static function getSendStatus($code) {
        $data = [0 => '等待发货', 1 => '已发货', 2=>"部份发货", 3 => '已退货'];
        return isset($data[$code]) ? $data[$code] : '未配货';
    }

    public static function getPaymentStatusText($status){
        return $status == 0 ? "未支付" : "己支付";
    }

    public static function getDeliveryStatus($status){
        return $status == 0 ? "未收货" : "己收货";
    }

    public static function getEvaluateStatus($status){
        switch ($status){
            case 0:
                return '未评价';
            case 1:
                return '己评价';
            case 2:
                return '部份评价';
            default:
                return '';
        }
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

    public static function getStatusText($code) {
        $result = [
            0=>'未知',1=>'等待付款',2=>'等待发货',3=>'待收货',4 => '部份发货',
            5=>'待评价',6=>'已完成',7=>'己取消',8=>'退款',9=>'未发货',
            10=>'部分退款',11=>'申请退款'
        ];

        return isset($result[$code]) ? $result[$code] : '';
    }

    public static function getStatus($order){
        if(empty($order)){
            return 0;
        }

        if($order["status"] == 1 && $order["pay_status"] == 0){ // 待付款
            return 1;
        }else if($order["status"] == 2){
            // 检查是否有退款
            if(Db::name('order_refundment')->where([
                "order_id"=>$order['id'],"is_delete"=>0,"pay_status"=>0
            ])->count()){
                return 11;
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
        }else if ($order['status'] == 3 || $order['status'] == 4) { //3,取消或者作废订单
            return 7;
        }else if ($order['status'] == 6) { // 5,退款
            return 8;
        }else if ($order['status'] == 7) { // 6,部分退款
            if ($order['distribution_status'] == 1) { // 发货
                return 10;
            } else { // 未发货
                return 9;
            }
        }

        return 0;
    }

    public static function orderNo($number = '', $date = 'YmdHis') {
        $arr = explode(" ", microtime());
        $usec = substr(str_replace('0.', '', $arr[0]), 0, 2) . rand(100, 999);
        return $number . date($date) . $usec;
    }

    /**
     * 更新库存
     * @param type $data
     * @param type $type
     * @return boolean
     */
    public static function updateStock($data, $type = "-") {
        if ($data["product_id"] > 0) {
            $product = Db::name("goods_item")->where([
                "goods_id"=>$data["goods_id"],"id"=>$data["product_id"]
            ])->find();
        }

        $product_store = 0;
        $goods = Db::name("goods")->where(["id"=>$data["goods_id"]])->find();
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
            Db::name("goods_item")->where([
                "goods_id"=>$data["goods_id"],"id"=>$data["product_id"]
            ])->update(["store_nums" => $product_store]);
        }

        Db::name("goods")->where([ "id"=>$data["goods_id"]])->update(["store_nums" => $goods_store]);

        return true;
    }

}