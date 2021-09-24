<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller\promotion;


use app\admin\controller\Auth;
use app\common\model\users\Luckdraw;
use mall\response\Response;
use think\facade\Request;
use think\facade\View;

class Log  extends Auth{

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $log = new Luckdraw();
            $list = $log->getList([],$limit);

            if(empty($list["data"])){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            return Response::returnArray("ok",0,$list["data"],$list["count"]);
        }

        return View::fetch();
    }

}