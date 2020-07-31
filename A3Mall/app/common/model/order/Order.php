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
use app\common\model\base\Payment;
use app\common\model\users\Users;

class Order extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "activity_id"=>"integer",
        "user_id"=>"integer",
        "type"=>"integer",
        "pay_type"=>"integer",
        "distribution_id"=>"integer",
        "status"=>"integer",
        "pay_status"=>"integer",
        "distribution_status"=>"integer",
        "delivery_status"=>"integer",
        "evaluate_status"=>"integer",
        "is_delete"=>"integer",
        "insured"=>"float",
        "pay_fee"=>"float",
        "taxes"=>"float",
        "promotions"=>"float",
        "discount"=>"float",
        "increase_amount"=>"float",
        "reduce_amount"=>"float",
        "real_freight"=>"float",
        "payable_freight"=>"float",
        "real_point"=>"integer",
        "real_amount"=>"float",
        "payable_amount"=>"float",
        "order_amount"=>"float",
        "exp"=>"integer",
        "point"=>"integer",
        "source"=>"integer",
        "admin_id"=>"integer",
        "send_time"=>"integer",
        "accept_time"=>"integer",
        "evaluate_time"=>"integer",
        "pay_time"=>"integer",
        "create_time"=>"integer",
        "completion_time"=>"integer"
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->withJoin(["users","payment"])->where($condition)->count();
        $data = $this->withJoin(["users","payment"])->where($condition)->order('order.id','DESC')->paginate($size);

        $list = array_map(function ($res){

            return $res;
        },$data->items());

        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

    public function users(){
        return $this->hasOne(Users::class,"id","user_id")
            ->bind(["username"])->joinType("LEFT");
    }

    public function payment(){
        return $this->hasOne(Payment::class,"id","pay_type")
            ->bind(["payment_name"=>"name"])->joinType("LEFT");
    }

    public function setOrderNoAttr($value){
        return strip_tags(trim($value));
    }

    public function setAcceptNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setZipAttr($value){
        return strip_tags(trim($value));
    }

    public function setMobileAttr($value){
        return strip_tags(trim($value));
    }

    public function setPhoneAttr($value){
        return strip_tags(trim($value));
    }

    public function setCountryAttr($value){
        return strip_tags(trim($value));
    }

    public function setProvinceAttr($value){
        return strip_tags(trim($value));
    }

    public function setCityAttr($value){
        return strip_tags(trim($value));
    }

    public function setAreaAttr($value){
        return strip_tags(trim($value));
    }

    public function setAddressAttr($value){
        return strip_tags(trim($value));
    }

    public function setMessageAttr($value){
        return strip_tags(trim($value));
    }

    public function setNoteAttr($value){
        return strip_tags(trim($value));
    }

    public function setRemarksAttr($value){
        return strip_tags(trim($value));
    }

    public function setTradeNoAttr($value){
        return strip_tags(trim($value));
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}