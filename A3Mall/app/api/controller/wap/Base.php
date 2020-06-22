<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wap;

class Base {

    public function returnAjax($msg = "", $code = 1, $data = []){
        return json(["status" => $code, "info" => $msg, "data" => $data]);
    }

}