<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\microapp\Payment;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ProviderService implements ServiceProviderInterface{

    public function register(Container $app){
        !isset($app['payment']) && $app['payment'] = function ($app) {
            return new Payment($app);
        };
    }

}