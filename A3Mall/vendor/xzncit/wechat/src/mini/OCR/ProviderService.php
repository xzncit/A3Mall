<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\OCR;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ProviderService implements ServiceProviderInterface{

    public function register(Container $app){
        !isset($app['ocr']) && $app['ocr'] = function ($app) {
            return new OCR($app);
        };
    }

}