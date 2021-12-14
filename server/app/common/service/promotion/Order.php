<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\service\promotion;

use app\common\service\Service;

class Order extends Service {

    protected static $array = [
        "0"=>"满额打折",
        "1"=>"满额优惠金额",
        "2"=>"满额送积分",
        "3"=>"满额免运费"
    ];

    /**
     * 订单活动类型
     * @param string $type
     * @return string|string[]
     */
    public static function getActivityType($type=""){
        return isset(self::$array[$type]) ? self::$array[$type] : self::$array;
    }

}