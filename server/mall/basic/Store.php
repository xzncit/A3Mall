<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\basic;

use mall\utils\BC;
use think\facade\Db;

class Store {

    public static function get(&$data,$store){
        if(empty($store)){
            return $data;
        }

        $data["real_freight"] = 0;
        $data["payable_freight"] = 0;

        $data["payable_amount"] = BC::add($data["real_freight"],$data["real_amount"]);
        $data = Promotion::run($data);
        $data["order_amount"] = $data["payable_amount"];
        return $data;
    }

    public static function getUniqueCode(){
        $num = BC::add(time(),mt_rand(3,1000000),0) . mt_rand(2,99999);
        $num = substr($num, 0, 12);
        if(Db::name("order")->where("shipping_code != '' AND shipping_code='".$num."'")->count()){
            return self::getUniqueCode();
        }

        return $num;
    }

}