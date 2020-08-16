<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\order;

use app\common\model\base\A3Mall;
use app\common\model\users\Users;

class Refundment extends A3Mall{

    protected $name = "order_refundment";

    protected $type = [
        "id"=>"integer",
        "order_id"=>"integer",
        "user_id"=>"integer",
        "type"=>"integer",
        "amount"=>"float",
        "admin_id"=>"integer",
        "pay_status"=>"integer",
        "is_delete"=>"integer",
        "dispose_time"=>"integer",
        "create_time"=>"integer"
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->withJoin(["lorder","users"])->where($condition)->count();
        $data = $this->withJoin(["lorder","users"])->where($condition)->order('refundment.id','DESC')->paginate($size);

        $list = array_map(function ($res){
            $res['url'] = $res->url;
            $res['order_url'] = $res->order_url;
            $res['refundment_text'] = $res->refundment_text;
            return $res;
        },$data->items());

        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

    public function lorder(){
        return $this->hasOne(Order::class,"id","order_id")
            ->joinType("LEFT");
    }

    public function users(){
        return $this->hasOne(Users::class,"id","user_id")
            ->bind(['username'])->joinType("LEFT");
    }

    public function setOrderNoAttr($value){
        return strip_tags(trim($value));
    }

    public function setContentAttr($value){
        return strip_tags(trim($value));
    }

    public function setDisposeIdeaAttr($value){
        return strip_tags(trim($value));
    }

    public function setOrderGoodsIdAttr($value){
        return strip_tags(trim($value));
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getUrlAttr($value,$data){
        return createUrl("detail",["id"=>$data["id"]]);
    }

    public function getOrderUrlAttr($value,$data){
        return createUrl("order.index/detail",["id"=>$data["id"]]);
    }

    public function getRefundmentTextAttr($value,$data){
        $status = ['0' => '申请退款', '1' => '退款失败', '2' => '退款成功'];
        return isset($status[$data['pay_status']]) ? $status[$data['pay_status']] : "未知状态";
    }
}