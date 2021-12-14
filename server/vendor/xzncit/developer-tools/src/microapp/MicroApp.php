<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\microapp;

use xzncit\core\Config;
use xzncit\core\exception\ConfigNotFoundException;
use xzncit\core\Service;

/**
 * class MicroApp
 *
 * @property \xzncit\microapp\OAuth\OAuth                                         $oauth
 * @property \xzncit\microapp\Storage\Storage                                     $storage
 * @property \xzncit\microapp\QRCode\QRCode                                       $qrcode
 * @property \xzncit\microapp\Security\Security                                   $security
 * @property \xzncit\microapp\Other\Other                                         $other
 * @property \xzncit\microapp\Subscribe\Subscribe                                 $subscribe
 * @property \xzncit\microapp\Live\Live                                           $live
 * @property \xzncit\microapp\Payment\Payment                                     $payment
 * @property \xzncit\microapp\Payment\Merchant\Merchant                           $merchant
 *
 */
class MicroApp extends Service{

    /**
     * @var string[]
     */
    protected $providers = [
        "oauth"                  =>      OAuth\ProviderService::class,
        "storage"                =>      Storage\ProviderService::class,
        "qrcode"                 =>      QRCode\ProviderService::class,
        "security"               =>      Security\ProviderService::class,
        "other"                  =>      Other\ProviderService::class,
        "subscribe"              =>      Subscribe\ProviderService::class,
        "live"                   =>      Live\ProviderService::class,
        "payment"                =>      Payment\ProviderService::class,
        "merchant"               =>      Payment\Merchant\ProviderService::class,
    ];

    /**
     * MicroApp constructor.
     * @param array $config
     * @throws ConfigNotFoundException
     */
    public function __construct(array $config){
        if(empty($config["appid"])){
            throw new ConfigNotFoundException("Missing Config - appid",0);
        }

        if(empty($config["appsecret"])){
            throw new ConfigNotFoundException("Missing Config - appsecret",0);
        }

        parent::__construct($config);
        Config::set($config);
    }

}