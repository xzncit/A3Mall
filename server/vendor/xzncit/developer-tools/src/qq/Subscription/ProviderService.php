<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\qq\Subscription;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use xzncit\qq\TemplateMessage\TemplateMessage;

class ProviderService implements ServiceProviderInterface {

    public function register(Container $app){
        !isset($app['subscription']) && $app['subscription'] = function ($app) {
            return new Subscription($app);
        };
    }

}