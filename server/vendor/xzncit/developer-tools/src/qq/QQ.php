<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\qq;

use xzncit\core\Config;
use xzncit\core\exception\ConfigNotFoundException;
use xzncit\core\Service;

/**
 * class Payment
 *
 * @property \xzncit\qq\OAuth\OAuth                         $oauth
 * @property \xzncit\qq\Security\Security                   $security
 * @property \xzncit\qq\Subscription\Subscription           $subscription
 * @property \xzncit\qq\QRCode\QRCode                       $qrcode
 * @property \xzncit\qq\Payment\Wechat\Wechat               $wechat
 */
class QQ  extends Service{

    /**
     * @var string[]
     */
    protected $providers = [
        "oauth"                  => OAuth\ProviderService::class,
        "security"               => Security\ProviderService::class,
        "subscription"           => Subscription\ProviderService::class,
        "qrcode"                 => QRCode\ProviderService::class,
        "wechat"                 => Payment\Wechat\ProviderService::class,
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

        if(empty($config["appsecret"])){
            throw new ConfigNotFoundException("Missing Config - appsecret",0);
        }

        parent::__construct($config);
        Config::set($config);
    }

}