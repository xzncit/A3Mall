<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\delivery;

use xzncit\core\Service;

class Delivery extends Service {

    /**
     * @var string[]
     */
    protected $providers = [];

    /**
     * Wechat constructor.
     * @param array $config
     */
    public function __construct(array $config){
        parent::__construct($config);
    }

}