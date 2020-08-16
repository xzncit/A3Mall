<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\order;

use app\admin\controller\Auth;
use mall\utils\Date;
use mall\basic\Order;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class Index extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            if(isset($key["pay_type"]) && $key["pay_type"] != '-1'){
                $condition["order.pay_type"] = $key["pay_type"];
            }

            if(isset($key["status"]) && $key["status"] != '-1'){
                $condition["order.status"] = $key["status"];
            }

            if(isset($key["distribution_status"]) && $key["distribution_status"] != '-1'){
                $condition["order.distribution_status"] = $key["distribution_status"];
            }

            if(!empty($key["title"])){
                $condition[] = ["order.order_no","like",'%'.$key["title"].'%'];
            }

            $order = new \app\common\model\order\Order();
            $list = $order->getList($condition,$limit);

            if(empty($list['data'])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            foreach($list['data'] as $key=>$item){
                $list['data'][$key] = $item;
                $list['data'][$key]['distribution_status_name'] = Order::getStatusText(Order::getStatus($item));
                $list['data'][$key]['pay_status_name'] = Order::getPaymentStatusText($item["pay_status"]);
                $list['data'][$key]['order_type_name'] = Order::getOrderTypeText($item['type']);
            }

            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch();
    }

    public function detail(){
        $id = (int)Request::param("id");
        if(($row = Db::name("order")->where("id",$id)->find()) == false){
            $this->error("您要查找的订单不存在！");
        }

        $row["type_name"] = Order::getOrderTypeText($row['type']);
        $row["distribution_status_name"] = Order::getSendStatus($row["distribution_status"]);
        $row["order_status"] = Order::getStatus($row);
        $row["order_status_text"] = Order::getStatusText($row["order_status"]);
        $row['order_payment_status_text'] = Order::getPaymentStatusText($row["pay_status"]);

        $row["distribution_name"] = Db::name("distribution")->where(["id"=>$row["distribution_id"]])->value("title");
        $row["payment_name"] = Db::name("payment")->where(["id" => $row["pay_type"]])->value("name");
        $row["goods"] = Db::name("order_goods")->where(["order_id" =>$id])->order("id DESC")->select()->toArray();

        foreach($row["goods"] as $key=>$item){
            $row["goods"][$key]["goods_array"] = "";
            if(!empty($item["goods_array"])){
                $row["goods"][$key]["goods_array"] = json_decode($item["goods_array"],true);
            }

            $row["goods"][$key]["order_price"] = number_format($item["goods_nums"]*$item["sell_price"],2);
        }

        $row["goods_weight"] = 0.00;
        foreach ($row["goods"] as $key => $val) {
            $row["goods_weight"] += $val["goods_nums"] * $val["goods_weight"];
            if ($val["product_id"] <= 0) {
                $goods = Db::name("goods")->where(["id" => $val["goods_id"]])->find();
                $row["goods"][$key]["store_nums"] = $goods["store_nums"];
            } else {
                $product = Db::name("goods_item")->where(["id" => $val["product_id"]])->find();
                $row["goods"][$key]["store_nums"] = $product["store_nums"];
            }
        }

        $row["users"] = Db::name("users")->where(["id" => $row["user_id"]])->find();
        $group_name = Db::name("users_group")->where(["id" => $row["users"]["group_id"]])->value("name");
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

        $row["area_name"] =  implode(",",[$row["province"],$row["city"],$row["area"]]);
        $row["order_log"] = array_map(function($res){
            $res["create_time"] = Date::format($res["create_time"]);
            return $res;
        },Db::name("order_log")->where(["order_id"=>$row["id"]])->select()->toArray());
        $row["create_time"] = Date::format($row["create_time"]);
        $row["pay_time"] = Date::format($row["pay_time"]);
        $row["send_time"] = Date::format($row["send_time"]);
        $row["accept_time"] = Date::format($row["accept_time"]);
        $row["completion_time"] = Date::format($row["completion_time"]);

        return View::fetch("",[
            "data"=>$row
        ]);
    }

    public function payment(){
        if(Request::isAjax()){
            $id = Request::post("id",0,"intval");
            $note = Request::post("note","","trim,strip_tags");
            $order = Db::name("order")->where(["id"=>$id])->find();

            if(empty($order)){
                return Response::returnArray("操作失败，订单不存在！",0);
            }

            try{
                $admin_id = Session::get("system_user_id");
                Order::payment($order["order_no"],$admin_id,$note);
            } catch (\Exception $ex) {
                return Response::returnArray($ex->getMessage(),0);
            }

            $admin_name = Db::name("system_users")->where(["id"=>$admin_id])->value("username");
            Db::name("order_log")->insert([
                'order_id' => $order["id"],
                'username' => $admin_name,
                'action' => '付款',
                'result' => '成功',
                'note' => '订单【' . $order["order_no"] . '】付款' . $order["order_amount"] . '元',
                'create_time' => time()
            ]);

            return Response::returnArray("操作成功！",1,createUrl("detail",["id"=>$id]));
        }

        if(($row = Db::name("order")->where(["id"=>Request::param("id",0,"intval")])->find()) == false){
            $this->error("您要查找的订单不存在！");
        }

        $row["username"] = Db::name("system_users")->where([
            "id"=>Session::get("system_user_id")
        ])->value("username");

        $row["payment_name"] = Db::name("payment")->where(["id" => $row["pay_type"]])->value("name");
        $row["create_time"] = Date::format($row["create_time"]);
        return View::fetch("",["data"=>$row]);
    }

    public function distribution(){
        if(Request::isAjax()){
            $order_id = Request::post("id", 0,"intval");
            $order_goods_id = Request::post("order_goods_id/a",0,"intval");

            try{
                $admin_id = Session::get("system_user_id");
                Order::sendDistributionGoods($order_id, $order_goods_id, $admin_id);
            } catch (\Exception $ex) {
                return Response::returnArray($ex->getMessage(),0);
            }

            return Response::returnArray("操作成功！",1,createUrl("detail",["id"=>$order_id]));
        }

        if(($row = Db::name("order")->where(["id"=>Request::param("id",0,"intval")])->find()) == false){
            $this->error("您要查找的订单不存在！");
        }

        $row["goods"] = Db::name("order_goods")->where(["order_id" =>$row["id"]])->order("id DESC")->select()->toArray();

        $row["goods_weight"] = 0.00;
        foreach ($row["goods"] as $key => $val) {
            $row["goods_weight"] += $val["goods_nums"] * $val["goods_weight"];
            if ($val["product_id"] <= 0) {
                $goods = Db::name("goods")->where(["id" => $val["goods_id"]])->find();
                $row["goods"][$key]["store_nums"] = $goods["store_nums"];
            } else {
                $product = Db::name("goods_item")->where(["id" => $val["product_id"]])->find();
                $row["goods"][$key]["store_nums"] = $product["store_nums"];
            }

            $row["goods"][$key]["goods_array"] = "";
            if(!empty($val["goods_array"])){
                $row["goods"][$key]["goods_array"] = json_decode($val["goods_array"],true);
            }

            $row["goods"][$key]["order_price"] = number_format($val["goods_nums"]*$val["sell_price"],2);
        }

        $row["distribution_name"] = Db::name("distribution")->where(["id"=>$row["distribution_id"]])->value("title");
        $row["create_time"] = Date::format($row["create_time"]);
        return View::fetch("",[
            "data"=>$row,
            "freight"=>Db::name("freight")->where(['status' => 0])->select()->toArray(),
            "province"=>Db::name('area')->where(['pid' => 0])->select()->toArray(),
            "city"=>Db::name('area')->where(['pid' => $row["province"]])->select()->toArray(),
            "area"=>Db::name('area')->where(['pid' => $row["city"]])->select()->toArray()
        ]);
    }

    public function refundment(){
        if(Request::isAjax()){
            $type = Request::post("type","0","intval");
            $amount = Request::post("amount","0","float");
            $id = Request::post("id","0","intval");
            $status = Request::post("status","0","intval");
            $desc = Request::post("desc","","strip_tags,trim");
            $order_goods_id = Request::post("order_goods_id/a",0,"intval");

            if(empty($order_goods_id)){
                return Response::returnArray("请选择需要退款的商品！",0);
            }

            if(($order = Db::name("order")->where(["id"=>$id])->find()) == false){
                return Response::returnArray("您要操作的订单不存在！",0);
            }

            $admin_id = Session::get("system_user_id");
            if($status == 1){
                if(Db::name("order_refundment")->where("order_id",$order["id"])->count()){
                    Db::name("order_refundment")->where("order_id",$order["id"])->update([
                        'admin_id' => $admin_id,'dispose_idea' => $desc,"pay_status"=>1,"dispose_time"=>time()
                    ]);
                }else{
                    return Response::returnArray("该用户未申请退款！",0);
                }

                return Response::returnArray("操作成功！",1,createUrl("detail",["id"=>$id]));
            }

            $orderGoodsList = Db::name("order_goods")->where('id','in',$order_goods_id)->where('is_send',"<>",'2')->select()->toArray();

            if (empty($orderGoodsList)) {
                return Response::returnArray('订单中没有符合退货条件的商品', 0);
            }

            //计算退款商品的原始支付金额
            $actAmount = 0;
            foreach ($orderGoodsList as $val) {
                $orderGoodsRow = $val;
                $actAmount += $orderGoodsRow['real_price'] * $orderGoodsRow['goods_nums'];
            }

            if ($amount > $actAmount) {
                return Response::returnArray('填写的退款金额不能大于实际用户支付的金额', 0);
            }

            try{
                if(($refunds_id = Db::name("order_refundment")->where("order_id",$order["id"])->value("id")) == false){
                    $refunds_id = Db::name("order_refundment")->insert([
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
                    ]);
                }else{
                    Db::name("order_refundment")->where("order_id",$order["id"])->update(['admin_id' => $admin_id,'dispose_idea' => $desc]);
                }

                Order::refund($refunds_id,$admin_id);
            } catch (\Exception $ex) {
                return Response::returnArray($ex->getMessage(),0);
            }

            return Response::returnArray("操作成功！",1,createUrl("detail",["id"=>$id]));
        }

        if(($row = Db::name("order")->where(array("id"=>Request::param("id",0,"intval")))->find()) == false){
            $this->error("您要查找的订单不存在！");
        }

        $row["amount_refund"] = Order::getRefundAmount($row);

        $row["goods"] = Db::name("order_goods")->where(["order_id" =>$row["id"]])->order("id DESC")->select()->toArray();

        $orderRefundment = Db::name("order_refundment")->where("order_id",$row["id"])->find();
        $order_goods_id = isset($orderRefundment["order_goods_id"]) ? explode(",",$orderRefundment["order_goods_id"]) : [];

        foreach ($row["goods"] as $key => $val) {
            $row["goods"][$key]["send_status"] = Order::getSendStatus($val["is_send"]);
            $row["goods"][$key]["goods_array"] = "";
            if(!empty($val["goods_array"])){
                $row["goods"][$key]["goods_array"] = json_decode($val["goods_array"],true);
            }
            $row["goods"][$key]["checked"] = in_array($val["id"],$order_goods_id) ? true : false;
            $row["goods"][$key]["order_price"] = number_format($val["goods_nums"]*$val["sell_price"],2);
        }

        $row["distribution_name"] = Db::name("distribution")->where(["id"=>$row["distribution_id"]])->value("title");
        $row["create_time"] = Date::format($row["create_time"]);
        return View::fetch("",[
            "data"=>$row,
            "freight"=>Db::name("freight")->where(['status' => 0])->select()->toArray(),
            "province"=>Db::name('area')->where(['pid' => 0])->select()->toArray(),
            "city"=>Db::name('area')->where(['pid' => $row["province"]])->select()->toArray(),
            "area"=>Db::name('area')->where(['pid' => $row["city"]])->select()->toArray()
        ]);
    }

    public function complete(){
        $id = Request::param("id","0","intval");
        $status = Request::param("status","0","intval");
        if ($id <= 0 || !in_array($status, [4, 5])) {
            $this->error("参数错误");
        }

        $row = Db::name("order")->where(["id"=>$id])->find();
        Db::name("order")->where(['id'=>$id])->update([
            'status' => $status,
            'completion_time' => time()
        ]);

        if ($status == 5 && in_array($row["distribution_status"],[1,2])) {
            $action = '完成';
            $note = '订单【' . $row['order_no'] . '】完成成功';

            //完成订单并且进行支付
            Order::complete($row['order_no'],Session::get("system_user_id"));
        }else{
            $action = '作废';
            $note = '订单【' . $row['order_no'] . '】作废成功';
        }

        $username = Db::name("system_users")->where(["id"=>Session::get("system_user_id")])->value("username");
        Db::name("order_log")->insert([
            'order_id' => $id,
            'username' => $username,
            'action' => $action,
            'result' => '成功',
            'note' => $note,
            'create_time' => time()
        ]);

        $this->success("操作成功");
    }

    public function update_amount(){
        $id = Request::param("id","0","intval");
        $order = Db::name("order")->where("id",$id)->find();
        if(Request::isAjax()){
            $action = (int)Request::param("action","0");
            $num = (float)Request::param("num","0");

            if(empty($order)){
                return Response::returnArray("您要操作的订单不存在",0);
            }else if($order["pay_status"] == 1){
                return Response::returnArray("您要操作的订单己支付",0);
            }

            if($action == 1 && $num > $order["order_amount"]){
                return Response::returnArray("您要操作的金额超过订单总金额",0);
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

            try {
                Db::name("order")->where("id",$id)->update($data);
                $username = Db::name("system_users")->where(["id"=>Session::get("system_user_id")])->value("username");
                Db::name("order_log")->insert([
                    'order_id' => $id,
                    'username' => $username,
                    'action' => $action == 0 ? "增加金额" : "减少金额",
                    'result' => '成功',
                    'note' => "修改订单金额",
                    'create_time' => time()
                ]);
            }catch (\Exception $e){
                return Response::returnArray($e->getMessage(),0);
            }

            return Response::returnArray("操作成功！",1);
        }

        return View::fetch("",[
            "order"=>$order
        ]);
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("order")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("order")->delete($id);
            Db::name("order_collection")->where(["order_id"=>$id])->delete();
            Db::name("order_delivery")->where(["order_id"=>$id])->delete();
            Db::name("order_goods")->where(["order_id"=>$id])->delete();
            Db::name("order_refundment")->where(["order_id"=>$id])->delete();
            Db::name("order_log")->where(["order_id"=>$id])->delete();
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

}