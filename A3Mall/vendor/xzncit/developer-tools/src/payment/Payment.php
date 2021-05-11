<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\payment;

use xzncit\core\Config;
use xzncit\core\exception\ConfigNotFoundException;
use xzncit\core\Service;

/**
 * class Payment
 *
 * @property \xzncit\payment\Order\Order                                         $order
 * @property \xzncit\payment\Refund\Refund                                       $refund
 * @property \xzncit\payment\Bill\Bill                                           $bill
 * @property \xzncit\payment\Payitil\Payitil                                     $payitil
 * @property \xzncit\payment\Micropay\Micropay                                   $micropay
 * @property \xzncit\payment\Tools\Coupon\Coupon                                 $coupon
 * @property \xzncit\payment\Tools\RedPack\RedPack                               $redpack
 * @property \xzncit\payment\Tools\MchPay\MchPay                                 $mch_pay
 * @property \xzncit\payment\Tools\Allocation\Allocation                         $allocation
 */
class Payment extends Service{

    /**
     * @var string[]
     */
    protected $providers = [
        "order"                  =>      Order\ProviderService::class,
        "refund"                 =>      Refund\ProviderService::class,
        "bill"                   =>      Bill\ProviderService::class,
        "payitil"                =>      Payitil\ProviderService::class,
        "micropay"               =>      Micropay\ProviderService::class,
        "coupon"                 =>      Tools\Coupon\ProviderService::class,
        "redpack"                =>      Tools\RedPack\ProviderService::class,
        "mch_pay"                =>      Tools\MchPay\ProviderService::class,
        "allocation"             =>      Tools\Allocation\ProviderService::class,
    ];

    /**
     * Wechat constructor.
     * @param array $config
     * @throws ConfigNotFoundException
     */
    public function __construct(array $config){
        if(empty($config["appid"])){
            throw new ConfigNotFoundException("Missing Config - appid",0);
        }

        if(empty($config["mch_id"])){
            throw new ConfigNotFoundException("Missing Config - mch_id",0);
        }

        if(empty($config["mch_key"])){
            throw new ConfigNotFoundException("Missing Config - mch_key",0);
        }

        if(isset($config["ssl_cer"]) && !file_exists($config["ssl_cer"])){
            throw new ConfigNotFoundException("SSL certificate not found: ssl_cer",0);
        }

        if(isset($config["ssl_key"]) && !file_exists($config["ssl_key"])){
            throw new ConfigNotFoundException("SSL certificate not found: ssl_key",0);
        }

        parent::__construct($config);
        Config::set($config);
    }

}