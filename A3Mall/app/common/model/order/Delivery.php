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
use app\common\model\base\Freight;
use app\common\model\users\Users;

class Delivery extends A3Mall{

    protected $name = "order_delivery";

    protected $type = [
        "id"=>"integer",
        "order_id"=>"integer",
        "user_id"=>"integer",
        "admin_id"=>"integer",
        "country"=>"integer",
        "province"=>"integer",
        "city"=>"integer",
        "area"=>"integer",
        "freight"=>"float",
        "distribution_id"=>"integer",
        "is_delete"=>"integer",
        "freight_id"=>"integer",
        "create_time"=>"integer"
    ];

    public function lorder(){
        return $this->hasOne(Order::class,"id","order_id")
            ->bind(["order_no"])->joinType("LEFT");
    }

    public function hasFreight(){
        return $this->hasOne(Freight::class,"id","freight_id")
            ->bind(["title"])->joinType("LEFT");
    }

    public function users(){
        return $this->hasOne(Users::class,"id","user_id")
            ->bind(['username'])->joinType("LEFT");
    }

    public function getList($condition,$size=10,$page=1){
        $count = $this->withJoin(["lorder","users","hasFreight"])->where($condition)->count();
        $data = $this->withJoin(["lorder","users","hasFreight"])->where($condition)->order('delivery.id','DESC')->paginate($size);

        return [
            "count"=>$count,
            "data"=>$data->items()
        ];
    }

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setZipAttr($value){
        return strip_tags(trim($value));
    }

    public function setPhoneAttr($value){
        return strip_tags(trim($value));
    }

    public function setAddressAttr($value){
        return strip_tags(trim($value));
    }

    public function setMobileAttr($value){
        return strip_tags(trim($value));
    }

    public function setDistributionCodeAttr($value){
        return strip_tags(trim($value));
    }

    public function setNoteAttr($value){
        return strip_tags(trim($value));
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}