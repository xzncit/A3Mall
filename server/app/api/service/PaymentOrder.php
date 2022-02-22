<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;

use app\common\library\payment\PaymentException;
use mall\basic\Bonus;
use mall\basic\Distribution;
use mall\basic\Promotion;
use mall\basic\Shopping;
use mall\basic\Store;
use mall\basic\Users;
use mall\utils\Check;
use think\facade\Db;
use think\facade\Request;
use app\common\models\Area as AreaModel;
use app\common\library\payment\Payment;
use app\common\service\order\Order as OrderService;

class PaymentOrder extends Service {

    /**
     * 获取用户优惠劵
     * @param $amount
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getBonusData($amount){
        $bonus = Db::name("users_bonus")
            ->alias("u")
            ->field("b.*,u.id as users_bonus_id")
            ->join("promotion_bonus b","u.bonus_id=b.id","LEFT")
            ->where("u.user_id",Users::get("id"))
            ->where("u.status",0)
            ->where("b.end_time > " . time())
            ->where('order_amount <= 0 OR ' . $amount . ' >= order_amount')
            ->select()->toArray();

        $coupon = [];
        foreach($bonus as $key=>$value){
            $coupon[$key] = [
                "id"=>$value["users_bonus_id"],
                "name"=>$value["name"],
                "condition"=>$value["order_amount"] <= 0 ? "无门槛" : "满".$value["order_amount"].'可用',
                "startAt"=>date("Y-m-d",$value["start_time"]),
                "endAt"=>date("Y-m-d",$value["end_time"]),
                "price"=>(int)$value["amount"],
                "valueDesc"=>number_format($value["amount"]),
                "unitDesc"=>"元",
                "reason"=>""
            ];
        }

        return $coupon;
    }

    /**
     * 确认订单
     * @param array $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function confirm($params=[]){
        $id             = strip_tags($params["id"]??"");
        $bonus_id       = strip_tags($params["bonus_id"]??0);
        $address_id     = strip_tags($params["address_id"]??0);
        $shipping_type  = intval($params["shipping_type"]??0);
        $store_id       = intval($params["store_id"]??0);
        $product_id     = strip_tags($params["sku_id"]??0);
        $type           = strip_tags($params["type"]??"");
        $num            = intval($params["num"]??1);

        $array = array_map("intval",explode(",",$id));
        $array = array_filter($array,function ($res){
            return $res != 0;
        });

        if(count($array) <= 0){
            throw new \Exception("请选择需要购买的商品",0);
        }else if(empty($type) || !in_array($type,["buy","cart","group","point","second","special","regiment"])){
            throw new \Exception("非法操作",0);
        }

        $cart = [];
        if($type == "cart"){
            $rs = Db::name("cart")->where("user_id",Users::get("id"))->where("id","in", implode(",",$array))->select()->toArray();

            if(empty($rs)){
                throw new \Exception("请选择商品后在提交订单",0);
            }

            foreach($rs as $k=>$v){
                $cart[$k] = [
                    "activity_id"=>0,
                    "type"=>0,
                    "goods_id"=>$v["goods_id"],
                    "product_id"=>$v["product_id"],
                    "spec_key"=>$v["spec_key"],
                    "nums"=>$v["goods_nums"]
                ];
            }
        }else{
            if($num <= 0) $num = 1;
            $promotion = Promotion::checkOrderType($id,$product_id,$num,$type);
            array_push($cart,$promotion);
        }

        $data = Shopping::get($cart);

        $data["bonus"] = self::getBonusData($data["real_amount"]);

        // 检查是否开启到店自提
        $data["is_shipping"] = Db::name("setting")->where("name","is_shipping")->value("value");
        $address = Db::name("users_address")->where("user_id",Users::get("id"))->select()->toArray();

        $addressData = ["default"=>[],"list"=>[],"store"=>[]];
        foreach ($address as $key=>$value){
            $v = [
                "id"=>$value["id"],
                "name"=>$value["accept_name"],
                "tel"=>$value["mobile"],
                "province"=>$value["province"],
                "city"=>$value["city"],
                "area"=>$value["area"],
                "address"=>AreaModel::getArea([$value["province"],$value["city"],$value["area"]],',') . ' ' . $value["address"],
            ];

            if($address_id == $value["id"] || (empty($addressData["default"]) && $value["is_default"])){
                $addressData["default"] = $v;
            }

            $addressData["list"][] = $v;
        }

        $data["address"] = $addressData;
        $addressData["default"] = empty($addressData["default"]) ? (isset($addressData["list"][0]) ? $addressData["list"][0] : []) : $addressData["default"];

        try {
            if($shipping_type == 2){
                $store = Db::name("store")->where("id",$store_id)->find();
                Store::get($data,$store);
            }else{
                Distribution::get($data,$addressData["default"]);
            }
            if($bonus_id > 0){
                Bonus::apply($data,$bonus_id);
            }
        }catch (\Exception $e){
            throw new \Exception($e->getMessage(),$e->getCode() > 0 ? 1 : 0,$e->getCode());
        }

        $data["users_price"] = Users::get("amount");
        $data["users_point"] = Users::get("point");
        $data["store"] = [];
        if($data["is_shipping"]){
            $store = Db::name("store")->where(["status"=>0,"is_del"=>0])->select()->toArray();
            $data["store"] = array_map(function ($value){
                return [
                    "id"=>$value["id"],
                    "name"=>$value["shop_name"],
                    "tel"=>$value["phone"],
                    "address"=>AreaModel::getArea([$value["province"],$value["city"],$value["area"]],',') . ' ' . $value["address"]
                ];
            }, $store);
        }

        foreach($data["item"] as $key=>$value){
            if(isset($value["fictitious_array"])){
                unset($data["item"][$key]["fictitious_array"]);
            }
        }

        return $data;
    }

    /**
     * 创建订单并且调起支付
     * @param array $params
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function create($params=[]){
        $id                 = strip_tags($params["id"]??"");
        $bonus_id           = strip_tags($params["bonus_id"]??0);
        $address_id         = strip_tags($params["address_id"]??0);
        $shipping_type      = intval($params["shipping_type"]??0);
        $store_id           = strip_tags($params["store_id"]??0);
        $product_id         = strip_tags($params["sku_id"]??"");
        $type               = strip_tags($params["type"]??"");
        $payment_id         = strip_tags($params["payment"]??"");
        $remarks            = strip_tags($params["remarks"]??"");
        $num                = intval($params["num"]??1);
        $source             = intval($params["source"]??1);

        $array = array_map("intval",explode(",",$id));
        $array = array_filter($array,function ($res){
            return $res != 0;
        });

        if(count($array) <= 0){
            throw new \Exception("请选择需要购买的商品",0);
        }else if(empty($type) || !in_array($type,["buy","cart","group","point","second","special","regiment"])){
            throw new \Exception("非法操作",0);
        }

        if(!in_array($source,[1,2,3,4])){
            $source = 1;
        }

        $payment_id = Payment::instance()->getPayType($payment_id,$source);

        $cart = [];
        if($type == "cart"){
            $rs = Db::name("cart")->where("user_id",Users::get("id"))->where("id","in", implode(",",$array))->select()->toArray();

            if(empty($rs)){
                throw new \Exception("请选择商品后在提交订单",0);
            }

            foreach($rs as $k=>$v){
                $cart[$k] = [
                    "activity_id"=>0,
                    "type"=>0,
                    "cart_id"=>$v["id"],
                    "goods_id"=>$v["goods_id"],
                    "product_id"=>$v["product_id"],
                    "spec_key"=>$v["spec_key"],
                    "nums"=>$v["goods_nums"]
                ];
            }
        }else{
            if($num <= 0) $num = 1;
            $promotion = Promotion::checkOrderType($id,$product_id,$num,$type);
            array_push($cart,$promotion);
        }

        // 检查是否开启到店自提
        $is_shipping = Db::name("setting")->where("name","is_shipping")->value("value");
        if($shipping_type == 2 && $is_shipping){
            if(($store = Db::name("store")->where("id",$store_id)->find()) == false){
                throw new \Exception("您选择的到店自提地址不存在，请重新选择",0);
            }
        }else{
            if(($address = Db::name("users_address")->where(["id"=>$address_id,"user_id"=>Users::get("id")])->find()) == false){
                throw new \Exception("您选择的地址不存在，请重新选择",0);
            }
        }

        if($bonus_id > 0 && ($bonus = Db::name("users_bonus")->where("user_id",Users::get("id"))->where("id",$bonus_id)->find()) == false){
            throw new \Exception("您选择的优惠劵不存在！",0);
        }

        if(($payment = Db::name("payment")->where("code",$payment_id)->find()) == false){
            throw new \Exception("您选择的支付方式不存在！",0);
        }

        if(Check::strlen($remarks) > 100){
            throw new \Exception("留言内容不得超过100个字符",0);
        }

        try {
            $data = Shopping::get($cart);
            if($shipping_type == 2){
                $data["shipping_type"] = $shipping_type;
                $data["store_id"] = $store["id"];
                Store::get($data,$store);
            }else{
                Distribution::get($data,$address);
                $data["address"] = $address;
            }

            if($bonus_id > 0){
                Bonus::apply($data,$bonus_id);
            }

            if(isset($data["real_point"]) && $data["real_point"] > Users::get("point")){
                throw new \Exception("您的积分不足，不能兑换商品",0);
            }

            $data["payment"] = $payment;
            $data["remarks"] = $remarks;
            $data["source"] = $source;
            $data['order_id'] = OrderService::createOrder($data);
            Bonus::updateStatus($bonus_id,$data['order_id']);
            $result = Payment::instance()->setOrderData($data['order_id'])->pay($payment);
            if($type == 'cart'){
                Shopping::delete(array_map(function ($res){
                    return $res["cart_id"];
                },$cart));
            }

            Promotion::updateStatus($data);
        } catch (PaymentException $ex){
            return $ex->getRaw();
        } catch (\Exception $e){
            throw new \Exception($e->getMessage(),$e->getCode() > 0 ? 1 : 0);
        }

        $result["shop_count"] = Db::name("cart")->where("user_id",Users::get("id"))->count();
        return $result;
    }

    public static function payment($params=[]){
        $order_id   = intval($params["order_id"]??0);
        $payment_id = trim(strip_tags($params["payment_id"]??""));
        $source     = intval($params["source"]??1);

        if(!in_array($source,[1,2,3,4])){
            $source = 1;
        }

        $payment_id = Payment::instance()->getPayType($payment_id,$source);
        try{
            if(($payment = Db::name("payment")->where("code",$payment_id)->find()) == false){
                throw new \Exception("支付方式不存在！",0);
            }

            Db::name("order")->where(["user_id"=>Users::get("id"),"id"=>$order_id])->update(["pay_type"=>$payment["id"]]);
            return Payment::instance()->setOrderData($order_id)->pay($payment);
        }catch (PaymentException $ex){
            return $ex->getRaw();
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 会员充值
     * @param array $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function recharge($params=[]){
        $payment = trim(strip_tags($params["payment"]??""));
        $source  = intval($params["source"]??"");
        $price   = floatval($params["price"]??0);

        if(empty($price) || $price <= 0){
            throw new \Exception("请输入您要充值的金额！",0);
        }

        if(!in_array($source,[1,2,3,4])){
            $source = 1;
        }

        if($payment == "balance"){
            throw new \Exception("非法操作",0);
        }

        $payment_id = Payment::instance()->getPayType($payment,$source);

        if(!$payment = Db::name("payment")->where("code",$payment_id)->find()){
            throw new \Exception("您选择的支付方式不存在！",0);
        }

        Db::name("users_rechange")->insert([
            "user_id"=>Users::get("id"),
            "pay_type"=>$payment["id"],
            "order_no"=>"P".orderNo(),
            "order_amount"=>$price,
            "payment_name"=>$payment["name"],
            "status"=>0,
            "create_time"=>time()
        ]);

        $order_id = Db::name("users_rechange")->getLastInsID();
        return Payment::instance()->setRechargeData($order_id)->pay($payment);
    }
}