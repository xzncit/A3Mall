<?php
namespace app\api\controller\wap;

use mall\basic\Area;
use mall\basic\Bonus;
use mall\basic\Distribution;
use mall\basic\Payment;
use mall\basic\Promotion;
use mall\utils\Check;
use mall\utils\Tool;
use think\facade\Db;
use think\facade\Request;
use mall\basic\Cart;

class Order extends Auth {

    public function confirm(){
        $id = Request::param("id","","trim,strip_tags");
        $bonus_id = Request::param("bonus_id","0","trim,strip_tags");
        $address_id = Request::param("address_id","0","trim,strip_tags");
        $product_id = Request::param("sku_id","","trim,strip_tags");
        $type = Request::param("type","","trim,strip_tags");
        $num = Request::param("num","1","intval");

        $array = array_map("intval",explode(",",$id));
        $array = array_filter($array,function ($res){
            return $res != 0;
        });

        if(count($array) <= 0){
            return $this->returnAjax("请选择需要购买的商品",0);
        }else if(empty($type) || !in_array($type,["buy","cart","group","point","second","special"])){
            return $this->returnAjax("非法操作",0);
        }

        $cart = [];
        if($type == "cart"){
            $rs = Db::name("cart")
                ->where("user_id",$this->users["id"])
                ->where("id","in", implode(",",$array))
                ->select()->toArray();

            if(empty($rs)){
                return $this->returnAjax("请选择商品后在提交订单",0);
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
            if($num <= 0){
                $num = 1;
            }

            try{
                $promotion = Promotion::checkOrderType($id,$product_id,$num,$type);
            }catch (\Exception $e){
                return $this->returnAjax($e->getMessage(),0);
            }

            array_push($cart,$promotion);
        }

        try {
            $data = Cart::get($cart);
        }catch (\Exception $e){
            return $this->returnAjax($e->getMessage(),$e->getCode() > 0 ? 1 : 0,$e->getCode());
        }

        $bonus = Db::name("users_bonus")
            ->alias("u")
            ->field("b.*,u.id as users_bonus_id")
            ->join("promotion_bonus b","u.bonus_id=b.id","LEFT")
            ->where("u.user_id",$this->users["id"])
            ->where("u.status",0)
            ->where("b.end_time > " . time())
            ->select()->toArray();

        $coupon = ['y'=>[], 'n'=>[]];
        foreach($bonus as $key=>$value){
            $arr = [
                "available"=>$value["users_bonus_id"],
                "id"=>$value["users_bonus_id"],
                "name"=>$value["name"],
                "condition"=>$value["order_amount"] <= 0 ? "无门槛" : "满".$value["order_amount"].'可使用',
                "startAt"=>$value["start_time"],
                "endAt"=>$value["end_time"],
                "value"=>$value["amount"] * 100,
                "price"=>$value["amount"],
                "valueDesc"=>number_format($value["amount"]),
                "unitDesc"=>"元",
                "reason"=>""
            ];
            if($value["order_amount"] <= 0 || $value["order_amount"] >= $data["real_amount"]){
                $coupon["y"][] = $arr;
            }else{
                $coupon["n"][] = $arr;
            }
        }

        $data["bonus"] = $coupon;

        $address = Db::name("users_address")
            ->where("user_id",$this->users["id"])
            ->select()->toArray();

        $addressData = ["default"=>[],"list"=>[]];
        foreach ($address as $key=>$value){
            $v = [
                "id"=>$value["id"],
                "name"=>$value["accept_name"],
                "tel"=>$value["mobile"],
                "province"=>$value["province"],
                "city"=>$value["city"],
                "area"=>$value["area"],
                "address"=>Area::get_area([$value["province"],$value["city"],$value["area"]],',') . ' ' . $value["address"],
            ];

            if($address_id == $value["id"] || (empty($addressData["default"]) && $value["is_default"])){
                $addressData["default"] = $v;
            }

            $addressData["list"][] = $v;
        }

        $data["address"] = $addressData;

        try {
            Distribution::get($data,$addressData["default"]);
            if($bonus_id > 0 && Db::name("users_bonus")->where("user_id",$this->users["id"])->where("id",$bonus_id)->count()){
                Bonus::apply($data,$bonus_id);
            }

        }catch (\Exception $e){
            return $this->returnAjax($e->getMessage(),$e->getCode() > 0 ? 1 : 0,$e->getCode());
        }

        $data["users_price"] = $this->users["amount"];
        return $this->returnAjax("ok",1,$data);
    }


    public function create(){
        $id = Request::param("id","","trim,strip_tags");
        $bonus_id = Request::param("bonus_id","0","trim,strip_tags");
        $address_id = Request::param("address_id","0","trim,strip_tags");
        $product_id = Request::param("sku_id","","trim,strip_tags");
        $type = Request::param("type","","trim,strip_tags");
        $payment_id = Request::param("payment","","trim,strip_tags");
        $remarks = Request::param("remarks","","trim,strip_tags");
        $num = Request::param("num","1","intval");

        $array = array_map("intval",explode(",",$id));
        $array = array_filter($array,function ($res){
            return $res != 0;
        });

        if(count($array) <= 0){
            return $this->returnAjax("请选择需要购买的商品",0);
        }else if(empty($type) || !in_array($type,["buy","cart","group","point","second","special"])){
            return $this->returnAjax("非法操作",0);
        }

        $cart = [];
        if($type == "cart"){
            $rs = Db::name("cart")
                ->where("user_id",$this->users["id"])
                ->where("id","in", implode(",",$array))
                ->select()->toArray();

            if(empty($rs)){
                return $this->returnAjax("请选择商品后在提交订单",0);
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
            if($num <= 0){
                $num = 1;
            }

            try{
                $promotion = Promotion::checkOrderType($id,$product_id,$num,$type);
            }catch (\Exception $e){
                return $this->returnAjax($e->getMessage(),0);
            }

            array_push($cart,$promotion);
        }

        if(($address = Db::name("users_address")->where("id",$address_id)->find()) == false){
            return $this->returnAjax("您选择的地址不存在，请重新选择",0);
        }

        if($bonus_id > 0 && ($bonus = Db::name("users_bonus")->where("user_id",$this->users["id"])->where("id",$bonus_id)->find()) == false){
            return $this->returnAjax("您选择的优惠劵不存在！",0);
        }

        if(($payment = Db::name("payment")->where("code",$payment_id)->find()) == false){
            return $this->returnAjax("您选择的支付方式不存在！",0);
        }

        if(Check::strlen($remarks) > 100){
            return $this->returnAjax("留言内容不得超过100个字符",0);
        }

        try {
            $data = Cart::get($cart);
            Distribution::get($data,$address);
            if($bonus_id > 0 && Db::name("users_bonus")->where("user_id",$this->users["id"])->where("id",$bonus_id)->count()){
                Bonus::apply($data,$bonus_id);
            }

            $data["address"] = $address;
            $data["payment"] = $payment;
            $data["remarks"] = $remarks;
            $data["user_id"] = $this->users["id"];
            $order_id = \mall\basic\Order::create($data);
            $result = Payment::handle($order_id);
            if($type == 'cart'){
                Cart::delete(array_map(function ($res){
                    return $res["cart_id"];
                },$cart));
            }

            Bonus::updateStatus($bonus_id,$this->users["id"]);
            Promotion::updateStatus($data);
        }catch (\Exception $e){
            return $this->returnAjax($e->getMessage(),$e->getCode() > 0 ? 1 : 0,$e->getCode());
        }

        $result["shop_count"] = Db::name("cart")->where("user_id",$data["user_id"])->count();
        return $this->returnAjax("ok",1,$result);
    }

    public function get_list(){
        $type = Request::param("type","1","intval");
        $page = Request::param("page","1","intval");
        $size = 10;

        $condition = 'user_id=' . $this->users["id"] . ' and ';
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

        $count = Db::name("order")
            ->where($condition)
            ->count();

        $total = ceil($count / $size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $list = Db::name("order")
            ->where($condition)
            ->order("id","DESC")
            ->limit((($page - 1) * $size),$size)
            ->select()->toArray();

        $data = [];
        foreach($list as $key=>$value){
            $data[$key] = [
                "order_id"=>$value["id"],
                "order_no"=>$value["order_no"],
                "type"=>\mall\basic\Order::getOrderTypeText($value["type"],1),
                "pay_status"=>\mall\basic\Order::getPaymentStatusText($value["pay_status"]),
                "order_status"=>\mall\basic\Order::getStatusText(\mall\basic\Order::getStatus($value)),
                "order_amount"=>$value["order_amount"],
                "create_time"=>date("Y-m-d H:i:s",$value["create_time"]),
                "active"=>\mall\basic\Order::getStatus($value)
            ];

            $goods = Db::name("order_goods")->where("order_id",$value["id"])->select()->toArray();
            foreach($goods as $k=>$v){
                $goods_array = json_decode($v["goods_array"],true);
                $data[$key]['item'][$k] = [
                    "title"=>$goods_array["title"],
                    "spec"=>$goods_array["spec"],
                    "thumb_image"=>Tool::thumb($v["thumb_image"],"medium",true),
                    "nums"=>$v["goods_nums"],
                    "price"=>$v["sell_price"]
                ];
            }
        }

        return $this->returnAjax("ok",1,[
            "list"=>$data,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }

    public function detail(){
        $id = Request::post("id","0","intval");
        if(($order = Db::name("order")->where([
            "user_id"=>$this->users["id"],"id"=>$id
        ])->find()) == false){
            return $this->returnAjax("您要查找的订单不存在！",0);
        }

        $order["active"] = \mall\basic\Order::getOrderActive($order);
        $order["create_time"] = date("Y-m-d H:i:s",$order["create_time"]);
        $order["type"] = \mall\basic\Order::getOrderTypeText($order["type"]);
        $order["pay_status"]= \mall\basic\Order::getPaymentStatusText($order["pay_status"]);
        $order["pay_type"] = Db::name("payment")->where(["id"=>$order["pay_type"]])->value("name");
        $order["region"] = Area::get_area([$order['province'],$order['city'],$order['area']],' ');

        $order_goods = Db::name("order_goods")->where([
            "order_id"=>$id
        ])->select()->toArray();

        foreach($order_goods as $key=>$value){
            $goods_array = json_decode($value["goods_array"],true);
            $order["item"][$key] = [
                "title"=>$goods_array["title"],
                "spec"=>!empty($goods_array["spec"]) ? $goods_array["spec"] : "",
                "goods_id"=>$value["goods_id"],
                "goods_no"=>$value["goods_no"],
                "thumb_image"=>Tool::thumb($value["thumb_image"],"medium",true),
                "sell_price"=>$value["sell_price"],
                "nums"=>$value["goods_nums"]
            ];
        }

        return $this->returnAjax("ok",1,[
            "accept_name"=>$order["accept_name"],
            "mobile"=>$order["mobile"],
            "region"=>$order["region"],
            "address"=>$order["address"],
            "order_no"=>$order["order_no"],
            "create_time"=>$order["create_time"],
            "type"=>$order["type"],
            "pay_status"=>$order["pay_status"],
            "order_status"=>\mall\basic\Order::getStatus($order),
            "pay_type"=>$order["pay_type"],
            "payable_freight"=>Tool::moneyPrefix($order["payable_freight"]),
            "order_amount"=>Tool::moneyPrefix($order["order_amount"]),
            "promotions"=>Tool::moneyPrefix($order["promotions"]),
            "real_amount"=>Tool::moneyPrefix($order["real_amount"]),
            "payable_amount"=>Tool::moneyPrefix($order["payable_amount"]),
            "item"=>$order["item"],
            "active"=>$order["active"],
            "users_price"=>$this->users["amount"]
        ]);
    }

    public function refund(){
        $id = Request::post("id","0","intval");
        if(($order = Db::name("order")->where([
                "user_id"=>$this->users["id"],"id"=>$id
            ])->find()) == false){
            return $this->returnAjax("您要查找的订单不存在！",0);
        }

        if(!in_array($order["status"],[2,6,7])){
            return $this->returnAjax("该订单不允许此操作",0);
        }

        $order_goods = Db::name("order_goods")->where([
            "order_id"=>$id
        ])->select()->toArray();

        $refundment = Db::name("order_refundment")->where("order_id",$id)->find();
        $array = explode(",",$refundment["order_goods_id"]);
        foreach($order_goods as $key=>$value){
            $goods_array = json_decode($value["goods_array"],true);
            $order["item"][$key] = [
                "title"=>$goods_array["title"],
                "spec"=>!empty($goods_array["spec"]) ? $goods_array["spec"] : "",
                "goods_id"=>$value["goods_id"],
                "goods_no"=>$value["goods_no"],
                "thumb_image"=>Tool::thumb($value["thumb_image"],"medium",true),
                "sell_price"=>$value["sell_price"],
                "nums"=>$value["goods_nums"]
            ];
            $order["item"][$key]["is_refund"] = (!empty($array) && in_array($value["goods_id"],$array)) ? 1 : 0;
        }

        return $this->returnAjax("ok",1,[
            "payable_freight"=>Tool::moneyPrefix($order["payable_freight"]),
            "order_amount"=>Tool::moneyPrefix($order["order_amount"]),
            "promotions"=>Tool::moneyPrefix($order["promotions"]),
            "real_amount"=>Tool::moneyPrefix($order["real_amount"]),
            "payable_amount"=>Tool::moneyPrefix($order["payable_amount"]),
            "is_refund"=>!empty($refundment),
            "order_status"=>!empty($refundment) ? \mall\basic\Order::getRefundmentText($refundment["pay_status"]) : '',
            "item"=>$order["item"]
        ]);
    }

    public function apply_refund(){
        $id = Request::param("id","0","intval");
        $message = Request::param("message","","trim,strip_tags");

        if(empty($message)){
            return $this->returnAjax("请填写退款说明",0);
        }else if(Check::strlen($message) > 200){
            return $this->returnAjax("退款说明，请控制在200字符内",0);
        }

        if(($order = Db::name("order")->where([
                "user_id"=>$this->users["id"],"id"=>$id
            ])->find()) == false){
            return $this->returnAjax("您要查找的订单不存在！",0);
        }

        try{
            \mall\basic\Order::refundmentApply($order,$message);
        }catch(\Exception $e){
            return $this->returnAjax($e->getMessage(),$e->getCode());
        }

        return $this->returnAjax("您的退款申请己提交，请等待管理员审核");
    }

    public function delivery(){
        $id = Request::post("id","0","intval");
        if(($order = Db::name("order")->where([
                "user_id"=>$this->users["id"],"id"=>$id
            ])->find()) == false){
            return $this->returnAjax("您要查找的订单不存在！",0);
        }

        if(!in_array($order["status"],[2,6,7])){
            return $this->returnAjax("该订单不允许此操作",0);
        }

        $order_goods = Db::name("order_goods")->where([
            "order_id"=>$id
        ])->select()->toArray();

        foreach($order_goods as $key=>$value){
            $goods_array = json_decode($value["goods_array"],true);
            $order["item"][$key] = [
                "title"=>$goods_array["title"],
                "spec"=>!empty($goods_array["spec"]) ? $goods_array["spec"] : "",
                "goods_id"=>$value["goods_id"],
                "goods_no"=>$value["goods_no"],
                "thumb_image"=>Tool::thumb($value["thumb_image"],"medium",true),
                "sell_price"=>$value["sell_price"],
                "nums"=>$value["goods_nums"]
            ];
        }

        return $this->returnAjax("ok",1,[
            "payable_freight"=>Tool::moneyPrefix($order["payable_freight"]),
            "order_amount"=>Tool::moneyPrefix($order["order_amount"]),
            "promotions"=>Tool::moneyPrefix($order["promotions"]),
            "real_amount"=>Tool::moneyPrefix($order["real_amount"]),
            "payable_amount"=>Tool::moneyPrefix($order["payable_amount"]),
            "item"=>$order["item"]
        ]);
    }

    public function confirm_delivery(){
        $id = Request::post("id","0","intval");
        if(($order = Db::name("order")->where([
                "user_id"=>$this->users["id"],"id"=>$id
            ])->find()) == false){
            return $this->returnAjax("您要查找的订单不存在！",0);
        }

        if(!in_array($order["status"],[2,6,7])){
            return $this->returnAjax("该订单不允许此操作",0);
        }

        try{
            Db::name("order")->where(['id'=>$id])->update([
                'status' => 5,
                'delivery_status'=>1,
                'completion_time' => time()
            ]);
            \mall\basic\Order::complete($order["order_no"]);
        }catch (\Exception $e){
            return $this->returnAjax($e->getMessage(),0);
        }

        return $this->returnAjax("确认收货成功");
    }

    public function evaluate(){
        $id = Request::post("id","0","intval");
        if(($order = Db::name("order")->where([
                "user_id"=>$this->users["id"],"id"=>$id
            ])->find()) == false){
            return $this->returnAjax("您要查找的订单不存在！",0);
        }

        if($order["status"] != 5){
            return $this->returnAjax("该订单不允许此操作",0);
        }else if($order["evaluate_status"] == 1){
            return $this->returnAjax("该订单己评价",2);
        }

        $order_goods = Db::name("order_goods")->where([
            "order_id"=>$id
        ])->select()->toArray();

        foreach($order_goods as $key=>$value){
            $goods_array = json_decode($value["goods_array"],true);
            $order["item"][$key] = [
                "title"=>$goods_array["title"],
                "spec"=>!empty($goods_array["spec"]) ? $goods_array["spec"] : "",
                "goods_id"=>$value["goods_id"],
                "goods_no"=>$value["goods_no"],
                "thumb_image"=>Tool::thumb($value["thumb_image"],"medium",true),
                "sell_price"=>$value["sell_price"],
                "nums"=>$value["goods_nums"]
            ];
        }

        return $this->returnAjax("ok",1,[
            "payable_freight"=>Tool::moneyPrefix($order["payable_freight"]),
            "order_amount"=>Tool::moneyPrefix($order["order_amount"]),
            "promotions"=>Tool::moneyPrefix($order["promotions"]),
            "real_amount"=>Tool::moneyPrefix($order["real_amount"]),
            "payable_amount"=>Tool::moneyPrefix($order["payable_amount"]),
            "item"=>$order["item"]
        ]);
    }

    public function do_evaluate(){
        $id = Request::post("id","0","intval");
        $message = Request::post("message","","trim,strip_tags");
        $rate = Request::post("rate","5","intval");
        if(($order = Db::name("order")->where([
                "user_id"=>$this->users["id"],"id"=>$id
            ])->find()) == false){
            return $this->returnAjax("您要查找的订单不存在！",0);
        }

        if($order["evaluate_status"] == 1){
            return $this->returnAjax("您的订单己评价！",0);
        }

        $comment = Db::name("users_comment")->where([
            "user_id"=>$this->users['id'],
            "order_no"=>$order["order_no"],
            "status"=>0
        ])->select()->toArray();

        Db::startTrans();
        try {
            foreach($comment as $value){
                Db::name("users_comment")->where('id',$value["id"])->update([
                    "contents"=>$message,
                    "point"=>$rate,
                    "comment_time"=>time()
                ]);
            }
            Db::name("order")->where([
                "user_id"=>$this->users["id"],"id"=>$id
            ])->update([
                "evaluate_status"=>1
            ]);
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            return $this->returnAjax("请求出错，请稍后在试",0);
        }

        return $this->returnAjax("评价成功",1);
    }

    public function payment(){
        $order_id = Request::param("order_id","","intval");
        $payment_id = Request::param("param","","trim,strip_tags");

        if(($payment = Db::name("payment")->where("code",$payment_id)->find()) == false){
            return $this->returnAjax("支付方式不存在！",0);
        }

        Db::name("order")->where(["user_id"=>$this->users["id"],"id"=>$order_id])->update(["pay_type"=>$payment["id"]]);

        try{
            $result = Payment::handle($order_id);
        }catch (\Exception $e){
            return $this->returnAjax($e->getMessage(),$e->getCode() > 0 ? 1 : 0,$e->getCode());
        }

        return $this->returnAjax("ok",1,$result);
    }

    public function cancel(){
        $id = Request::get("order_id","","intval");
        $condition = ["user_id"=>$this->users["id"],"id"=>$id];
        if(($order=Db::name("order")->where($condition)->find()) == false){
            return $this->returnAjax("您要操作的订单不存在！",0);
        }

        if($order["status"] == 1){
            Db::name("order")->where($condition)->update([
                "status"=>3
            ]);
            Db::name("order_log")->insert([
                'order_id' => $id,
                'username' => $this->users["username"],
                'action' => "取消订单",
                'result' => '成功',
                'note' => "订单【{$order["order_no"]}】客户取消订单",
                'create_time' => time()
            ]);

            return $this->returnAjax("取消订单成功");
        }

        if(in_array($order["status"],[3,4])){
            return $this->returnAjax("非法操作",0);
        }

        return $this->returnAjax("您的订单己付款，不允许此操作",0);
    }

    public function service(){
        $page = Request::param("page","1","intval");
        $size = 10;

        $count = Db::name("order_refundment")
            ->alias("r")
            ->join("order o","r.order_id=o.id","LEFT")
            ->where(["r.user_id"=>$this->users["id"]])->count();

        $total = ceil($count / $size);
        if($total == $page -1){
            return $this->returnAjax("empty",-1,[]);
        }

        $result = Db::name("order_refundment")
            ->alias("r")
            ->field("o.*,r.amount,r.pay_status as r_pay_status,r.create_time as r_create_time")
            ->join("order o","r.order_id=o.id","LEFT")
            ->where(["r.user_id"=>$this->users["id"]])->limit((($page - 1) * $size),$size)
            ->select()->toArray();

        $data = [];
        foreach($result as $key=>$value){
            $data[$key] = [
                "order_id"=>$value["id"],
                "order_no"=>$value["order_no"],
                "type"=>\mall\basic\Order::getOrderTypeText($value["type"],1),
                "pay_status"=>\mall\basic\Order::getRefundmentText($value["r_pay_status"]),
                "order_status"=>\mall\basic\Order::getStatusText(\mall\basic\Order::getStatus($value)),
                "order_amount"=>$value["order_amount"],
                "create_time"=>date("Y-m-d H:i:s",$value["r_create_time"]),
                "active"=>\mall\basic\Order::getOrderActive($value)
            ];

            $goods = Db::name("order_goods")->where("order_id",$value["id"])->select()->toArray();
            foreach($goods as $k=>$v){
                $goods_array = json_decode($v["goods_array"],true);
                $data[$key]['item'][$k] = [
                    "title"=>$goods_array["title"],
                    "spec"=>$goods_array["spec"],
                    "thumb_image"=>Tool::thumb($v["thumb_image"],"medium",true),
                    "nums"=>$v["goods_nums"],
                    "price"=>$v["sell_price"]
                ];
            }
        }

        return $this->returnAjax("ok",1,[
            "list"=>$data,
            "page"=>$page,
            "total"=>$total,
            "size"=>$size
        ]);
    }
}