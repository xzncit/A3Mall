<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller\wap;

use mall\basic\Users;
use think\facade\Request;
use mall\basic\Order;

class Comments extends Base {

    public function index(){
        $page = Request::param("page","1","intval");
        $id = Request::param("id","0","intval");
        $type = Request::param("type","");
        $size = 10;

        try{
            $type = $type == 'goods' ? 'buy' : $type;
            $type = Order::getOrderType($type);
            $list = Users::getComments($id,$type,$size,$page);
        }catch(\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }

        return $this->returnAjax("ok",1,[
            "list"=>$list["data"],
            "page"=>$page,
            "total"=>$list["total"],
            "size"=>$size
        ]);
    }

}