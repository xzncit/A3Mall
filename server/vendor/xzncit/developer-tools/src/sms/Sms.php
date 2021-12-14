<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\sms;

use xzncit\core\Service;

/**
 * class Sms
 *
 * @property \xzncit\sms\Aliyun\Aliyun                                         $aliyun
 */
class Sms extends Service{

    /**
     * @var string[]
     */
    protected $providers = [
        "aliyun"    =>  Aliyun\ProviderService::class
    ];

    /**
     * Wechat constructor.
     * @param array $config
     */
    public function __construct(array $config){
        parent::__construct($config);
    }

}