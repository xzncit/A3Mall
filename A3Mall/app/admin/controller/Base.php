<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\BaseController;
use think\response\Redirect;
use think\Response;
use think\exception\HttpResponseException;

class Base extends BaseController {

    protected function success($msg = '', $url = null, $data = '', $wait = 3, array $header = []){
        exit($msg);
    }

    protected function error($msg = '', $url = null, $data = '', $wait = 3, array $header = []){
        exit($msg);
    }


    protected function redirect($url, $params = [], $code = 302, $with = []){
        if (is_integer($params)) {
            $code   = $params;
            $params = [];
        }

        $response = Response::create($url, 'redirect', $code);
        throw new HttpResponseException($response);
        //header("Location: " . createUrl($url,$params));
        exit;
    }

}
