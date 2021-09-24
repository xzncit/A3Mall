<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\statistics;

use app\admin\controller\Auth;
use mall\response\Response;
use mall\utils\Tool;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Sale extends Auth {

    public function index(){
        // 取得会员总数
        $users_count = Db::name("users")->count();

        // 购买过订单的会员数
        $order_count = Db::name("order")
            ->field("COUNT(DISTINCT user_id) as count")
            ->where("user_id > 0 AND status=5")->find();

        // 会员订单总数和订单总购物额
        $order = Db::name("order")
            ->field("COUNT(*) AS order_num,SUM(order_amount) as order_amount_total")
            ->where("user_id > 0 AND status=5")->find();

        // 注册后还未购买过商品的会员
        $users_goods_count = Db::name("users")
            ->field("COUNT(DISTINCT o.user_id) as count")
            ->alias("u")
            ->join("order o","o.user_id=u.id","LEFT")
            ->where("o.user_id > 0 AND o.status=5")
            ->find();
        $not_goods_users_count = $users_count - $users_goods_count["count"];

        // 注册会员购买率
        $cart = sprintf("%0.2f", ($users_count > 0 ? $not_goods_users_count / $users_count : 0) * 100);

        // 订单排行
        $fields = "SUM(o.order_amount) as total,COUNT(o.id) as count,u.username";
        $order_hot = Db::name("order")
            ->field($fields)
            ->alias("o")
            ->join("users u","o.user_id=u.id","LEFT")
            ->where("o.user_id > 0 AND o.status=5")
            ->group("o.user_id")->order("total DESC")
            ->limit(10)->select()->toArray();
        $i=1;
        foreach($order_hot as $k=>$v){
            $order_hot[$k]['p'] = $i++;
        }

        return View::fetch("",[
            "a"=>$cart,
            "b"=>$users_count,
            "c"=>$not_goods_users_count,
            "d"=>$users_goods_count,
            "e"=>empty($order["order_amount_total"]) ? "0.00" : $order["order_amount_total"],
            "f"=>empty($order["order_amount_total"]) ? "0.00" : sprintf("%.2f",($order["order_amount_total"] > 0 && $users_goods_count["count"] > 0 ? $order["order_amount_total"]/$users_goods_count["count"] : 0)),
            "g"=>$order_hot
        ]);
    }

    public function ranking(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $start_time = !empty($key["start_time"]) ? strtotime($key["start_time"]) : "";
            $end_time = !empty($key["end_time"]) ? strtotime($key["end_time"]) : "";

            if(!empty($start_time) && ($start_time > $end_time)){
                return Response::returnArray("开始时间不能大于结束时间！",1);
            }

            $condition = "o.user_id > 0 AND o.status=5";
            if(!empty($start_time)){
                $condition .= " AND (o.create_time >= '{$start_time}' AND o.create_time <= '{$end_time}')";
            }

            $fields = "SUM(o.order_amount) as total,COUNT(o.id) as count,u.username,o.user_id";
            $count = Db::name("order")
                ->field($fields)
                ->alias("o")
                ->join("users u","o.user_id=u.id","LEFT")
                ->where($condition)
                ->group("o.user_id")->order("total DESC")
                ->count();


            $data = Db::name("order")
                ->field($fields)
                ->alias("o")
                ->join("users u","o.user_id=u.id","LEFT")
                ->where("o.user_id > 0 AND o.status=5")
                ->group("o.user_id")->order("total DESC")->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = array_map(function ($res){
                $res["username"] = getUserName($res);
                return $res;
            },$data->items());

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function sale_list(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $start_time = !empty($key["start_time"]) ? strtotime($key["start_time"]) : "";
            $end_time = !empty($key["end_time"]) ? strtotime($key["end_time"]) : "";

            if(!empty($start_time) && ($start_time > $end_time)){
                return Response::returnArray("开始时间不能大于结束时间！",1);
            }

            $condition = "o.user_id > 0 AND o.status=5";
            if(!empty($start_time)){
                $condition .= " AND (o.create_time >= '{$start_time}' AND o.create_time <= '{$end_time}')";
            }

            $count = Db::name("order_goods")->alias("g")
                ->join("order o","g.order_id=o.id","LEFT")
                ->where($condition)
                ->count();

            $data = Db::name("order_goods")->alias("g")
                ->field("g.*,o.order_no,o.create_time")
                ->join("order o","g.order_id=o.id","LEFT")
                ->where($condition)->order("g.goods_nums")
                ->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();
            $tableData = [];
            foreach($list as $key=>$value){
                $goods_array = json_decode($value["goods_array"],true);
                $tableData[$key] = [
                    "goods_img"=>Tool::thumb($value["thumb_image"]),
                    "goods_name"=>$goods_array["title"] . (!empty($goods_array["spec"]) ? '['.$goods_array["spec"].']' : ''),
                    "order_no"=>$value["order_no"],
                    "num"=>$value["goods_nums"],
                    "price"=>$value["real_price"],
                    "time"=>date("Y-m-d H:i:s",$value["create_time"])
                ];
            }

            return Response::returnArray("ok",0,$tableData,$count);
        }

        return View::fetch();
    }

    public function sale_order(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $start_time = !empty($key["start_time"]) ? strtotime($key["start_time"]) : "";
            $end_time = !empty($key["end_time"]) ? strtotime($key["end_time"]) : "";

            if(!empty($start_time) && ($start_time > $end_time)){
                return Response::returnArray("开始时间不能大于结束时间！",1);
            }

            $condition = "o.user_id > 0 AND o.status=5";
            if(!empty($start_time)){
                $condition .= " AND (o.create_time >= '{$start_time}' AND o.create_time <= '{$end_time}')";
            }

            $count = Db::name("order_goods")->alias("g")
                ->join("order o","g.order_id=o.id","LEFT")
                ->where($condition)->group("g.goods_id")
                ->count();

            $data = Db::name("order_goods")->alias("g")
                ->field("g.*,SUM(g.goods_nums) as nums,SUM(g.goods_nums * g.real_price) as total,o.order_no,o.create_time")
                ->join("order o","g.order_id=o.id","LEFT")
                ->where($condition)->group("g.goods_id")
                ->order("g.goods_nums")
                ->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();
            $tableData = [];
            foreach($list as $key=>$value){
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

            return Response::returnArray("ok",0,$tableData,$count);
        }

        return View::fetch();
    }

}