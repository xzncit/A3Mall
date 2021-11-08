<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat;

use xzncit\core\Config;
use xzncit\core\Service;
use xzncit\core\exception\ConfigNotFoundException;

/**
 * class Wechat
 *
 * @property \xzncit\wechat\OAuth\OAuth                                         $oauth
 * @property \xzncit\wechat\Script\Script                                       $script
 * @property \xzncit\wechat\Server\Server                                       $server
 * @property \xzncit\wechat\Base\Base                                           $base
 * @property \xzncit\wechat\Menus\Menu                                          $menu
 * @property \xzncit\wechat\Subscription\Subscription                           $subscription
 * @property \xzncit\wechat\Online\Online                                       $online
 * @property \xzncit\wechat\Online\Session\Session                              $kfsession
 * @property \xzncit\wechat\Materials\Materials                                 $materials
 * @property \xzncit\wechat\User\User                                           $user
 * @property \xzncit\wechat\User\Tag\Tag                                        $user_tag
 * @property \xzncit\wechat\QRCode\QRCode                                       $qr_code
 * @property \xzncit\wechat\GenShorten\GenShorten                               $shorten
 * @property \xzncit\wechat\Statistics\Statistics                               $analysis
 * @property \xzncit\wechat\Card\Card                                           $card
 * @property \xzncit\wechat\Card\CouponsMiniProgram\CouponsMiniProgram          $card_mini
 * @property \xzncit\wechat\Card\GiftCard\GiftCard                              $card_gift
 * @property \xzncit\wechat\Card\SpecialTicket\SpecialTicket                    $card_special_ticket
 * @property \xzncit\wechat\Card\DeveloperMode\DeveloperMode                    $card_mode
 * @property \xzncit\wechat\Store\Store                                         $store
 * @property \xzncit\wechat\Store\StoreMiniprogram\StoreMiniprogram             $store_mini
 * @property \xzncit\wechat\Intelligent\Intelligent                             $intelligent
 * @property \xzncit\wechat\MarketCode\MarketCode                               $market_code
 * @property \xzncit\wechat\Template\Template                                   $template
 */
class Wechat extends Service {

    /**
     * @var string[]
     */
    protected $providers = [
        "oauth"                  =>      OAuth\ProviderService::class,
        "script"                 =>      Script\ProviderService::class,
        "server"                 =>      Server\ProviderService::class,
        "base"                   =>      Base\ProviderService::class,
        "menu"                   =>      Menus\ProviderService::class,
        "subscription"           =>      Subscription\ProviderService::class,
        "materials"              =>      Materials\ProviderService::class,
        "online"                 =>      Online\ProviderService::class,
        "kfsession"              =>      Online\Session\ProviderService::class,
        "user"                   =>      User\ProviderService::class,
        "user_tag"               =>      User\Tag\ProviderService::class,
        "qr_code"                =>      QRCode\ProviderService::class,
        "shorten"                =>      GenShorten\ProviderService::class,
        "analysis"               =>      Statistics\ProviderService::class,
        "card"                   =>      Card\ProviderService::class,
        "card_mini"              =>      Card\CouponsMiniProgram\ProviderService::class,
        "card_gift"              =>      Card\GiftCard\ProviderService::class,
        "card_special_ticket"    =>      Card\SpecialTicket\ProviderService::class,
        "card_mode"              =>      Card\DeveloperMode\ProviderService::class,
        "store"                  =>      Store\ProviderService::class,
        "store_mini"             =>      Store\StoreMiniprogram\ProviderService::class,
        "intelligent"            =>      Intelligent\ProviderService::class,
        "market_code"            =>      MarketCode\ProviderService::class,
        "template"               =>      Template\ProviderService::class
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

        if(empty($config["token"])){
            throw new ConfigNotFoundException("Missing Config - token",0);
        }

        parent::__construct($config);
        Config::set($config);
    }

}