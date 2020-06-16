<?php
namespace app\api\controller\wap;

class Base {

    public function returnAjax($msg = "", $code = 1, $data = []){
        return json(["status" => $code, "info" => $msg, "data" => $data]);
    }

}